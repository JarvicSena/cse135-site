<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/users-lib.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $u = $_POST["username"] ?? "";
  $p = $_POST["password"] ?? "";

  $users = get_users();

  if (isset($users[$u]) && password_verify($p, $users[$u]["password_hash"])) {
      session_regenerate_id(true);
      $_SESSION["user"] = $u;
      $_SESSION["role"] = $users[$u]["role"];
      header("Location: /hw4/index.php");
      exit;
  }

  $error = "Invalid login.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP_NAME; ?> - Login</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: Arial, Helvetica, sans-serif;
      background: #0f172a;
      color: #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .card {
      width: 100%;
      max-width: 460px;
      background: #111827;
      border: 1px solid #1f2937;
      border-radius: 18px;
      padding: 30px 28px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }

    h1 {
      margin: 0 0 8px;
      font-size: 2rem;
      color: #f8fafc;
    }

    .subtitle {
      margin: 0 0 24px;
      color: #94a3b8;
      font-size: 0.98rem;
    }

    .error {
      margin-bottom: 16px;
      padding: 12px 14px;
      border-radius: 10px;
      background: rgba(239, 68, 68, 0.12);
      border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fecaca;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #e5e7eb;
    }

    input {
      width: 100%;
      padding: 12px 14px;
      margin-bottom: 18px;
      border-radius: 10px;
      border: 1px solid #334155;
      background: #0f172a;
      color: #e5e7eb;
      font-size: 0.98rem;
    }

    input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.18);
    }

    button {
      width: 100%;
      padding: 12px 14px;
      border: none;
      border-radius: 10px;
      background: #3b82f6;
      color: white;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      transition: 0.2s ease;
    }

    button:hover {
      background: #2563eb;
    }
  </style>
</head>
<body>
  <div class="card">
    <h1>Analytics Login</h1>
    <p class="subtitle">Sign in to access the protected reporting dashboard.</p>

    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="username">Username</label>
      <input id="username" name="username" required>

      <label for="password">Password</label>
      <input id="password" type="password" name="password" required>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
