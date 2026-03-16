<?php
$response = [
 "greeting" => "Hello from Richard and James",
 "language" => "This program was generated with PHP",
 "date" =>"This program was generated at" . date("Y-m-d H:i:s"),
 "ip" => "Your current IP address is:" . $_SERVER['REMOTE_ADDR']
];

header("Content-Type: application/json");
echo json_encode($response);
?>
