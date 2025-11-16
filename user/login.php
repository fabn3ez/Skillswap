<?php
require_once '../controllers/AuthController.php';
$auth = new AuthController();
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $message = $auth->login($email, $password);
}
?>
<?php include '../includes/header.php'; ?>
<div class="max-w-md mx-auto mt-10 bg-gray-800 rounded-2xl p-8" data-aos="fade-up">
  <h2 class="text-2xl font-bold text-blue-400 mb-4">Login to SkillSwap</h2>
  <?php if ($message): ?>
    <div class="bg-red-500 text-white p-2 rounded mb-3 text-sm"><?= $message ?></div>
  <?php endif; ?>
  <form method="POST">
    <input type="email" name="email" placeholder="Email" class="w-full mb-3 p-2 rounded text-black" required>
    <input type="password" name="password" placeholder="Password" class="w-full mb-3 p-2 rounded text-black" required>
    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">Login</button>
  </form>
  <p class="mt-3 text-gray-400 text-sm text-center">Don’t have an account?
     <a href="register.php" class="text-blue-400 hover:underline">Register</a></p>
</div>
<?php include '../includes/footer.php'; ?>