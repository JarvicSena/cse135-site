<?php
require_once __DIR__ . "/auth.php";
require_role(["superadmin", "analyst", "viewer"]);

$report = $_GET["report"] ?? "behavioral";

$allowed = [
  "behavioral" => "/hw4/saved-reports/behavioral.php",
  "performance" => "/hw4/saved-reports/performance.php",
  "sessions" => "/hw4/saved-reports/sessions.php"
];

if (!isset($allowed[$report])) {
  http_response_code(404);
  echo "<h1>404 Not Found</h1>";
  exit;
}

$target = $allowed[$report];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Export Report</title>

<script>
window.onload = function () {
    const frame = document.getElementById("printFrame");

    frame.onload = function () {
        setTimeout(() => {
            frame.contentWindow.focus();
            frame.contentWindow.print();
        }, 1500);
    };

    frame.src = "<?php echo htmlspecialchars($target); ?>";
};
</script>

<style>
body {
    font-family: sans-serif;
    text-align: center;
    margin-top: 40px;
}
</style>
</head>

<body>

<h2>Preparing PDF export...</h2>
<p>Your print dialog should appear shortly.</p>

<iframe id="printFrame" style="width:0;height:0;border:0;"></iframe>

</body>
</html>
