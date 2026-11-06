
<?php

session_start();

if (!isset($_SESSION["player_id"])) {
    header("Location: profile.php");
    exit();
}

require_once __DIR__ . "/../database.php";

$conn = getDBConnection();

$playerId = (int) $_SESSION["player_id"];

// Get player information
$stmt = $conn->prepare(
    "SELECT nickname FROM players WHERE id = ?"
);

$stmt->bind_param("i", $playerId);
$stmt->execute();

$player = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$player) {
    session_destroy();
    header("Location: profile.php");
    exit();
}

$nickname = $player["nickname"];

// Get dashboard statistics
$stmt = $conn->prepare("
    SELECT
        COUNT(*) AS total_games,

        COALESCE(
            MAX(CASE
                WHEN game_type = 'Typing Test' THEN wpm
            END), 0
        ) AS best_wpm,

        COALESCE(
            MAX(CASE
                WHEN game_type = 'Typing Test' THEN accuracy
            END), 0
        ) AS best_accuracy,

        COALESCE(
            MAX(CASE
                WHEN game_type = 'Ninja Mode' THEN score
            END), 0
        ) AS ninja_high_score

    FROM game_results
    WHERE player_id = ?
");

$stmt->bind_param("i", $playerId);
$stmt->execute();

$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get recent game history
$stmt = $conn->prepare("
    SELECT
        game_type,
        score,
        wpm,
        accuracy,
        level_reached,
        played_at

    FROM game_results

    WHERE player_id = ?

    ORDER BY played_at DESC

    LIMIT 5
");

$stmt->bind_param("i", $playerId);
$stmt->execute();

$recentGames = $stmt->get_result();

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Dashboard | Keyboard Ninja</title>

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
        <a href="ninja-mode.php">Ninja Mode</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="dashboard.php" class="active">Dashboard</a>
    </nav>

    <a href="../index.php" class="nav-button">
        ← Home
    </a>

</header>


<main class="dashboard-page">

    <section class="dashboard-heading">

        <span class="tag">🥷 YOUR PERSONAL SPACE</span>

        <h1>
            WELCOME BACK,
            <span class="gradient-text">
                <?php echo htmlspecialchars($nickname); ?>
            </span>
        </h1>

        <p>
            Track your progress, improve your skills
            and become the ultimate Keyboard Ninja.
        </p>

    </section>


    <!-- STATISTICS -->

    <section class="dashboard-stats">

        <div class="dashboard-card">

            <span>⌨️ BEST WPM</span>

            <h2>
                <?php echo round($stats["best_wpm"]); ?>
            </h2>

            <p>Your highest typing speed</p>

        </div>


        <div class="dashboard-card">

            <span>🎯 BEST ACCURACY</span>

            <h2>
                <?php
                echo number_format(
                    (float)$stats["best_accuracy"], 1
                ) . "%";
                ?>
            </h2>

            <p>Your best typing accuracy</p>

        </div>


        <div class="dashboard-card">

            <span>⚔️ NINJA HIGH SCORE</span>

            <h2>
                <?php echo (int)$stats["ninja_high_score"]; ?>
            </h2>

            <p>Your highest arcade score</p>

        </div>


        <div class="dashboard-card">

            <span>🎮 TOTAL GAMES</span>

            <h2>
                <?php echo (int)$stats["total_games"]; ?>
            </h2>

            <p>All your completed games</p>

        </div>

    </section>


    <!-- GAME SHORTCUTS -->

    <section class="dashboard-actions">

        <h2>READY FOR ANOTHER CHALLENGE?</h2>

        <div class="dashboard-buttons">

            <a href="typing-test.php" class="primary-button">
                ⌨️ PLAY TYPING TEST
            </a>

            <a href="ninja-mode.php" class="primary-button">
                ⚔️ PLAY NINJA MODE
            </a>

            <a href="leaderboard.php" class="nav-button">
                🏆 VIEW LEADERBOARD
            </a>

        </div>

    </section>


    <!-- RECENT ACTIVITY -->

    <section class="dashboard-history">

        <h2>📊 RECENT GAME HISTORY</h2>

        <div class="dashboard-table-wrap">

            <table class="dashboard-table">

                <thead>

                    <tr>
                        <th>GAME</th>
                        <th>SCORE</th>
                        <th>WPM</th>
                        <th>ACCURACY</th>
                        <th>LEVEL</th>
                        <th>DATE</th>
                    </tr>

                </thead>

                <tbody>

                <?php if ($recentGames->num_rows > 0): ?>

                    <?php while ($game = $recentGames->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo $game["game_type"] === "Ninja Mode"
                                    ? "⚔️ Ninja Mode"
                                    : "⌨️ Typing Test";
                                ?>
                            </td>

                            <td>
                                <?php echo (int)$game["score"]; ?>
                            </td>

                            <td>
                                <?php
                                echo $game["wpm"] !== null
                                    ? round((float)$game["wpm"])
                                    : "-";
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $game["accuracy"] !== null
                                    ? number_format((float)$game["accuracy"], 1) . "%"
                                    : "-";
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $game["level_reached"] !== null
                                    ? (int)$game["level_reached"]
                                    : "-";
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime($game["played_at"])
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6">
                            No games played yet. Start your first challenge!
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<footer>
    <p>🥷 KEYBOARD NINJA © 2026 | Type Fast. Think Faster.</p>
</footer>

</body>
</html>
