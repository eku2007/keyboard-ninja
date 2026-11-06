
<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../database.php";

// Check player session
if (!isset($_SESSION["player_id"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Please create or select a player profile first."
    ]);
    exit();
}

// Read submitted JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request data."
    ]);
    exit();
}

// Validate values

$wpm = filter_var($data["wpm"] ?? null, FILTER_VALIDATE_FLOAT);
$accuracy = filter_var($data["accuracy"] ?? null, FILTER_VALIDATE_FLOAT);
$errors = filter_var($data["errors"] ?? null, FILTER_VALIDATE_INT);
$duration = filter_var($data["duration"] ?? null, FILTER_VALIDATE_INT);

if (
    $wpm === false || $wpm < 0 ||
    $accuracy === false || $accuracy < 0 || $accuracy > 100 ||
    $errors === false || $errors < 0 ||
    $duration === false || !in_array($duration, [30, 60, 120], true)
) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalid score values."
    ]);
    exit();
}


$conn = getDBConnection();

$playerId = (int) $_SESSION["player_id"];

// Save result
$stmt = $conn->prepare(
    "INSERT INTO game_results
    (player_id, game_type, score, wpm, accuracy, errors, duration_seconds, level_reached)
    VALUES (?, 'Typing Test', ?, ?, ?, ?, ?, 1)"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Could not prepare database query."
    ]);
    exit();
}

$stmt->bind_param(
    "iiddii",
    $playerId,
    $wpm,
    $wpm,
    $accuracy,
    $errors,
    $duration
);
if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Your typing score has been saved!",
        "result_id" => $stmt->insert_id
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to save your score."
    ]);

}

$stmt->close();
$conn->close();

?>
