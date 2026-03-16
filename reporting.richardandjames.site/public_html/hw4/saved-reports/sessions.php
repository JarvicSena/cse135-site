<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../config.php";
require_role(["superadmin", "analyst", "viewer"]);

$db = new PDO("sqlite:" . SQLITE_DB);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$rows = $db->query("
  SELECT sessionId, COUNT(*) AS cnt
  FROM events
  WHERE sessionId IS NOT NULL AND sessionId != ''
  GROUP BY sessionId
  ORDER BY cnt DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$labels = array_map(fn($r) => substr($r["sessionId"], 0, 8) . "...", $rows);
$counts = array_map(fn($r) => (int)$r["cnt"], $rows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Saved Session Report</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body{
	margin: 0;
	font-family: Arial, Helvetica, sans-serif;
	background: #0f172a;
	color: #e5e7eb;
    }
    .container{
	max-width: 1100px;
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
    .title-wrap h1{
	margin: 0 0 6px;
	font-size: 2.2rem;
	color:#f8fafc;
    }
    .title-wrap p{
	margin:0; color:#94a3b8;
    }
    .nav{
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
    }
    .nav a{
	text-decoration: none;
	color: #e5e7eb;
	background: #1e293b;
	padding: 10px 14px;
	border-radius: 10px;
	border: 1px solid #334155;
	font-weight: 600;
    }
    .card{
	background: #111827;
	border: 1px solid #1f2937;
	border-radius: 18px;
	padding: 22px;
	margin-bottom: 24px;
	box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }
    .chart-wrap{
	position: relative;
	height:360px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title-wrap">
        <h1>Saved Session Report</h1>
        <p>Viewer-safe report showing activity grouped by session.</p>
      </div>
      <div class="nav">
        <a href="../index.php">Home</a>
        <a href="../export.php?report=sessions">Export PDF</a>
        <a href="../logout.php">Logout</a>
      </div>
    </div>

    <div class="card">
      <h2>Analyst Comment</h2>
      <p>
        This report summarizes how recorded activity is distributed across collected sessions.
      </p>
    </div>

    <div class="card">
      <h2>Events by Session</h2>
      <div class="chart-wrap"><canvas id="sessionChart"></canvas></div>
    </div>
  </div>

  <script>
    new Chart(document.getElementById('sessionChart'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Events per Session',
          data: <?= json_encode($counts) ?>,
          backgroundColor: 'rgba(234,179,8,0.75)',
          borderColor: 'rgba(234,179,8,1)',
          borderWidth: 1.5,
          borderRadius: 8
        }]
      },
      options: { responsive:true, maintainAspectRatio:false }
    });
  </script>
</body>
</html>
