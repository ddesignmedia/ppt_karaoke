<?php
/**
 * API for Presentation Karaoke Voting
 * Handles topic tracking, voting, and leaderboard stats.
 * Data is stored in data/votes.json
 */

header('Content-Type: application/json');

$dataDir = __DIR__ . '/data';
$dataFile = $dataDir . '/votes.json';

// Ensure data directory exists
if (!is_dir($dataDir)) {
    if (!mkdir($dataDir, 0777, true)) {
        echo json_encode(['error' => 'Failed to create data directory']);
        exit;
    }
}

// Initialize data structure if not exists (Atomic check handled inside lock later, but good for first run)
if (!file_exists($dataFile)) {
    $initialData = [
        'current_topic' => null,
        'session_results' => [],
        'current_votes' => []
    ];
    file_put_contents($dataFile, json_encode($initialData, JSON_PRETTY_PRINT));
}

$action = $_GET['action'] ?? '';

// Open file with exclusive lock for the duration of the script execution (Read-Modify-Write)
$fp = fopen($dataFile, 'r+');
if (!$fp) {
    echo json_encode(['error' => 'Could not open data file']);
    exit;
}

if (!flock($fp, LOCK_EX)) {
    echo json_encode(['error' => 'Could not lock data file']);
    fclose($fp);
    exit;
}

// Read current data
$content = stream_get_contents($fp);
$data = $content ? json_decode($content, true) : null;

if (!$data) {
    // Fallback if empty or corrupt
    $data = [
        'current_topic' => null,
        'session_results' => [],
        'current_votes' => []
    ];
}

$response = [];

try {
    switch ($action) {
        case 'start_topic':
            $filename = $_POST['filename'] ?? '';
            if (!$filename) {
                $response = ['error' => 'No filename provided'];
                break;
            }

            // Save previous topic results if any
            if ($data['current_topic'] && !empty($data['current_votes'])) {
                saveTopicResult($data);
            }

            // Reset for new topic
            $data['current_topic'] = [
                'filename' => $filename,
                'start_time' => time()
            ];
            $data['current_votes'] = [];

            writeAndSave($fp, $data);
            $response = ['status' => 'ok', 'topic' => $filename];
            break;

        case 'vote':
            $score = intval($_POST['score'] ?? 0);
            if ($score < 1 || $score > 10) {
                $response = ['error' => 'Invalid score'];
                break;
            }

            if (!$data['current_topic']) {
                $response = ['error' => 'No active topic'];
                break;
            }

            $data['current_votes'][] = $score;
            writeAndSave($fp, $data);
            $response = ['status' => 'ok'];
            break;

        case 'get_status':
            $currentStats = [
                'count' => count($data['current_votes']),
                'average' => calculateAverage($data['current_votes']),
                'topic' => $data['current_topic']['filename'] ?? null
            ];

            $leaderboard = $data['session_results'];

            // Add current topic to leaderboard view (without saving it to session_results yet)
            if ($data['current_topic'] && !empty($data['current_votes'])) {
                $found = false;
                foreach ($leaderboard as &$entry) {
                    if ($entry['filename'] === $data['current_topic']['filename']) {
                        $entry['votes'] = $data['current_votes'];
                        $entry['average'] = calculateAverage($data['current_votes']);
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $leaderboard[] = [
                        'filename' => $data['current_topic']['filename'],
                        'votes' => $data['current_votes'],
                        'average' => calculateAverage($data['current_votes'])
                    ];
                }
            }

            usort($leaderboard, function($a, $b) {
                return $b['average'] <=> $a['average'];
            });

            $response = [
                'current' => $currentStats,
                'leaderboard' => array_values($leaderboard)
            ];
            break;

        case 'get_current_topic':
            $response = [
                'topic' => $data['current_topic']['filename'] ?? null
            ];
            break;

        default:
            $response = ['error' => 'Invalid action'];
            break;
    }
} catch (Exception $e) {
    $response = ['error' => 'Server error: ' . $e->getMessage()];
}

// Release lock and close
flock($fp, LOCK_UN);
fclose($fp);

echo json_encode($response);


// --- Helper Functions ---

function writeAndSave($fp, $data) {
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
}

function calculateAverage($votes) {
    if (empty($votes)) return 0;
    return round(array_sum($votes) / count($votes), 1);
}

function saveTopicResult(&$data) {
    $filename = $data['current_topic']['filename'];
    $votes = $data['current_votes'];
    $avg = calculateAverage($votes);

    $found = false;
    foreach ($data['session_results'] as &$entry) {
        if ($entry['filename'] === $filename) {
            $entry['votes'] = $votes;
            $entry['average'] = $avg;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $data['session_results'][] = [
            'filename' => $filename,
            'votes' => $votes,
            'average' => $avg
        ];
    }
}
