
<?php

session_start();

require_once __DIR__ . "/../database.php";

$conn = getDBConnection();

$message = "";


// REGISTER OR RECOGNIZE PLAYER

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nickname = trim($_POST["nickname"] ?? "");

    if ($nickname === "") {

        $message = "Please enter a nickname.";

    } elseif (strlen($nickname) > 50) {

        $message = "Nickname must be under 50 characters.";

    } else {

        // Check whether nickname already exists

        $stmt = $conn->prepare(
            "SELECT id, nickname FROM players WHERE nickname = ?"
        );

        $stmt->bind_param("s", $nickname);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            // Existing player

            $player = $result->fetch_assoc();

            $_SESSION["player_id"] = $player["id"];
            $_SESSION["nickname"] = $player["nickname"];

            $stmt->close();

            header("Location: profile.php");
            exit();

        } else {

            $stmt->close();

            // Create new player

            $stmt = $conn->prepare(
                "INSERT INTO players (nickname) VALUES (?)"
            );

            $stmt->bind_param("s", $nickname);

            if ($stmt->execute()) {

                $_SESSION["player_id"] = $conn->insert_id;
                $_SESSION["nickname"] = $nickname;

                $stmt->close();

                header("Location: profile.php");
                exit();

            } else {

                $message = "Unable to create profile. Please try again.";

            }

            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Player Profile | Keyboard Ninja</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<!-- NAVIGATION -->

<header class="navbar">

    <a href="../index.php" class="logo">
        🥷 KEYBOARD<span class="highlight">NINJA</span>
    </a>

    <nav>
        <a href="../index.php">Home</a>
        <a href="typing-test.php">Typing Test</a>
        <a href="ninja-mode.php">Ninja Mode</a>
        <a href="leaderboard.php">Leaderboard</a>
    </nav>

    <a href="../index.php" class="nav-button">
        ← Home
    </a>

</header>


<!-- PROFILE SECTION -->

<main class="profile-section">

    <section class="profile-card">

        <div class="profile-icon">🥷</div>

        <?php if (isset($_SESSION["player_id"])): ?>

            <h2>WELCOME BACK!</h2>

            <p>
                Your ninja identity is ready.
            </p>

            <h2 class="gradient-text">
                <?php echo htmlspecialchars($_SESSION["nickname"]); ?>
            </h2>

            <p>
                Player ID:
                <?php echo (int) $_SESSION["player_id"]; ?>
            </p>

            <a href="typing-test.php" class="primary-button">
                START PLAYING →
            </a>

            <p style="margin-top:25px;">
                <a href="logout.php" style="color:#fb7185;">
                    Logout
                </a>
            </p>

        <?php else: ?>

            <h2>CREATE YOUR PROFILE</h2>

            <p>
                Choose your ninja identity and enter the arena!
            </p>

            <form method="POST" action="profile.php">

                <label for="nickname">
                    YOUR NINJA NICKNAME
                </label>

                <input
                    type="text"
                    id="nickname"
                    name="nickname"
                    maxlength="50"
                    placeholder="e.g. ShadowNinja"
                    required
                >

                <button type="submit" class="primary-button">
                    CREATE MY PROFILE →
                </button>

            </form>

            <?php if ($message !== ""): ?>

                <div class="profile-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>

        <?php endif; ?>

    </section>

</main>


<footer>
    <p>🥷 KEYBOARD NINJA © 2026 | Type Fast. Think Faster.</p>
</footer>

</body>
</html>
