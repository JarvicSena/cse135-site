<?php
echo <<<END
<!DOCTYPE html>
<html>
<head>
<title>GET Request Echo</title>
</head>
<body><h1 align="center">Get Request Echo</h1>
<hr>
END;

echo "<p><b>Query String: </b>" . $_SERVER['QUERY_STRING'] . "</p>\n";

if (!empty($_GET)){
	foreach ($_GET as $key => $value){
		echo "<p>$key = " . htmlspecialchars($value) . "</p>";
	}
} else {
	echo "<p>No GET parameters received.</p>";
}

echo "</body>";
echo "</html>";
