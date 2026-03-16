<?php
header("Access-Control-Allow-Origin: https://test.richardandjames.site");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Max-Age: 86400");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

$data = trim(file_get_contents("php://input"));

if ($data !== "") {
    // keep file logging (Part 3)
    file_put_contents(
        "/var/www/collector.richardandjames.site/collector-events.jsonl",
        $data . "\n",
        FILE_APPEND | LOCK_EX
    );

    // Part 4: insert into DB
    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=analytics;charset=utf8mb4",
            "analytics_user",
            "StrongPassword123!",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $stmt = $pdo->prepare("INSERT INTO events (raw_json) VALUES (?)");
        $stmt->execute([$data]);
    } catch (Exception $e) {
        error_log("DB insert failed: " . $e->getMessage());
    }
}

http_response_code(204);
?>
