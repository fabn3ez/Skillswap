<?php
// user/admin/manage_users.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../user/login.php'); exit;
}
require_once __DIR__ . '/../../config/Database.php';
$db = new Database(); $conn = $db->connect();
$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
if (isset($_GET['toggle']) && $uid) {
  $q = $conn->prepare('UPDATE users SET is_active = NOT is_active WHERE user_id = ?');
  $q->bind_param('i', $uid);
  $q->execute();
  header('Location: manage_users.php'); exit;
}
$result = $conn->query('SELECT user_id, username, email, user_type, is_active, created_at FROM users ORDER BY created_at DESC');
$users = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-6xl mx-auto mt-8">
  <h2 class="text-2xl text-blue-400 mb-4">Manage Users</h2>
  <table class="w-full text-left">
    <thead class="text-gray-400 text-sm"><tr><th>User</th><th>Email</th><th>Role</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($users as $u): ?>
      <tr class="border-t border-gray-700">
        <td class="py-2"><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['user_type']) ?></td>
        <td><?= $u['is_active'] ? 'Yes' : 'No' ?></td>
        <td><a class="text-blue-400" href="manage_users.php?toggle=1&uid=<?= $u['user_id'] ?>"><?= $u['is_active'] ? 'Deactivate' : 'Activate' ?></a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
