<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/users-lib.php";
require_role(["superadmin"]);

$users = get_users();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "add") {
        $username = trim($_POST["username"] ?? "");
        $password = $_POST["password"] ?? "";
        $role = $_POST["role"] ?? "viewer";

        if ($username !== "" && $password !== "" && !isset($users[$username])) {
            $users[$username] = [
                "password_hash" => password_hash($password, PASSWORD_DEFAULT),
                "role" => $role
            ];
            save_users($users);
            $message = "User added.";
            $users = get_users();
        }
    }

    if ($action === "update") {
        $username = $_POST["username"] ?? "";
        $role = $_POST["role"] ?? "viewer";
        $newPassword = $_POST["new_password"] ?? "";

        if (isset($users[$username])) {
            $users[$username]["role"] = $role;

            if ($newPassword !== "") {
                $users[$username]["password_hash"] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            save_users($users);
            $message = "User updated.";
            $users = get_users();
        }
    }

    if ($action === "delete") {
        $username = $_POST["username"] ?? "";

        if ($username !== "superadmin" && isset($users[$username])) {
            unset($users[$username]);
            save_users($users);
            $message = "User deleted.";
            $users = get_users();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
  <style>
    body { margin:0; font-family:Arial,Helvetica,sans-serif; background:#0f172a; color:#e5e7eb; }
    .container { max-width:1100px; margin:0 auto; padding:32px 20px 48px; }
    .nav a { text-decoration:none; color:#e5e7eb; background:#1e293b; padding:10px 14px; border-radius:10px; border:1px solid #334155; font-weight:600; margin-right:8px; }
    .card { background:#111827; border:1px solid #1f2937; border-radius:18px; padding:22px; margin-bottom:24px; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:12px 14px; border-bottom:1px solid #1f2937; text-align:left; }
    th { background:#1e293b; }
    input, select { padding:8px 10px; border-radius:8px; border:1px solid #334155; background:#0f172a; color:#e5e7eb; }
    button { padding:8px 12px; border:none; border-radius:8px; background:#2563eb; color:#fff; cursor:pointer; }
    .danger { background:#dc2626; }
  </style>
</head>
<body>
  <div class="container">
    <p class="nav">
      <a href="index.php">Home</a>
      <a href="logout.php">Logout</a>
    </p>

    <div class="card">
      <h1>User Management</h1>
      <?php if ($message): ?><p><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
    </div>

    <div class="card">
      <h2>Add User</h2>
      <form method="POST">
        <input type="hidden" name="action" value="add">
        <input name="username" placeholder="username" required>
        <input name="password" type="password" placeholder="password" required>
        <select name="role">
          <option value="viewer">viewer</option>
          <option value="analyst">analyst</option>
          <option value="superadmin">superadmin</option>
        </select>
        <button type="submit">Add</button>
      </form>
    </div>

    <div class="card">
      <h2>Existing Users</h2>
      <table>
        <tr>
          <th>Username</th>
          <th>Role</th>
          <th>New Password</th>
          <th>Update</th>
          <th>Delete</th>
        </tr>
        <?php foreach ($users as $username => $info): ?>
          <tr>
            <form method="POST">
              <td>
                <?php echo htmlspecialchars($username); ?>
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
              </td>
              <td>
                <select name="role">
                  <option value="viewer" <?php if ($info["role"] === "viewer") echo "selected"; ?>>viewer</option>
                  <option value="analyst" <?php if ($info["role"] === "analyst") echo "selected"; ?>>analyst</option>
                  <option value="superadmin" <?php if ($info["role"] === "superadmin") echo "selected"; ?>>superadmin</option>
                </select>
              </td>
              <td>
                <input name="new_password" type="password" placeholder="leave blank">
              </td>
              <td>
                <button type="submit" name="action" value="update">Save</button>
              </td>
              <td>
                <?php if ($username !== "superadmin"): ?>
                  <button class="danger" type="submit" name="action" value="delete">Delete</button>
                <?php endif; ?>
              </td>
            </form>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</body>
</html>
