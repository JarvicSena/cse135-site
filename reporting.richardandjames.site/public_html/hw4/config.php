<?php
	//define("HW4_USER", "grader");
	//define("HW4_PASS", "Cse135Winter");

	define("APP_NAME", "CSE135 HW4");
        define("SQLITE_DB", "/var/www/reporting.richardandjames.site/public_html/hw4/events.sqlite");
        define("EVENTS_JSONL", "/var/www/collector.richardandjames.site/collector-events.jsonl");
	define("USERS_FILE", "/var/www/reporting.richardandjames.site/data/users.json");

	$USERS = [
  		"superadmin" => [
    			"password" => "Cse135Winter",
    			"role" => "superadmin"
  		],
  		"analyst" => [
    			"password" => "Analyst123",
    			"role" => "analyst"
  		],
  		"viewer" => [
    			"password" => "Viewer123",
    			"role" => "viewer"
  		]
	];
?>
