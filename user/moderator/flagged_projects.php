<?php
// user/moderator/flagged_projects.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'moderator') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();
$result = $conn->query("SELECT p.*, c.company_name FROM projects p JOIN clients c ON p.client_id = c.client_id WHERE p.status = 'flagged' ORDER BY p.created_at DESC");
$projects = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-6xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Flagged Projects</h2>
  <?php foreach($projects as $p): ?>
    <div class="bg-gray-800 p-4 rounded mb-3"><strong class="text-white"><?= htmlspecialchars($p['title']) ?></strong><div class="text-gray-400"><?= htmlspecialchars(substr($p['description'],0,120)) ?>...</div></div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
