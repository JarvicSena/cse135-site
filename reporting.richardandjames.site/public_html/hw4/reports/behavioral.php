<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../config.php";
require_role(["superadmin", "analyst"]);

$db = new PDO("sqlite:" . SQLITE_DB);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$chartRows = $db->query("
  SELECT type, COUNT(*) AS cnt
  FROM events
  GROUP BY type
  ORDER BY cnt DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$labels = array_column($chartRows, "type");
$counts = array_map("intval", array_column($chartRows, "cnt"));

$tableRows = $db->query("
  SELECT ts, type, sessionId, page
  FROM events
  ORDER BY id DESC
  LIMIT 100
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Behavioral Report</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
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
    font-weight: 600;
  }

  .card {
    background: #111827;
    border: 1px solid #1f2937;
    border-radius: 18px;
    padding: 22px;
    margin-bottom: 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
  }

  .chart-wrap {
    position: relative;
    height: 360px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th,
  td {
    text-align: left;
    padding: 12px 14px;
    border-bottom: 1px solid #1f2937;
  }

  th {
    background: #1e293b;
    color: #f8fafc;
  }

  td {
    color: #dbe4ee;
  }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title-wrap">
        <h1>Behavioral Report</h1>
        <p>Event activity grouped by collected behavior data.</p>
      </div>
      <div class="nav">
        <a href="../index.php">Home</a>
        <a href="../export.php?report=behavioral">Export PDF</a>
	<a href="../logout.php">Logout</a>
      </div>
    </div>

    <div class="card">
      <h2>Analyst Comment</h2>
      <p>
        This report summarizes user behavior captured by the collector script.
        The chart shows which event types occur most often, while the table shows recent raw activity records.
      </p>
    </div>

    <div class="card">
      <h2>Events by Type</h2>
      <div class="chart-wrap"><canvas id="behaviorChart"></canvas></div>
    </div>

    <div class="card">
      <h2>Recent Behavioral Events</h2>
      <table>
        <tr><th>Timestamp</th><th>Type</th><th>Session</th><th>Page</th></tr>
        <?php foreach ($tableRows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r["ts"] ?? "") ?></td>
            <td><?= htmlspecialchars($r["type"] ?? "") ?></td>
            <td><?= htmlspecialchars($r["sessionId"] ?? "") ?></td>
            <td><?= htmlspecialchars($r["page"] ?? "") ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <script>
    new Chart(document.getElementById('behaviorChart'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Event Count',
          data: <?= json_encode($counts) ?>,
          backgroundColor: 'rgba(59,130,246,0.75)',
          borderColor: 'rgba(59,130,246,1)',
          borderWidth: 1.5,
          borderRadius: 8
        }]
      },
      options: { responsive:true, maintainAspectRatio:false }
    });
  </script>
</body>
</html>
