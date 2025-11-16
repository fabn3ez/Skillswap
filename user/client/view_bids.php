<?php
// user/client/view_bids.php?project_id=#
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'client') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();
if (!isset($_GET['project_id'])) { header('Location: my_projects.php'); exit; }
$pid = intval($_GET['project_id']);
$stmt = $conn->prepare('SELECT b.*, f.full_name FROM bids b JOIN freelancers f ON b.freelancer_id = f.freelancer_id WHERE b.project_id = :pid');
$stmt->execute([':pid'=>$pid]); $bids = $stmt->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-4xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Bids for Project #<?= $pid ?></h2>
  <?php foreach($bids as $b): ?>
    <div class="bg-gray-800 p-4 rounded mb-3"><strong class="text-white"><?= htmlspecialchars($b['full_name']) ?></strong><div class="text-gray-400"><?= htmlspecialchars(substr($b['proposal_text'],0,150)) ?>...</div><div class="text-green-400 mt-2">$<?= $b['bid_amount'] ?> — <?= $b['estimated_days'] ?> days</div></div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
