<?php
// user/freelancer/earnings.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'freelancer') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();
$stmt = $conn->prepare('SELECT freelancer_id, total_earnings FROM freelancers WHERE user_id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$f = $result ? $result->fetch_assoc() : null;
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-3xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Earnings</h2>
  <div class="bg-gray-800 p-4 rounded"><div class="text-gray-400">Total Earnings</div><div class="text-white text-2xl">$<?= number_format($f['total_earnings'] ?? 0,2) ?></div></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
