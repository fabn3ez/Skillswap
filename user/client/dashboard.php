<?php
// user/client/dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'client') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../controllers/ClientController.php';
$c = new ClientController(); $client = $c->getClientByUserId($_SESSION['user_id']);
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-4xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Client Dashboard</h2>
  <p class="text-gray-400">Welcome, <?= htmlspecialchars($client['company_name'] ?? $_SESSION['username']) ?></p>
  <div class="mt-4"><a class="bg-blue-500 px-4 py-2 rounded text-white" href="create_project.php">Post New Project</a></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
