<?php
require_once __DIR__ . "/auth.php";
require_role(["superadmin", "analyst"]);

$db = new PDO("sqlite:" . SQLITE_DB);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$rows = $db->query("
  SELECT ts, type, sessionId, page, payload
  FROM events
  ORDER BY id DESC
  LIMIT 200
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP_NAME; ?> - Table</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      background: #0f172a;
      color: #e5e7eb;
    }

    .container {
      max-width: 1300px;
      margin: 0 auto;
      padding: 32px 20px 48px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 28px;
    }

    .title-wrap h1 {
      margin: 0 0 6px;
      font-size: 2.2rem;
      color: #f8fafc;
    }

    .title-wrap p {
      margin: 0;
      color: #94a3b8;
      font-size: 1rem;
    }

    .nav {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .nav a {
      text-decoration: none;
      color: #e5e7eb;
      background: #1e293b;
      padding: 10px 14px;
      border-radius: 10px;
      border: 1px solid #334155;
      transition: 0.2s ease;
      font-weight: 600;
    }

    .nav a:hover {
      background: #334155;
    }

    .card {
      background: #111827;
      border: 1px solid #1f2937;
      border-radius: 18px;
      padding: 22px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 1100px;
    }

    th, td {
      text-align: left;
      padding: 12px 14px;
      border-bottom: 1px solid #1f2937;
      vertical-align: top;
    }

    th {
      background: #1e293b;
      color: #f8fafc;
      font-size: 0.95rem;
    }

    td {
      color: #dbe4ee;
      font-size: 0.93rem;
    }

    tr:hover td {
      background: rgba(51, 65, 85, 0.22);
    }

    .payload {
      white-space: pre-wrap;
      word-break: break-word;
      max-width: 420px;
      margin: 0;
      color: #cbd5e1;
      font-size: 0.88rem;
    }

    .footer-note {
      margin-top: 22px;
      color: #94a3b8;
      font-size: 0.92rem;
      text-align: center;
    }

    @media (max-width: 700px) {
      .container {
        padding: 24px 14px 36px;
      }

      .title-wrap h1 {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title-wrap">
        <h1>Collected Events Table</h1>
        <p>Raw event records imported from the collector datastore into SQLite.</p>
      </div>

      <div class="nav">
        <a href="index.php">Home</a>
        <a href="import.php">Import Data</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Timestamp</th>
            <th>Type</th>
            <th>Session ID</th>
            <th>Page</th>
            <th>Payload</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r["ts"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($r["type"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($r["sessionId"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($r["page"] ?? ""); ?></td>
              <td><pre class="payload"><?php echo htmlspecialchars($r["payload"] ?? ""); ?></pre></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="footer-note">
      Built with PHP and SQLite for HW4 Step 2.
    </div>
  </div>
</body>
</html>
