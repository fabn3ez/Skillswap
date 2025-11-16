<?php
// user/moderator/disputes.php (simple placeholder)
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'moderator') { header('Location: ../../user/login.php'); exit; }
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-4xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Disputes</h2>
  <p class="text-gray-400">List and resolve disputes here. Integrate with contracts table and dispute workflow.</p>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
