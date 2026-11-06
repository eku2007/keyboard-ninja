
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

    <title>Ninja Mode | Keyboard Ninja</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="navbar">

    <a href="../index.php" class="logo">
        🥷 KEYBOARD<span class="highlight">NINJA</span>
    </a>

    <nav>
        <a href="../index.php">Home</a>
        <a href="typing-test.php">Typing Test</a>
        <a href="ninja-mode.php" class="active">Ninja Mode</a>
        <a href="leaderboard.php">Leaderboard</a>
    </nav>

    <a href="../index.php" class="nav-button">← Back Home</a>

</header>

<main class="ninja-page">

    <div class="ninja-heading">

        <span class="tag">⚔️ ARCADE CHALLENGE</span>

        <h1>NINJA <span class="gradient-text">MODE</span></h1>

        <p>
            Type the falling words before they escape.
            Don't lose all your lives!
        </p>

    </div>

    <section class="arcade-box">

        <div class="arcade-stats">

            <div class="arcade-stat">
                <span>SCORE</span>
                <h2 id="ninjaScore">0</h2>
            </div>

            <div class="arcade-stat">
                <span>LEVEL</span>
                <h2 id="ninjaLevel">1</h2>
            </div>

            <div class="arcade-stat">
                <span>LIVES</span>
                <h2 id="ninjaLives">❤️ ❤️ ❤️</h2>
            </div>

            <div class="arcade-stat">
                <span>TIME</span>
                <h2 id="ninjaTime">0s</h2>
            </div>

        </div>

        <div id="gameArena" class="game-arena">

            <div class="arena-message" id="arenaMessage">

                <div class="arena-icon">🥷</div>

                <h2>READY, NINJA?</h2>

                <p>Type the falling words to destroy them!</p>

                <button id="ninjaStartBtn" class="primary-button">
                    ⚔ START GAME
                </button>

            </div>

            <div id="fallingWords"></div>

        </div>

        <div class="ninja-input-area">

            <label for="ninjaInput">
                ⌨️ TYPE THE FALLING WORD
            </label>

            <input
                type="text"
                id="ninjaInput"
                placeholder="Start the game first..."
                autocomplete="off"
                disabled
            >

            <p>Correct word = +10 points | Missed word = -1 life</p>

        </div>

        <div id="ninjaResult" class="ninja-result">

            <h2>GAME OVER!</h2>

            <p id="ninjaFinalScore"></p>

            <p id="ninjaSaveStatus" class="profile-message">
                Waiting for result...
            </p>

            <button id="ninjaRestartBtn" class="primary-button">
                ↻ PLAY AGAIN
            </button>

        </div>

    </section>

</main>

<footer>
    <p>🥷 KEYBOARD NINJA © 2026 | Type Fast. Think Faster.</p>
</footer>

<script src="../js/ninja.js?v=2"></script>

</body>
</html>
