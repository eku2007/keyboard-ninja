

<?php
session_start();

if (!isset($_SESSION["player_id"])) {
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Speed Test | Keyboard Ninja</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="navbar">
    <a href="../index.php" class="logo">
        🥷 KEYBOARD<span class="highlight">NINJA</span>
    </a>

    <nav>
        <a href="../index.php">Home</a>
        <a href="typing-test.php" class="active">Typing Test</a>
        <a href="ninja-mode.php">Ninja Mode</a>
        <a href="leaderboard.php">Leaderboard</a>
    </nav>

    <a href="../index.php" class="nav-button">← Back Home</a>
</header>


<main class="typing-page">

    <div class="typing-heading">
        <span class="tag">⚡ TEST YOUR SKILLS</span>
        <h1>Typing Speed <span class="gradient-text">Challenge</span></h1>
        <p>How fast can your fingers move? Prove your ninja skills!</p>
    </div>


    <section class="typing-box">

        <div class="game-toolbar">

            <div>
                <label for="duration">SELECT TIME</label>
                <select id="duration">
                    <option value="30">30 Seconds</option>
                    <option value="60" selected>60 Seconds</option>
                    <option value="120">120 Seconds</option>
                </select>
            </div>

            <div class="timer-display">
                <span>TIME LEFT</span>
                <h2 id="timer">60s</h2>
            </div>

        </div>


        <div class="typing-stats">

            <div class="stat-card">
                <span>WPM</span>
                <h2 id="wpm">0</h2>
            </div>

            <div class="stat-card">
                <span>ACCURACY</span>
                <h2 id="accuracy">100%</h2>
            </div>

            <div class="stat-card">
                <span>ERRORS</span>
                <h2 id="errors">0</h2>
            </div>

        </div>


        <div class="passage-container">
            <div class="passage-title">⌨ TYPE THE FOLLOWING TEXT</div>

            <div id="passage" class="passage-text"></div>
        </div>


        <textarea
            id="typingInput"
            placeholder="Click Start Test, then type here..."
            disabled
            spellcheck="false"
            autocomplete="off"
        ></textarea>


        <div class="typing-actions">
            <button id="startBtn" class="primary-button">
                ⚔ Start Test
            </button>

            <button id="resetBtn" class="secondary-button">
                ↻ Restart
            </button>
        </div>


        
<div id="result" class="result-box">
    <h2>🏆 Challenge Completed!</h2>
    <p id="resultMessage"></p>
    <p id="saveStatus">Saving your score...</p>
</div>

    </section>

    <p class="typing-note">
        💡 Tip: Focus on accuracy first. Speed will improve with practice.
    </p>

</main>


<footer>
    <p>🥷 KEYBOARD NINJA © 2026 | Type Fast. Think Faster.</p>
</footer>
<script src="../js/game.js?v=2"></script>

</body>
</html>
