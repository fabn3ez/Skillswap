<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillSwap | AI Freelance Marketplace</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script<script>AOS.init({ duration: 1000, once: true });</script>
  <script>AOS.init({ duration: 1000, once: true });</script>

  <!-- <script src="assets/js/theme.js" defer></script> -->
  <style>
    header {
      position: sticky;
      top: 0;
      z-index: 50;
      backdrop-filter: blur(8px);
      border-bottom: 1px solid #2a2a2a;
      box-shadow: 0 4px 24px 0 #1e293b99;
    }
    header a:hover {
      text-shadow: 0 0 8px #3b82f6;
    }
    main {
      box-shadow: 0 8px 40px 0 #4c658f55;
      border-radius: 1.25rem;
      margin-top: 2rem;
      background: rgba(31,41,55,0.95);
    }
    body {
      background: linear-gradient(135deg, #111827 0%, #1f2937 60%, #111827 100%);
      min-height: 100vh;
    }
  </style>
</head>
<body class="text-gray-100 min-h-screen">
<header class="bg-gray-900 bg-opacity-95 text-gray-100 shadow-2xl border-b border-blue-900">
  <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
    <h1 class="text-2xl font-bold text-blue-400">SkillSwap</h1>
    <nav class="flex space-x-6 text-gray-300">
      <?php if (isset($_SESSION['user_type'])): ?>
        <?php if ($_SESSION['user_type'] === 'admin'): ?>
          <a href="/skillswap/user/profile.php" class="hover:text-blue-400">Profile</a>
          <a href="/skillswap/user/admin/dashboard.php" class="hover:text-blue-400">Dashboard</a>
          <a href="/skillswap/user/admin/manage_projects.php" class="hover:text-blue-400">All Projects</a>
          <a href="/skillswap/user/admin/manage_users.php" class="text-red-400 hover:text-red-300">Manage Users</a>
          <a href="/skillswap/user/admin/report.php" class="hover:text-blue-400">Reports</a>
        <?php elseif ($_SESSION['user_type'] === 'client'): ?>
          <a href="/skillswap/user/profile.php" class="hover:text-blue-400">Profile</a>
          <a href="/skillswap/user/client/view_bids.php" class="hover:text-blue-400">View Bids</a>
          <a href="/skillswap/user/client/create_project.php" class="hover:text-blue-400">Post Project</a>
          <a href="/skillswap/user/client/my_projects.php" class="hover:text-blue-400">My Projects</a>
        <?php elseif ($_SESSION['user_type'] === 'freelancer'): ?>
          <a href="/skillswap/user/freelancer/browse_projects.php" class="hover:text-blue-400">Browse</a>
          <a href="/skillswap/user/profile.php" class="hover:text-blue-400">Profile</a>
          <a href="/skillswap/user/freelancer/earnings.php" class="hover:text-blue-400">Earnings</a>
          <a href="/skillswap/user/freelancer/my_bids.php" class="hover:text-blue-400">My Bids</a>
        <?php elseif ($_SESSION['user_type'] === 'moderator'): ?>
          <a href="/skillswap/user/moderator/disputes.php" class="hover:text-blue-400">Disputes</a>
          <a href="/skillswap/user/moderator/flagged_projects.php" class="hover:text-blue-400">Flagged Projects</a>
          <a href="/skillswap/user/moderator/reports.php" class="hover:text-blue-400">Reports</a>
        <?php endif; ?>
        <a href="/skillswap/controllers/AuthController.php?action=logout" class="text-red-400 hover:text-red-300">Logout</a>
      <?php else: ?>
        <a href="/skillswap/" class="hover:text-blue-400">Home</a> 
        <a href="/skillswap/user/login.php" class="hover:text-blue-400">Login</a>
        <a href="/skillswap/user/register.php" class="hover:text-blue-400">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

</header>
<main class="p-6 bg-gray-800 bg-opacity-90 min-h-screen rounded-2xl shadow-2xl max-w-6xl mx-auto mt-8">