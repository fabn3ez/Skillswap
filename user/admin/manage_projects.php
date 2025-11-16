<?php
// user/admin/manage_projects.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../user/login.php'); exit;
}
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
if (isset($_GET['delete']) && $pid) {
  $d = $conn->prepare('DELETE FROM projects WHERE project_id = ?');
  $d->bind_param('i', $pid);
  $d->execute();
  header('Location: manage_projects.php'); exit;
}
$result = $conn->query('SELECT p.*, c.company_name FROM projects p JOIN clients c ON p.client_id = c.client_id ORDER BY p.created_at DESC');
$projects = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-6xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Manage Projects</h2>
  <?php foreach($projects as $p): ?>
    <div class="bg-gray-800 p-4 rounded mb-3">
      <div class="flex justify-between"><div><strong class="text-white"><?= htmlspecialchars($p['title']) ?></strong><div class="text-gray-400 text-sm"><?= htmlspecialchars($p['company_name']) ?></div></div><div><a class="text-red-400" href="manage_projects.php?delete=1&pid=<?= $p['project_id'] ?>">Delete</a></div></div>
    </div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
