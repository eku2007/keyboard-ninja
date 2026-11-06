
<?php

session_start();

header("Content-Type: application/json");

// Check player session
if (!isset($_SESSION["player_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Player session not found. Please log in."
    ]);

    exit();
}

// Read JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid game data."
    ]);

    exit();
}

// Validate values
$score = filter_var($data["score"] ?? null, FILTER_VALIDATE_INT);
$level = filter_var($data["level"] ?? null, FILTER_VALIDATE_INT);
$duration = filter_var($data["duration"] ?? null, FILTER_VALIDATE_INT);
$errors = filter_var($data["errors"] ?? null, FILTER_VALIDATE_INT);

if (
    $score === false || $score < 0 ||
    $level === false || $level < 1 ||
    $duration === false || $duration < 0 ||
    $errors === false || $errors < 0
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid score values."
    ]);

    exit();
}

require_once __DIR__ . "/../database.php";

$conn = getDBConnection();

$playerId = (int) $_SESSION["player_id"];

// Insert Ninja Mode result
$stmt = $conn->prepare(
    "INSERT INTO game_results
    (player_id, game_type, score, errors, duration_seconds, level_reached)
    VALUES (?, 'Ninja Mode', ?, ?, ?, ?)"
);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database query preparation failed."
    ]);

    exit();
}

$stmt->bind_param(
    "iiiii",
    $playerId,
    $score,
    $errors,
    $duration,
    $level
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Ninja Mode score saved successfully!",
        "result_id" => $stmt->insert_id
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Could not save Ninja Mode score."
    ]);

}

$stmt->close();
$conn->close();

?>
