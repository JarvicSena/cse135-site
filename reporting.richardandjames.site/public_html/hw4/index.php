<?php
require_once __DIR__ . "/auth.php";
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP_NAME; ?> - Home</title>
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
      max-width: 1200px;
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

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
    }

    .card {
      background: #111827;
      border: 1px solid #1f2937;
      border-radius: 18px;
      padding: 22px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }

    .card h2 {
      margin: 0 0 8px;
      color: #f8fafc;
    }

    .card p {
      margin: 0 0 16px;
      color: #94a3b8;
      line-height: 1.5;
    }

    .card a {
      display: inline-block;
      text-decoration: none;
      color: #e5e7eb;
      background: #2563eb;
      padding: 10px 14px;
      border-radius: 10px;
      font-weight: 600;
    }

    .card a:hover {
      background: #1d4ed8;
    }

    .footer-note {
      margin-top: 24px;
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
        <h1>Analytics Dashboard</h1>
        <p>Protected reporting area for the analytics platform.</p>
      </div>

      <div class="nav">
        <a href="index.php">Home</a>

        <?php if (in_array(current_role(), ["superadmin","analyst"])): ?>
          <a href="reports/behavioral.php">Behavioral</a>
          <a href="reports/performance.php">Performance</a>
          <a href="reports/sessions.php">Sessions</a>
        <?php endif; ?>

        <?php if (current_role() === "superadmin"): ?>
          <a href="admin-users.php">Manage Users</a>
        <?php endif; ?>

        <a href="logout.php">Logout</a>
      </div>
    </div>

    <p style="margin-bottom:20px;">
      Logged in as
      <strong><?php echo htmlspecialchars($_SESSION["user"]); ?></strong>
      (<?php echo htmlspecialchars($_SESSION["role"]); ?>)
    </p>

    <div class="grid">
      <div class="card">
        <h2>Protected Dashboard</h2>
        <p>
          This page is the protected home for the analytics platform.
          Access is controlled by the authentication system, and available sections depend on the logged-in user's role.
        </p>
      </div>

      <?php if (in_array(current_role(), ["superadmin", "analyst"])): ?>
        <div class="card">
          <h2>Import Data</h2>
          <p>
            Load collected analytics events from the collector datastore into the SQLite reporting database.
          </p>
          <a href="import.php">Run Import</a>
        </div>

        <div class="card">
          <h2>View Data Table</h2>
          <p>
            Browse collected event records stored in the reporting database using a raw HTML table.
          </p>
          <a href="table.php">Open Table</a>
        </div>

        <div class="card">
          <h2>Charts</h2>
          <p>
            Visual analytics dashboard powered by Chart.js.
          </p>
          <a href="chart.php">Open Charts</a>
        </div>
      <?php endif; ?>

      <?php if (current_role() === "viewer"): ?>
        <div class="card">
          <h2>Behavioral Report</h2>
          <p>
            View the saved behavioral report.
          </p>
          <a href="saved-reports/behavioral.php">Open Report</a>
        </div>

        <div class="card">
          <h2>Performance Report</h2>
          <p>
            View the saved performance report.
          </p>
          <a href="saved-reports/performance.php">Open Report</a>
        </div>

        <div class="card">
          <h2>Session Report</h2>
          <p>
            View the saved session report.
          </p>
          <a href="saved-reports/sessions.php">Open Report</a>
        </div>
      <?php endif; ?>
    </div>

    <div class="footer-note">
      Built with PHP and SQLite for the analytics platform.
    </div>
  </div>
</body>
</html>
