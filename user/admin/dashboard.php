<?php
// user/admin/dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../user/login.php');
    exit;
}
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();

// Gather stats
$totalUsers = $conn->query('SELECT COUNT(*) FROM users')->fetch_row()[0];
$totalProjects = $conn->query("SELECT COUNT(*) FROM projects")->fetch_row()[0];
$totalBids = $conn->query("SELECT COUNT(*) FROM bids")->fetch_row()[0];
$totalFreelancers = $conn->query("SELECT COUNT(*) FROM freelancers")->fetch_row()[0];

include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-6xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Admin Dashboard</h2>
  <div class="grid grid-cols-4 gap-4 mb-6">
    <div class="p-4 bg-gray-800 rounded-lg"><div class="text-sm text-gray-400">Users</div><div class="text-xl text-white"><?= $totalUsers ?></div></div>
    <div class="p-4 bg-gray-800 rounded-lg"><div class="text-sm text-gray-400">Projects</div><div class="text-xl text-white"><?= $totalProjects ?></div></div>
    <div class="p-4 bg-gray-800 rounded-lg"><div class="text-sm text-gray-400">Bids</div><div class="text-xl text-white"><?= $totalBids ?></div></div>
    <div class="p-4 bg-gray-800 rounded-lg"><div class="text-sm text-gray-400">Freelancers</div><div class="text-xl text-white"><?= $totalFreelancers ?></div></div>
  </div>

  <div class="bg-gray-600 p-6 rounded-lg">
    <canvas id="adminChart" width="400" height="150"></canvas>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('adminChart').getContext('2d');
const adminChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Users','Projects','Bids','Freelancers'],
        datasets: [{ label: 'Platform Summary', data: [<?= $totalUsers ?>, <?= $totalProjects ?>, <?= $totalBids ?>, <?= $totalFreelancers ?>] }]
    },
    options: { responsive:true, plugins: { legend: { display: false } } }
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
