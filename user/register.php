<?php
// user/register.php
require_once __DIR__ . '/../controllers/AuthController.php';
$auth = new AuthController();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];
    $result = $auth->register($username, $email, $password, $user_type);
    if ($result === 'success') {
        header('Location: login.php?registered=1');
        exit;
    } else {
        $message = $result;
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-md mx-auto mt-10 bg-gray-800 rounded-2xl p-8">
  <h2 class="text-2xl font-bold text-blue-400 mb-4">Create Account</h2>
  <?php if ($message): ?>
    <div class="bg-red-500 text-white p-2 rounded mb-3 text-sm"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  <form method="POST" class="space-y-3">
    <input type="text" name="username" placeholder="Username" class="w-full p-2 rounded text-black" required>
    <input type="email" name="email" placeholder="Email" class="w-full p-2 rounded text-black" required>
    <input type="password" name="password" placeholder="Password" class="w-full p-2 rounded text-black" required>
    <select name="user_type" class="w-full p-2 rounded text-black" required>
      <option value="">Select Role</option>
      <option value="freelancer">Freelancer</option>
      <option value="client">Client</option>
      <!-- <option value="moderator">Moderator</option> -->
      <option value="admin">Admin</option>
    </select>
    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">Register</button>
  </form>
  <p class="mt-3 text-gray-400 text-sm text-center">Already have an account?
     <a href="login.php" class="text-blue-400 hover:underline">Login</a></p>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>