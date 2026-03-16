<?php
	require_once __DIR__ . "/config.php";
	if (session_status() !== PHP_SESSION_ACTIVE) session_start();

	function is_logged_in(): bool {
  		return isset($_SESSION["user"]);
	}

	function require_login(): void {
  		if (!is_logged_in()) {
    			header("Location: /hw4/login.php");
    			exit;
  		}
	}

	function current_role(): ?string {
  		return $_SESSION["role"] ?? null;
	}

	function require_role(array $allowedRoles): void {
  	require_login();

  	if (!in_array(current_role(), $allowedRoles, true)) {
    		http_response_code(403);
    		echo "<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p>";
    		exit;
  	}
}
?>
