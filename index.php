
<?php
// Keyboard Ninja - Homepage
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Keyboard Ninja | Master Your Typing</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- NAVIGATION BAR -->
    <header class="navbar">
        <a href="index.php" class="logo">
            <span class="logo-icon">🥷</span>
            <span>KEYBOARD<span class="highlight">NINJA</span></span>
        </a>

        <nav>
            <a href="index.php" class="active">Home</a>
            <a href="pages/typing-test.php">Typing Test</a>
            <a href="pages/ninja-mode.php">Ninja Mode</a>
            <a href="pages/leaderboard.php">Leaderboard</a>
        </nav>

        <a href="pages/typing-test.php" class="nav-button">
            Start Playing →
        </a>
    </header>


    <!-- HERO SECTION -->
    <main>

        <section class="hero">

            <div class="hero-content">

                <div class="tag">
                    ⚡ THE ULTIMATE TYPING CHALLENGE
                </div>

                <h1>
                    TYPE FAST.<br>
                    THINK <span class="gradient-text">FASTER.</span>
                </h1>

                <p class="hero-description">
                    Ready to become a typing legend?
                    Test your speed, sharpen your accuracy
                    and unleash your inner ninja.
                </p>

                <div class="hero-buttons">
                    <a href="pages/typing-test.php" class="primary-button">
                        ⚔ Start Your Challenge
                    </a>

                    <a href="#games" class="secondary-button">
                        Explore Games ↓
                    </a>
                </div>

                <div class="hero-stats">
                    <div>
                        <h3>3</h3>
                        <p>Game Modes</p>
                    </div>

                    <div>
                        <h3>∞</h3>
                        <p>Practice Attempts</p>
                    </div>

                    <div>
                        <h3>100%</h3>
                        <p>Fun Guaranteed</p>
                    </div>
                </div>

            </div>


            <!-- HERO VISUAL -->
            <div class="hero-visual">

                <div class="glow-circle"></div>

                <div class="ninja-card">

                    <div class="ninja-emoji">🥷</div>

                    <div class="ninja-status">
                        <span class="status-dot"></span>
                        NINJA MODE READY
                    </div>

                    <h2>LEVEL UP YOUR SKILLS</h2>

                    <p>Speed is your weapon. Accuracy is your power.</p>

                    <div class="keyboard">
                        <div>Q</div><div>W</div><div>E</div><div>R</div><div>T</div>
                        <div>A</div><div>S</div><div>D</div><div>F</div><div>G</div>
                        <div>Z</div><div>X</div><div>C</div><div>V</div><div>B</div>
                    </div>

                </div>

            </div>

        </section>


        <!-- GAMES SECTION -->
        <section class="games-section" id="games">

            <div class="section-heading">
                <span class="tag">CHOOSE YOUR BATTLE</span>

                <h2>SELECT YOUR <span class="gradient-text">GAME MODE</span></h2>

                <p>Three ways to sharpen your typing skills.</p>
            </div>


            <div class="game-container">

                <!-- GAME 1 -->
                <div class="game-card purple-card">

                    <div class="game-icon">⌨️</div>

                    <span class="game-level">01 / SPEED</span>

                    <h3>Typing Speed Test</h3>

                    <p>
                        Race against time. Type the given text
                        and discover your words per minute.
                    </p>

                    <a href="pages/typing-test.php">
                        Play Now →
                    </a>

                </div>


                <!-- GAME 2 -->
                <div class="game-card cyan-card">

                    <div class="game-icon">⚔️</div>

                    <span class="game-level">02 / ARCADE</span>

                    <h3>Ninja Mode</h3>

                    <p>
                        Type quickly before the words disappear.
                        Can you survive the challenge?
                    </p>

                    <a href="pages/ninja-mode.php">
                        Play Now →
                    </a>

                </div>


                <!-- GAME 3 -->
                <div class="game-card pink-card">

                    <div class="game-icon">🏆</div>

                    <span class="game-level">03 / COMPETE</span>

                    <h3>Leaderboard</h3>

                    <p>
                        Check your records, compare scores
                        and aim for the number one position.
                    </p>

                    <a href="pages/leaderboard.php">
                        View Scores →
                    </a>

                </div>

            </div>

        </section>


        <!-- BOTTOM BANNER -->
        <section class="bottom-banner">

            <h2>YOUR KEYBOARD. YOUR RULES.</h2>

            <p>Every expert was once a beginner. Start typing!</p>

            <a href="pages/typing-test.php" class="primary-button">
                Become a Keyboard Ninja →
            </a>

        </section>

    </main>


    <!-- FOOTER -->
    <footer>
        <p>🥷 KEYBOARD NINJA © 2026 | Type Fast. Think Faster.</p>
    </footer>

</body>
</html>
