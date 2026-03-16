<?php
require 'auth.php';
require 'config.php';
require_role(["superadmin", "analyst"]);

$db = new SQLite3(SQLITE_DB);

/* Chart 1: Events by Type */
$typeLabels = [];
$typeCounts = [];

$result = $db->query("
    SELECT type, COUNT(*) as count
    FROM events
    GROUP BY type
    ORDER BY count DESC
");

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $typeLabels[] = $row['type'] ?: 'unknown';
    $typeCounts[] = (int)$row['count'];
}

/* Chart 2: Events by Page */
$pageLabels = [];
$pageCounts = [];

$result = $db->query("
    SELECT page, COUNT(*) as count
    FROM events
    GROUP BY page
    ORDER BY count DESC
");

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $label = $row['page'] ?: '(blank)';
    $label = preg_replace('#^https?://#', '', $label);
    $pageLabels[] = $label;
    $pageCounts[] = (int)$row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP_NAME; ?> - Charts</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      grid-template-columns: 1fr;
      gap: 24px;
    }

    .card {
      background: #111827;
      border: 1px solid #1f2937;
      border-radius: 18px;
      padding: 22px 22px 14px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }

    .card h2 {
      margin: 0 0 6px;
      font-size: 1.35rem;
      color: #f8fafc;
    }

    .card p {
      margin: 0 0 18px;
      color: #94a3b8;
      font-size: 0.95rem;
    }

    .chart-wrap {
      position: relative;
      height: 380px;
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

      .chart-wrap {
        height: 320px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title-wrap">
        <h1>Analytics Dashboard</h1>
        <p>Chart.js visualizations connected to the SQLite datastore.</p>
      </div>

      <div class="nav">
        <a href="index.php">Home</a>
        <a href="table.php">Table</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <div class="grid">
      <div class="card">
        <h2>Events by Type</h2>
        <p>Distribution of collected analytics events grouped by event type.</p>
        <div class="chart-wrap">
          <canvas id="typeChart"></canvas>
        </div>
      </div>

      <div class="card">
        <h2>Events by Page</h2>
        <p>Total event volume grouped by page URL.</p>
        <div class="chart-wrap">
          <canvas id="pageChart"></canvas>
        </div>
      </div>
    </div>

    <div class="footer-note">
      Built with PHP, SQLite, and Chart.js for HW4 Step 3.
    </div>
  </div>

  <script>
    const typeLabels = <?php echo json_encode($typeLabels); ?>;
    const typeCounts = <?php echo json_encode($typeCounts); ?>;

    const pageLabels = <?php echo json_encode($pageLabels); ?>;
    const pageCounts = <?php echo json_encode($pageCounts); ?>;

    const commonOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            color: '#e5e7eb',
            font: {
              size: 13,
              weight: '600'
            }
          }
        },
        tooltip: {
          backgroundColor: '#0f172a',
          titleColor: '#f8fafc',
          bodyColor: '#e5e7eb',
          borderColor: '#334155',
          borderWidth: 1
        }
      },
      scales: {
        x: {
          ticks: {
            color: '#cbd5e1',
            maxRotation: 25,
            minRotation: 0
          },
          grid: {
            color: 'rgba(148, 163, 184, 0.12)'
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: '#cbd5e1'
          },
          grid: {
            color: 'rgba(148, 163, 184, 0.12)'
          }
        }
      }
    };

    new Chart(document.getElementById('typeChart'), {
      type: 'bar',
      data: {
        labels: typeLabels,
        datasets: [{
          label: 'Event Count',
          data: typeCounts,
          backgroundColor: 'rgba(59, 130, 246, 0.75)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 1.5,
          borderRadius: 8
        }]
      },
      options: commonOptions
    });

    new Chart(document.getElementById('pageChart'), {
      type: 'bar',
      data: {
        labels: pageLabels,
        datasets: [{
          label: 'Event Count',
          data: pageCounts,
          backgroundColor: 'rgba(16, 185, 129, 0.75)',
          borderColor: 'rgba(16, 185, 129, 1)',
          borderWidth: 1.5,
          borderRadius: 8
        }]
      },
      options: {
        ...commonOptions,
        scales: {
          x: {
            ticks: {
              color: '#cbd5e1',
              maxRotation: 18,
              minRotation: 0
            },
            grid: {
              color: 'rgba(148, 163, 184, 0.12)'
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              color: '#cbd5e1'
            },
            grid: {
              color: 'rgba(148, 163, 184, 0.12)'
            }
          }
        }
      }
    });
  </script>
</body>
</html>
