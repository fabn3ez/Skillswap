<?php
// user/freelancer/my_bids.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'freelancer') { header('Location: ../../user/login.php'); exit; }
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();
// map user -> freelancer
$stmt = $conn->prepare('SELECT freelancer_id FROM freelancers WHERE user_id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$f = $result ? $result->fetch_assoc() : null;
$fid = $f ? $f['freelancer_id'] : 0;
$bids = $conn->prepare('SELECT b.*, p.title FROM bids b JOIN projects p ON b.project_id = p.project_id WHERE b.freelancer_id = ?');
$bids->bind_param('i', $fid);
$bids->execute();
$result2 = $bids->get_result();
$list = $result2 ? $result2->fetch_all(MYSQLI_ASSOC) : [];
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-4xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">My Bids</h2>
  <?php foreach($list as $b): ?>
    <div class="bg-gray-800 p-4 rounded mb-3"><strong class="text-white"><?= htmlspecialchars($b['title']) ?></strong><div class="text-gray-400"><?= htmlspecialchars(substr($b['proposal_text'],0,120)) ?>...</div><div class="text-green-400 mt-2">$<?= $b['bid_amount'] ?></div></div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
