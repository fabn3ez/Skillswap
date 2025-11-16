<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/Database.php';
$db = new Database();
$conn = $db->connect();

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'] ?? '';

// Fetch user basic info
$stmt = $conn->prepare('SELECT username, email, user_type, profile_picture, created_at FROM users WHERE user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch role-specific info
$role_info = null;
if ($user_type === 'client') {
    $stmt = $conn->prepare('SELECT company_name, company_size, industry, website, total_spent, total_projects_posted FROM clients WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $role_info = $stmt->get_result()->fetch_assoc();
} elseif ($user_type === 'freelancer') {
    $stmt = $conn->prepare('SELECT full_name, tagline, bio, hourly_rate, total_earnings, avg_rating, total_projects, success_rate, location, website FROM freelancers WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $role_info = $stmt->get_result()->fetch_assoc();
}
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-2xl mx-auto mt-8 bg-gray-800 p-6 rounded-2xl">
  <h2 class="text-2xl text-blue-400 mb-4">My Profile</h2>
  <div class="flex items-center mb-4">
    <?php if (!empty($user['profile_picture'])): ?>
      <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile" class="w-20 h-20 rounded-full mr-4 border-2 border-blue-400">
    <?php else: ?>
      <div class="w-20 h-20 rounded-full bg-gray-600 flex items-center justify-center text-3xl text-white mr-4">
        <?= strtoupper($user['username'][0]) ?>
      </div>
    <?php endif; ?>
    <div>
      <div class="text-xl text-white font-semibold"><?= htmlspecialchars($user['username']) ?></div>
      <div class="text-gray-400 text-sm">Joined: <?= date('F j, Y', strtotime($user['created_at'])) ?></div>
      <div class="text-blue-300 text-sm mt-1">Role: <?= ucfirst($user['user_type']) ?></div>
    </div>
  </div>
  <div class="mb-2 text-white"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
  <?php if ($user_type === 'client' && $role_info): ?>
    <div class="mt-4 text-blue-200 font-semibold">Client Details</div>
    <div class="text-white">Company: <?= htmlspecialchars($role_info['company_name']) ?></div>
    <div class="text-white">Size: <?= htmlspecialchars($role_info['company_size']) ?></div>
    <div class="text-white">Industry: <?= htmlspecialchars($role_info['industry']) ?></div>
    <div class="text-white">Website: <?= htmlspecialchars($role_info['website']) ?></div>
    <div class="text-white">Total Spent: $<?= number_format($role_info['total_spent'],2) ?></div>
    <div class="text-white">Projects Posted: <?= (int)$role_info['total_projects_posted'] ?></div>
  <?php elseif ($user_type === 'freelancer' && $role_info): ?>
    <div class="mt-4 text-blue-200 font-semibold">Freelancer Details</div>
    <div class="text-white">Name: <?= htmlspecialchars($role_info['full_name']) ?></div>
    <div class="text-white">Tagline: <?= htmlspecialchars($role_info['tagline']) ?></div>
    <div class="text-white">Bio: <?= nl2br(htmlspecialchars($role_info['bio'])) ?></div>
    <div class="text-white">Hourly Rate: $<?= number_format($role_info['hourly_rate'],2) ?></div>
    <div class="text-white">Earnings: $<?= number_format($role_info['total_earnings'],2) ?></div>
    <div class="text-white">Rating: <?= number_format($role_info['avg_rating'],2) ?>/5</div>
    <div class="text-white">Projects: <?= (int)$role_info['total_projects'] ?></div>
    <div class="text-white">Success Rate: <?= number_format($role_info['success_rate'],2) ?>%</div>
    <div class="text-white">Location: <?= htmlspecialchars($role_info['location']) ?></div>
    <div class="text-white">Website: <?= htmlspecialchars($role_info['website']) ?></div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
