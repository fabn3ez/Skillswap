<?php
// user/freelancer/dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'freelancer') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../controllers/ProjectController.php';
$pc = new ProjectController();
// ensure freelancer record exists
$freelancer_id = $pc->ensureFreelancerRecord($_SESSION['user_id']);
$projects = $pc->getAllProjects();
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-5xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Freelancer Dashboard</h2>
  <p class="text-gray-400">Welcome back, <?= htmlspecialchars($_SESSION['username']) ?></p>
  <div class="mt-4 grid md:grid-cols-2 gap-4">
    <div class="bg-gray-800 p-4 rounded">Top Matches<div class="mt-2"><?php foreach(array_slice($projects,0,3) as $p): ?><div class="text-gray-300"><?= htmlspecialchars($p['title']) ?></div><?php endforeach; ?></div></div>
    <div class="bg-gray-800 p-4 rounded">Quick Actions<div class="mt-2"><a class="text-blue-400" href="browse_projects.php">Browse Projects</a> | <a class="text-blue-400" href="profile.php">Profile</a></div></div>
  </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
