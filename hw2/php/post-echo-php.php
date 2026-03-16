<?php
echo <<<END
<!DOCTYPE html>
<html>
<head>
<title>Post Request Echo</title>
</head>
<body><h1 align="center">Post Request Echo</h1>
<hr>
END;

$form_data = file_get_contents("php://input");
echo "<p><b>Message Body:</b> " . htmlspecialchars($form_data) . "</p>";

if (!empty($_POST)){
        foreach ($_POST as $key => $value){
                echo "<p>$key = " . htmlspecialchars($value) . "</p>";
        }
} else {
        echo "<p>No POST parameters received.</p>";
}

echo "</body>";
echo "</html>";
