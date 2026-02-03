<?php
$arr = array_keys($_SERVER);
sort($arr);

echo <<<END
<!DOCTYPE html>
<html>
<head>
<title>Environment Variables</title>
</head>
<body>
<h1 align="center">Environment Variables</h1>
<hr>
END;

foreach ($arr as $element) {
	echo "<b>$element:</b>" .  $_SERVER[$element] . "<br /> \n";
}

echo "</body>";
echo "</html>";
