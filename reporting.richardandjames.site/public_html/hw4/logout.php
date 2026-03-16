<?php
	require_once __DIR__ . "/auth.php";
	$_SESSION = [];
	session_destroy();
	header("Location: /hw4/login.php");
	exit;
?>
