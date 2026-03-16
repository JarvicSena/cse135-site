<?php
$name = "Richard and James";
$language = "PHP";
$time = date("Y-m-d H:i:s");
$ip = $_SERVER['REMOTE_ADDR'];

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset= 'UTF-8'>";
echo "<title> Hello PHP World</title>";
echo "</head>";
echo "<body>";

echo "<h1> Hello from $name</h1>";
echo "<p> This page was generated with PHP </p>";
echo "<p> This program was generated at: $time</p>";
echo "<p> Your current IP Address: $ip</p>";

echo "</body>";
echo "</html>";
