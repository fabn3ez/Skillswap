<?php
// user/moderator/reports.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'moderator') { header('Location: ../../user/login.php'); exit; }
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-4xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Moderator Reports</h2>
  <p class="text-gray-400">Export moderator activity and generate reports.</p>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
