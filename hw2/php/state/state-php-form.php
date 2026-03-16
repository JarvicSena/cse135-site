<?php
echo <<<END
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>State Demo - Form (Cookies)</title>
</head>
<body>
  <h1 align="center">State Demo (Cookies) - Enter Data</h1>
  <hr>

  <form method="POST" action="state-php-save.php">
    <p>
      <label><b>Name:</b></label><br>
      <input type="text" name="name" required>
    </p>

    <p>
      <label><b>Favorite Language:</b></label><br>
      <input type="text" name="language" required>
    </p>

    <p>
      <label><b>Message:</b></label><br>
      <textarea name="message" rows="4" cols="40" required></textarea>
    </p>

    <button type="submit">Save State</button>
  </form>

  <p><a href="state-php-view.php">Go to View Page</a></p>
</body>
</html>
END;
