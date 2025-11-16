<?php
// user/moderator/dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'moderator') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();
// simple stats
$flagged = $conn->query("SELECT COUNT(*) FROM projects WHERE status = 'flagged'")->fetch_row()[0];
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-5xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Moderator Dashboard</h2>
  <div class="bg-gray-600 p-4 rounded">Flagged Projects: <span class="text-white"><?= $flagged ?></span></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
