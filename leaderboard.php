
<?php
require_once __DIR__ . "/../database.php";

$conn = getDBConnection();

// Get the highest result per player for each game.
// One player can appear once in each game category.
$sql = "
SELECT
    p.nickname,
    g.game_type,
    g.score,
    g.wpm,
    g.accuracy,
    g.level_reached,
    g.played_at
FROM game_results g
INNER JOIN players p ON g.player_id = p.id
WHERE g.id = (
    SELECT g2.id
    FROM game_results g2
    WHERE g2.player_id = g.player_id
      AND g2.game_type = g.game_type
    ORDER BY g2.score DESC, g2.id ASC
    LIMIT 1
)
ORDER BY g.game_type, g.score DESC, g.played_at ASC
";

$result = $conn->query($sql);

if (!$result) {
    die("Unable to load leaderboard.");
}

$typingPlayers = [];
$ninjaPlayers = [];

while ($row = $result->fetch_assoc()) {
    if ($row["game_type"] === "Typing Test") {
        $typingPlayers[] = $row;
    } elseif ($row["game_type"] === "Ninja Mode") {
        $ninjaPlayers[] = $row;
    }
}

$conn->close();

function renderLeaderboard($players, $gameType) {

    if (empty($players)) {
        echo '<p class="empty-board">No scores yet. Be the first ninja!</p>';
        return;
    }

    echo '<div class="leaderboard-table-wrap">';
    echo '<table class="leaderboard-table">';

    echo '<thead><tr>';
    echo '<th>RANK</th>';
    echo '<th>PLAYER</th>';
    echo '<th>SCORE</th>';

    if ($gameType === "Typing Test") {
        echo '<th>WPM</th><th>ACCURACY</th>';
    } else {
        echo '<th>LEVEL</th>';
    }

    echo '</tr></thead><tbody>';

    foreach ($players as $index => $player) {

        $rank = $index + 1;

        $medal = match ($rank) {
            1 => "🥇",
            2 => "🥈",
            3 => "🥉",
            default => "#" . $rank
        };

        $nickname = htmlspecialchars(
            $player["nickname"],
            ENT_QUOTES,
            "UTF-8"
        );

        echo '<tr>';

        echo '<td class="rank-cell">' . $medal . '</td>';

        echo '<td class="player-cell">🥷 ' . $nickname . '</td>';

        echo '<td class="score-cell">' .
            (int)$player["score"] .
            '</td>';

        if ($gameType === "Typing Test") {

            echo '<td>' .
                number_format((float)$player["wpm"], 0) .
                '</td>';

            echo '<td>' .
                number_format((float)$player["accuracy"], 1) .
                '%</td>';

        } else {

            echo '<td>' .
                (int)$player["level_reached"] .
                '</td>';
        }

        echo '</tr>';
    }

    echo '</tbody></table></div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Leaderboard | Keyboard Ninja</title>

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
        <a href="leaderboard.php" class="active">Leaderboard</a>
    </nav>

    <a href="../index.php" class="nav-button">
        ← Back Home
    </a>

</header>

<main class="leaderboard-page">

    <div class="leaderboard-heading">

        <span class="tag">🏆 HALL OF FAME</span>

        <h1>THE <span class="gradient-text">LEADERBOARD</span></h1>

        <p>Compete, improve, and climb to the top!</p>

    </div>

    <section class="leaderboard-section">

        <div class="board-title">
            <h2>⌨️ TYPING TEST CHAMPIONS</h2>
            <p>Ranked by highest typing score</p>
        </div>

        <?php renderLeaderboard($typingPlayers, "Typing Test"); ?>

    </section>

    <section class="leaderboard-section">

        <div class="board-title">
            <h2>⚔️ NINJA MODE CHAMPIONS</h2>
            <p>Ranked by highest arcade score</p>
        </div>

        <?php renderLeaderboard($ninjaPlayers, "Ninja Mode"); ?>

    </section>

</main>

<footer>
    <p>🥷 KEYBOARD NINJA © 2026 | Type Fast. Think Faster.</p>
</footer>

</body>
</html>
