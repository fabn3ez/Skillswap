<?php
// user/freelancer/browse_projects.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'freelancer') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../controllers/ProjectController.php';
$pc = new ProjectController(); $projects = $pc->getAllProjects();
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-6xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Browse Projects</h2>
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
  <?php foreach($projects as $p): ?>
    <div class="bg-gray-800 p-4 rounded">
      <h3 class="text-white font-semibold"><?= htmlspecialchars($p['title']) ?></h3>
      <p class="text-gray-400"><?= htmlspecialchars(substr($p['description'],0,100)) ?>...</p>
      <div class="mt-2"><a class="text-blue-400" href="../../views/projects/details.php?id=<?= $p['project_id'] ?>">View</a></div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
