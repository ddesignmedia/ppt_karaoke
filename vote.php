<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abstimmung - Präsentations Karaoke</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }

        .container {
            max-width: 400px;
            width: 100%;
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            text-align: center;
            margin-top: 1rem;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .topic-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 2rem;
            min-height: 1.5em;
        }

        .vote-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .vote-btn {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--text-main);
        }

        .vote-btn:active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: scale(0.95);
        }

        .success-msg {
            display: none;
            background: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .waiting-msg {
            display: none;
            color: #64748b;
            font-style: italic;
        }

    </style>
</head>
<body>

    <div class="container" id="voting-interface">
        <h1>Bewerte den Vortrag</h1>
        <div id="topic-display" class="topic-name">Lade Thema...</div>

        <div class="vote-grid">
            <button class="vote-btn" onclick="submitVote(1)">1</button>
            <button class="vote-btn" onclick="submitVote(2)">2</button>
            <button class="vote-btn" onclick="submitVote(3)">3</button>
            <button class="vote-btn" onclick="submitVote(4)">4</button>
            <button class="vote-btn" onclick="submitVote(5)">5</button>
            <button class="vote-btn" onclick="submitVote(6)">6</button>
            <button class="vote-btn" onclick="submitVote(7)">7</button>
            <button class="vote-btn" onclick="submitVote(8)">8</button>
            <button class="vote-btn" onclick="submitVote(9)">9</button>
            <button class="vote-btn" onclick="submitVote(10)">10</button>
        </div>
    </div>

    <div class="container" id="success-screen" style="display:none;">
        <h2 style="color: #166534;">Stimme gezählt!</h2>
        <p>Danke für deine Bewertung.</p>
        <button class="vote-btn" style="width:100%; margin-top:1rem;" onclick="resetView()">Zurück</button>
    </div>

    <div class="container" id="no-active-topic" style="display:none;">
        <h2>Kein aktiver Vortrag</h2>
        <p>Bitte warte, bis der nächste Vortrag beginnt.</p>
    </div>

    <script>
        let currentTopic = null;

        function init() {
            checkTopic();
            // Poll for topic changes every 5 seconds
            setInterval(checkTopic, 5000);
        }

        function formatFilename(filename) {
            if (!filename) return "";
            let name = filename.replace('.pdf', '').replace(/_/g, ' ');
            return name.charAt(0).toUpperCase() + name.slice(1);
        }

        function checkTopic() {
            fetch('api.php?action=get_current_topic')
                .then(r => r.json())
                .then(data => {
                    if (data.topic) {
                        const formatted = formatFilename(data.topic);

                        // If topic changed, reset view
                        if (currentTopic !== data.topic) {
                            currentTopic = data.topic;
                            document.getElementById('topic-display').textContent = formatted;
                            resetView();
                        }

                        document.getElementById('no-active-topic').style.display = 'none';
                        if (document.getElementById('success-screen').style.display === 'none') {
                            document.getElementById('voting-interface').style.display = 'block';
                        }
                    } else {
                        currentTopic = null;
                        document.getElementById('voting-interface').style.display = 'none';
                        document.getElementById('success-screen').style.display = 'none';
                        document.getElementById('no-active-topic').style.display = 'block';
                    }
                })
                .catch(console.error);
        }

        function submitVote(score) {
            const formData = new FormData();
            formData.append('score', score);

            fetch('api.php?action=vote', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok') {
                    showSuccess();
                } else {
                    alert('Fehler: ' + (data.error || 'Unbekannt'));
                }
            })
            .catch(err => alert('Netzwerkfehler'));
        }

        function showSuccess() {
            document.getElementById('voting-interface').style.display = 'none';
            document.getElementById('success-screen').style.display = 'block';
        }

        function resetView() {
            document.getElementById('success-screen').style.display = 'none';
            document.getElementById('voting-interface').style.display = 'block';
        }

        init();
    </script>
</body>
</html>