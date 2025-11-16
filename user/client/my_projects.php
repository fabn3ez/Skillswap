<?php
// user/client/my_projects.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'client') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../controllers/ClientController.php';
$c = new ClientController();
$client = $c->ensureClientRecord($_SESSION['user_id']);
$projects = $c->getProjectsByClientId($client['client_id']);
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-4xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">My Projects</h2>
  <?php if (empty($projects)): ?>
    <p class="text-gray-400">No projects yet. <a class="text-blue-400" href="create_project.php">Post one</a></p>
  <?php else: ?>
    <?php foreach($projects as $p): ?>
      <div class="bg-gray-800 p-4 rounded mb-3"><strong class="text-white"><?= htmlspecialchars($p['title']) ?></strong><div class="text-gray-400"><?= htmlspecialchars(substr($p['description'],0,100)) ?>...</div><div class="mt-2"><a class="text-blue-400" href="../../views/projects/details.php?id=<?= $p['project_id'] ?>">View</a></div></div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
