<?php

echo <<<END
<!DOCTYPE html>
<html>
<head>
<title>General Request Echo</title>
</head>
<body>
<h1 align="center">General Request Echo</h1>
<hr>
END;

echo "<p><b>HTTP Protocol: </b>" . $_SERVER['SERVER_PROTOCOL'] . "</p>";
echo "<p><b>HTTP Method: </b>" . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><b>Query String: </b>" . $_SERVER['QUERY_STRING'] . "</p>";
echo "<p><b>Time: </b>" . date("Y-m-d H:i:s") . "</p>";
echo "<p><b>Hostname: </b>" . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p><b>User-Agent Header: </b>" . $_SERVER['HTTP_USER_AGENT'] . "</p>";
echo "<p><b>IP Address: </b>" . $_SERVER['REMOTE_ADDR'] . "</p>";

$form_data = file_get_contents("php://input");

echo "<p><b>Message Body:</b> " . htmlspecialchars($form_data) . "</p>";

echo "</body>";
echo "</html>\n";
