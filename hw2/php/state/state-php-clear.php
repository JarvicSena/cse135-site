<?php
setcookie("state_name", "", time() - 3600, "/");
setcookie("state_language", "", time() - 3600, "/");
setcookie("state_message", "", time() - 3600, "/");

header("Location: state-php-view.php");
exit;

