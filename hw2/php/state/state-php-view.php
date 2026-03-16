<?php
$name     = isset($_COOKIE['state_name']) ? $_COOKIE['state_name'] : "";
$language = isset($_COOKIE['state_language']) ? $_COOKIE['state_language'] : "";
$message  = isset($_COOKIE['state_message']) ? $_COOKIE['state_message'] : "";

function h($s) {
  return htmlspecialchars($s ?? "", ENT_QUOTES, "UTF-8");
}

echo <<<END
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>State Demo - View (Cookies)</title>
</head>
<body>
  <h1 align="center">State Demo (Cookies) - View Saved Data</h1>
  <hr>
END;

if ($name === "" && $language === "" && $message === "") {
  echo "<p><i>No saved state found.</i></p>";
} else {
  echo "<p><b>Name:</b> " . h($name) . "</p>";
  echo "<p><b>Favorite Language:</b> " . h($language) . "</p>";
  echo "<p><b>Message:</b> " . h($message) . "</p>";
}

echo <<<END
  <hr>

  <form method="POST" action="state-php-clear.php">
    <button type="submit">Clear Saved State</button>
  </form>

  <p><a href="state-php-form.php">Back to Form</a></p>
</body>
</html>
END;
