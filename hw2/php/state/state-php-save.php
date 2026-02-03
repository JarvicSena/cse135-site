<?php
$name     = isset($_POST['name']) ? trim($_POST['name']) : "";
$language = isset($_POST['language']) ? trim($_POST['language']) : "";
$message  = isset($_POST['message']) ? trim($_POST['message']) : "";

$expiry = time() + 3600;
setcookie("state_name", $name, $expiry, "/");
setcookie("state_language", $language, $expiry, "/");
setcookie("state_message", $message, $expiry, "/");

header("Location: state-php-view.php");
exit;
