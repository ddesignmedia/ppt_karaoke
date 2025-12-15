<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Präsentations Karaoke</title>
    <style>
        /* DSGVO-KONFORMES STYLING
           Keine externen Fonts oder Libraries.
           Alles läuft lokal im Browser.
        */
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --radius: 12px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        /* Header */
        header {
            text-align: center;
            margin-bottom: 2rem;
            max-width: 600px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.025em;
        }

        p.subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        /* Main Container */
        main {
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* --- NEU: Auswahlbereich --- */
        .selection-area {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .selection-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .selection-title {
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .selection-controls button {
            background: transparent;
            border: 1px solid var(--border-color);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-left: 0.5rem;
            transition: all 0.2s;
        }

        .selection-controls button:hover {
            background: var(--bg-color);
            color: var(--text-main);
            border-color: #cbd5e1;
        }

        /* Das Grid für die Themen */
        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
            max-height: 300px; /* Scrollbar wenn es zu viele werden */
            overflow-y: auto;
            padding-right: 5px; /* Platz für Scrollbar */
        }

        /* Scrollbar Styling (Webkit) */
        .file-grid::-webkit-scrollbar {
            width: 6px;
        }
        .file-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .file-grid::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        /* Die einzelne Karte */
        .file-card {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            user-select: none;
        }

        .file-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        /* Status: Ausgewählt */
        .file-card.selected {
            border-color: var(--primary-color);
            background-color: var(--primary-light);
            color: var(--primary-color);
            font-weight: 500;
        }

        .checkbox-indicator {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #fff;
        }

        .file-card.selected .checkbox-indicator {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .checkbox-indicator::after {
            content: '✓';
            color: white;
            font-size: 14px;
            display: none;
        }

        .file-card.selected .checkbox-indicator::after {
            display: block;
        }

        .file-name {
            font-size: 0.9rem;
            line-height: 1.3;
            word-break: break-word;
        }

        /* --- Haupt-Aktion --- */
        .action-area {
            display: flex;
            justify-content: center;
        }

        .random-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .random-btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .random-btn:active {
            transform: translateY(0);
        }

        .random-btn:disabled {
            background-color: #cbd5e1;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Icon Styling */
        .icon {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Vorschau Bereich */
        #preview-container {
            width: 100%;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            display: none;
            animation: fadeIn 0.5s ease-out;
            border: 1px solid var(--border-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 1rem;
        }

        #file-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .fullscreen-btn {
            background: transparent;
            border: 1px solid #cbd5e1;
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .fullscreen-btn:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        /* Iframe Container */
        .iframe-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 */
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .no-pdf-support {
            display: none;
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        footer {
            margin-top: auto;
            padding-top: 2rem;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* --- NEU: Abstimmungsbereich --- */
        .voting-area {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            display: none; /* Initial hidden */
            animation: fadeIn 0.5s ease-out;
            margin-top: 2rem;
        }

        .voting-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .voting-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        .stat-item strong {
            color: var(--text-main);
            font-size: 1.3rem;
        }

        .voting-buttons {
            display: none; /* Hidden, using mobile voting */
        }

        /* QR Code Container */
        .qr-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 10px;
        }

        #qrcode {
            padding: 10px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .qr-hint {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* Leaderboard */
        .leaderboard-area {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-top: 2rem;
            display: none;
        }

        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .leaderboard-table th, .leaderboard-table td {
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .leaderboard-table th {
            font-weight: 600;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <header>
        <h1>Präsentations Karaoke</h1>
        <p class="subtitle">Wähle deine Themen aus und lass den Zufall entscheiden!</p>
    </header>

    <main>
        <!-- NEU: Auswahlbereich -->
        <div class="selection-area">
            <div class="selection-header">
                <div class="selection-title">
                    <svg class="icon" style="width:20px; height:20px;" viewBox="0 0 24 24">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    Themenauswahl
                </div>
                <div class="selection-controls">
                    <button onclick="selectAll(true)">Alle auswählen</button>
                    <button onclick="selectAll(false)">Auswahl aufheben</button>
                </div>
            </div>

            <div id="file-grid" class="file-grid">
                <!-- Hier werden die PDFs per Javascript eingefügt -->
            </div>
            <div style="margin-top: 10px; font-size: 0.85rem; color: #64748b; text-align: right;">
                <span id="count-display">0</span> Themen aktiv
            </div>
        </div>

        <!-- Start Button -->
        <div class="action-area">
            <button id="random-btn" class="random-btn" onclick="pickRandomPdf()">
                <svg class="icon" viewBox="0 0 24 24">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                    <path d="M16 8h.01"></path>
                    <path d="M8 8h.01"></path>
                    <path d="M8 16h.01"></path>
                    <path d="M16 16h.01"></path>
                    <path d="M12 12h.01"></path>
                </svg>
                Zufallsthema starten
            </button>
        </div>

        <!-- Vorschau Bereich -->
        <div id="preview-container">
            <div class="preview-header">
                <span id="file-title">Noch nichts ausgewählt</span>
                <button class="fullscreen-btn" onclick="toggleFullscreen()">
                    <svg class="icon" style="width:18px; height:18px;" viewBox="0 0 24 24">
                        <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2-2h3"></path>
                    </svg>
                    Vollbild
                </button>
            </div>

            <div class="iframe-wrapper" id="iframeWrapper">
                <iframe id="pdfViewer" src="" title="PDF Vorschau"></iframe>
                <div class="no-pdf-support">
                    <p>Dein Browser unterstützt keine PDF-Vorschau.</p>
                </div>
            </div>
        </div>

        <!-- Abstimmungsbereich -->
        <div id="voting-area" class="voting-area">
            <div class="voting-header">
                <h2>Publikums-Wertung</h2>
                <p class="subtitle">Scanne den QR-Code um abzustimmen!</p>
            </div>

            <div class="qr-area">
                <div id="qrcode"></div>
                <div class="qr-hint">Oder gehe auf: <span id="vote-url">...</span></div>
            </div>

            <div class="voting-stats">
                <div class="stat-item">Stimmen: <strong id="vote-count">0</strong></div>
                <div class="stat-item">Durchschnitt: <strong id="vote-average">-</strong></div>
            </div>
        </div>

        <!-- Leaderboard -->
        <div class="leaderboard-area" id="leaderboard-area">
            <h3>Rangliste</h3>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Platz</th>
                        <th>Thema</th>
                        <th>Note</th>
                        <th>Stimmen</th>
                    </tr>
                </thead>
                <tbody id="leaderboard-body">
                    <!-- Rows -->
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        &copy; 2025 Präsentations Karaoke - DSGVO Konform & Lokal
    </footer>

    <script src="js/qrcode.min.js"></script>
    <script>
        /**
         * KONFIGURATION
         * Liste deiner PDF-Dateien im Ordner "pdf"
         */
        const pdfFiles = [
            "beispiel_thema_1.pdf",
            "geschichte_der_ente.pdf",
            "warum_bananen_krumm_sind.pdf",
            "finanzamt_strategien.pdf",
            "quantenphysik_fuer_anfaenger.pdf",
            "die_kunst_des_kaffeekochens.pdf",
            "roboter_im_klassenzimmer.pdf"
            // Weitere Dateien hier...
        ];

        const pdfFolder = "pdf/";

        // State Management: Welche Indizes sind ausgewählt?
        // Initial alle ausgewählt (Set speichert Indizes)
        let selectedIndices = new Set(pdfFiles.map((_, i) => i));

        // DOM Elemente
        const fileGrid = document.getElementById('file-grid');
        const countDisplay = document.getElementById('count-display');
        const randomBtn = document.getElementById('random-btn');
        const previewContainer = document.getElementById('preview-container');
        const pdfViewer = document.getElementById('pdfViewer');
        const fileTitle = document.getElementById('file-title');
        const iframeWrapper = document.getElementById('iframeWrapper');

        // Voting Elemente
        const votingArea = document.getElementById('voting-area');
        const voteCountEl = document.getElementById('vote-count');
        const voteAverageEl = document.getElementById('vote-average');
        const leaderboardArea = document.getElementById('leaderboard-area');
        const leaderboardBody = document.getElementById('leaderboard-body');

        // Voting State
        let currentVotes = [];
        let currentFileIndex = -1;
        let sessionResults = []; // { filename: string, average: number, count: number }

        // Initialisierung
        function init() {
            renderFileList();
            updateUIState();
            updateLeaderboard();
        }

        // Hilfsfunktion: Dateinamen schön formatieren
        function formatFilename(filename) {
            let name = filename.replace('.pdf', '').replace(/_/g, ' ');
            return name.charAt(0).toUpperCase() + name.slice(1);
        }

        // Liste rendern
        function renderFileList() {
            fileGrid.innerHTML = '';

            if (pdfFiles.length === 0) {
                fileGrid.innerHTML = '<div style="color:red; padding:10px;">Bitte Dateinamen im Code eintragen!</div>';
                return;
            }

            pdfFiles.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = selectedIndices.has(index) ? 'file-card selected' : 'file-card';
                card.onclick = () => toggleSelection(index);

                const indicator = document.createElement('div');
                indicator.className = 'checkbox-indicator';

                const nameSpan = document.createElement('span');
                nameSpan.className = 'file-name';
                nameSpan.textContent = formatFilename(file);

                card.appendChild(indicator);
                card.appendChild(nameSpan);
                fileGrid.appendChild(card);
            });
        }

        // Auswahl umschalten (Einzeln)
        function toggleSelection(index) {
            if (selectedIndices.has(index)) {
                selectedIndices.delete(index);
            } else {
                selectedIndices.add(index);
            }
            renderFileList(); // Neu zeichnen, um Klassen zu aktualisieren
            updateUIState();
        }

        // Alle / Keine auswählen
        function selectAll(shouldSelectAll) {
            if (shouldSelectAll) {
                selectedIndices = new Set(pdfFiles.map((_, i) => i));
            } else {
                selectedIndices.clear();
            }
            renderFileList();
            updateUIState();
        }

        // UI Updates (Button Status, Zähler)
        function updateUIState() {
            const count = selectedIndices.size;
            countDisplay.textContent = count;

            if (count === 0) {
                randomBtn.disabled = true;
                randomBtn.title = "Bitte wähle mindestens ein Thema aus";
                randomBtn.style.opacity = "0.5";
            } else {
                randomBtn.disabled = false;
                randomBtn.title = "";
                randomBtn.style.opacity = "1";
            }
        }

        // Zufallslogik
        function pickRandomPdf() {
            // Array aus dem Set machen
            const activeIndices = Array.from(selectedIndices);

            if (activeIndices.length === 0) {
                alert("Bitte wähle mindestens ein Thema aus!");
                return;
            }

            // Zufälligen Index aus den *aktiven* Indizes wählen
            const randomPick = activeIndices[Math.floor(Math.random() * activeIndices.length)];
            const selectedFile = pdfFiles[randomPick];

            // UI Update
            fileTitle.textContent = formatFilename(selectedFile);

            // PDF laden
            const pdfParams = "#toolbar=0&navpanes=0&view=FitH";
            const timestamp = new Date().getTime();

            pdfViewer.src = `${pdfFolder}${selectedFile}?t=${timestamp}${pdfParams}`;

            // Container anzeigen
            previewContainer.style.display = "block";
            votingArea.style.display = "block";

            // Zum Player scrollen
            previewContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });

            // --- SERVER SYNC START ---
            notifyServerStart(selectedFile);
            // --- SERVER SYNC END ---
        }

        // --- Voting Logic (Server based) ---

        let statsInterval = null;

        function notifyServerStart(filename) {
            const formData = new FormData();
            formData.append('filename', filename);

            fetch('api.php?action=start_topic', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                resetVotingUI();
                renderQRCode();
                startPolling();
            })
            .catch(err => console.error("API Error", err));
        }

        function renderQRCode() {
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = ''; // Clear previous

            // Determine URL (assume vote.php is in same dir)
            // Use PHP-injected IP if available, or fall back to window.location
            // Since this file is now .php, we could use PHP to inject IP, but simple JS works for generic access
            // But for mobile to scan, 'localhost' won't work.

            // Try to use window.location, but replace 'localhost' with machine IP if possible?
            // Client side JS cannot easily get LAN IP.
            // So we rely on the user accessing index.php via LAN IP, OR we assume PHP helped us.
            // Let's use the current hostname.

            const protocol = window.location.protocol;
            const host = window.location.hostname;
            const port = window.location.port ? ':' + window.location.port : '';

            // Construct base URL
            let baseUrl = `${protocol}//${host}${port}`;

            // Get path to vote.php (removing index.php or trailing slash)
            let path = window.location.pathname;
            path = path.substring(0, path.lastIndexOf('/'));

            const voteUrl = `${baseUrl}${path}/vote.php`;

            document.getElementById('vote-url').textContent = voteUrl;

            new QRCode(qrContainer, {
                text: voteUrl,
                width: 150,
                height: 150
            });
        }

        function resetVotingUI() {
            voteCountEl.textContent = "0";
            voteAverageEl.textContent = "-";
        }

        function startPolling() {
            if (statsInterval) clearInterval(statsInterval);
            statsInterval = setInterval(fetchStats, 2000); // Poll every 2s
            fetchStats(); // Immediate
        }

        function fetchStats() {
            fetch('api.php?action=get_status')
            .then(r => r.json())
            .then(data => {
                // Update Current Stats
                if (data.current) {
                    voteCountEl.textContent = data.current.count;
                    voteAverageEl.textContent = data.current.average;
                }

                // Update Leaderboard
                if (data.leaderboard) {
                    updateLeaderboard(data.leaderboard);
                }
            })
            .catch(console.error);
        }

        function updateLeaderboard(sortedResults) {
            leaderboardBody.innerHTML = '';

            if (!sortedResults || sortedResults.length === 0) {
                leaderboardArea.style.display = 'none';
                return;
            }

            leaderboardArea.style.display = 'block';

            sortedResults.forEach((entry, index) => {
                const tr = document.createElement('tr');

                // Platz
                const tdRank = document.createElement('td');
                tdRank.textContent = index + 1 + ".";

                // Name
                const tdName = document.createElement('td');
                tdName.textContent = formatFilename(entry.filename);

                // Note
                const tdScore = document.createElement('td');
                tdScore.innerHTML = `<strong>${entry.average}</strong>`;

                // Stimmen
                const tdCount = document.createElement('td');
                tdCount.textContent = (entry.votes ? entry.votes.length : 0);

                tr.appendChild(tdRank);
                tr.appendChild(tdName);
                tr.appendChild(tdScore);
                tr.appendChild(tdCount);

                leaderboardBody.appendChild(tr);
            });
        }

        // Vollbild Logik
        function toggleFullscreen() {
            const element = iframeWrapper;

            if (!document.fullscreenElement) {
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Start
        init();

    </script>
</body>
</html>