<?php
// user/profile.php - view and edit basic user profile and role-specific info
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit;
}
require_once __DIR__ . '/../config/Database.php';
$db = new Database();
$conn = $db->connect();
$message = '';
// Fetch user
$stmt = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result ? $result->fetch_assoc() : null;

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
  $update = $conn->prepare('UPDATE users SET username = ?, email = ? WHERE user_id = ?');
  $update->bind_param('ssi', $username, $email, $_SESSION['user_id']);
  $update->execute();
    $message = 'Profile updated.';
    $_SESSION['username'] = $username;
}

include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-3xl mx-auto mt-8 bg-gray-800 p-6 rounded-2xl">
  <h3 class="text-2xl text-blue-400 mb-3">My Profile</h3>
  <?php if ($message): ?><div class="bg-green-600 p-2 rounded text-white mb-3"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <form method="POST" class="space-y-3">
    <input name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full p-2 rounded text-black" required>
    <input name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full p-2 rounded text-black" required>
    <button class="bg-blue-500 px-4 py-2 rounded text-white">Save</button>
  </form>
  <div class="mt-4 text-sm text-gray-400">
    <p><strong>Role:</strong> <?= htmlspecialchars($user['user_type']) ?></p>
    <!-- <p><a href="../../dashboard.php?type=<?= $user['user_type'] ?>" class="text-blue-400">Go to Dashboard</a></p> -->
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>