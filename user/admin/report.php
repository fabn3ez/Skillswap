<?php
// admin/report.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../user/login.php'); exit;
}
require_once __DIR__ . '/../../config/Database.php';
include __DIR__ . '/../../includes/header.php';
$db = new Database(); $conn = $db->connect(); 
if (isset($_GET['download']) && $_GET['download'] === '1') {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="reports.csv"');
  $output = fopen('php://output', 'w');
  fputcsv($output, ['Report ID', 'Reporter', 'Type', 'Created At']);
  $q = "SELECT r.report_id, u.username AS reporter, r.report_type, r.created_at FROM reports r LEFT JOIN users u ON r.reported_by = u.user_id ORDER BY r.created_at DESC";
  $result = $conn->query($q);
  while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
  }
  fclose($output);
  exit;
}
$result = $conn->query("SELECT r.report_id, u.username AS reporter, r.report_type, r.created_at FROM reports r LEFT JOIN users u ON r.reported_by = u.user_id ORDER BY r.created_at DESC");
?>  
<!DOCTYPE html>
<html>
<head>
  <title>Admin Reports</title>
  <style>
    table { border-collapse: collapse; width: 100%; padding: 8px; }
    th, td { border: 1px solid #000000ff; padding: 8px; }
    th { background: #000000ff; }
  </style>
</head>
<body>
  <h1>Admin Reports</h1>
  <a href="?download=1" style="display:inline-block;margin-bottom:12px;padding:8px 16px;background:#2563eb;color:#fff;border-radius:6px;text-decoration:none;">Download CSV</a>
  <table>
    <tr>
  <th>Report ID</th>
  <th>Reporter</th>
  <th>Type</th>
  <th>Created At</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
  <td><?= htmlspecialchars($row['report_id']) ?></td>
  <td><?= htmlspecialchars($row['reporter']) ?></td>
  <td><?= htmlspecialchars($row['report_type']) ?></td>
  <td><?= htmlspecialchars($row['created_at']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>