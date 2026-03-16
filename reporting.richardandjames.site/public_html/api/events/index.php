<?php
// reporting.richardandjames.site/api/events
header("Content-Type: application/json; charset=utf-8");

// (Optional) CORS - safe for debugging / future frontend
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

// ---- DB connection ----
$DB_HOST = "localhost";
$DB_NAME = "analytics";
$DB_USER = "analytics_user";
$DB_PASS = "StrongPassword123!";

try {
  $pdo = new PDO(
    "mysql:host=localhost;dbname=analytics;charset=utf8mb4",
    "analytics_user",
    "StrongPassword123!",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );

  $stmt = $pdo->query("SELECT id, received_at, raw_json FROM events ORDER BY id DESC LIMIT 50");
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(["error" => "DB query failed", "details" => $e->getMessage()]);
}

// ---- Parse /api/events/{id} ----
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", trim($path, "/"));

// Find "events" segment and read optional id after it
$eventsIndex = array_search("events", $parts);
$id = null;
if ($eventsIndex !== false && isset($parts[$eventsIndex + 1]) && $parts[$eventsIndex + 1] !== "") {
    if (ctype_digit($parts[$eventsIndex + 1])) {
        $id = intval($parts[$eventsIndex + 1]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Invalid id"]);
        exit;
    }
}

$method = $_SERVER["REQUEST_METHOD"];

// ---- Routes ----
try {
    if ($method === "GET") {
        if ($id === null) {
            // Return latest rows (limit to keep response reasonable)
            $stmt = $pdo->query("SELECT id, received_at, raw_json FROM events ORDER BY id DESC LIMIT 200");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows, JSON_PRETTY_PRINT);
        } else {
            $stmt = $pdo->prepare("SELECT id, received_at, raw_json FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                http_response_code(404);
                echo json_encode(["error" => "Not found"]);
            } else {
                echo json_encode($row, JSON_PRETTY_PRINT);
            }
        }
        exit;
    }

    if ($method === "POST") {
        // Add a new event (body is stored in raw_json)
        $body = trim(file_get_contents("php://input"));
        if ($body === "") {
            http_response_code(400);
            echo json_encode(["error" => "Empty body"]);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO events (raw_json) VALUES (?)");
        $stmt->execute([$body]);
        http_response_code(201);
        echo json_encode(["message" => "created", "id" => $pdo->lastInsertId()]);
        exit;
    }

    if ($method === "PUT") {
        if ($id === null) {
            http_response_code(400);
            echo json_encode(["error" => "PUT requires /api/events/{id}"]);
            exit;
        }
        $body = trim(file_get_contents("php://input"));
        if ($body === "") {
            http_response_code(400);
            echo json_encode(["error" => "Empty body"]);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE events SET raw_json = ? WHERE id = ?");
        $stmt->execute([$body, $id]);
        echo json_encode(["message" => "updated", "id" => $id]);
        exit;
    }

    if ($method === "DELETE") {
        if ($id === null) {
            http_response_code(400);
            echo json_encode(["error" => "DELETE requires /api/events/{id}"]);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["message" => "deleted", "id" => $id]);
        exit;
    }

    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error"]);
}
