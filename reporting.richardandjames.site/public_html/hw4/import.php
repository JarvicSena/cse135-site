<?php
	require_once __DIR__ . "/auth.php";
	require_role(["superadmin", "analyst"]);

	$db = new PDO("sqlite:" . SQLITE_DB);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$db->exec("
	CREATE TABLE IF NOT EXISTS events (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		ts TEXT,
		sessionId TEXT,
		type TEXT,
		page TEXT,
		payload TEXT
	);
");

$lines = file(EVENTS_JSONL, FILE_IGNORE_NEW_LINES);

$insert = $db->prepare("
INSERT INTO events(ts, sessionId, type, page, payload)
VALUES(:ts, :sid, :type, :page, :payload)
");

$count = 0;

foreach ($lines as $line) {

	$obj = json_decode($line, true);
	if (!$obj) continue;

	if (($obj["type"] ?? "") === "batch" && isset($obj["events"])) {

 		foreach ($obj["events"] as $event) {

		$insert->execute([
			":ts" => $event["ts"] ?? null,
			":sid" => $obj["sessionId"] ?? null,
			":type" => $event["type"] ?? "event",
			":page" => $obj["page"] ?? null,
			":payload" => json_encode($event)
		]);

		$count++;
		}

	 } else {

	$insert->execute([
		":ts" => $obj["ts"] ?? null,
		":sid" => $obj["sessionId"] ?? null,
		":type" => $obj["type"] ?? "unknown",
		":page" => $obj["page"] ?? null,
		":payload" => json_encode($obj)
	]);

	$count++;
	}
}

echo "Imported {$count} events.";
