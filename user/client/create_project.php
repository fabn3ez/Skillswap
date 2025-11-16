<?php
// user/client/create_project.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'client') { header('Location: ../../user/login.php'); exit; }

require_once __DIR__ . '/../../controllers/ProjectController.php';
require_once __DIR__ . '/../../controllers/ClientController.php';
$c = new ClientController(); $client = $c->getClientByUserId($_SESSION['user_id']);
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title']; $description = $_POST['description']; $type = $_POST['project_type']; $low = $_POST['budget_low']; $high = $_POST['budget_high']; $deadline = $_POST['deadline'];
    $skills_raw = $_POST['skills'] ?? ''; $skills = array_filter(array_map('intval', array_map('trim', explode(',', $skills_raw))));
    $pc = new ProjectController(); $pid = $pc->createProject($client['client_id'],$title,$description,$type,$low,$high,$deadline,$skills);
    if ($pid) $message = 'Project posted successfully!';
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="max-w-3xl mx-auto mt-8 bg-gray-800 p-6 rounded-2xl">
  <h3 class="text-2xl text-blue-400 mb-3">Post a Project</h3>
  <?php if ($message): ?><div class="bg-green-600 p-2 rounded text-white mb-3"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <form method="POST" class="space-y-3">
    <input name="title" placeholder="Project Title" class="w-full p-2 rounded text-black" required>
    <textarea name="description" placeholder="Project Description" class="w-full p-2 rounded text-black" rows="5" required></textarea>
    <div class="grid grid-cols-2 gap-2">
      <input name="budget_low" placeholder="Budget Low" class="p-2 rounded text-black" required>
      <input name="budget_high" placeholder="Budget High" class="p-2 rounded text-black" required>
    </div>
    <select name="project_type" class="w-full p-2 rounded text-black" required>
      <option value="">Select Project Type</option>
      <option value="Web Development">Web Development</option>
      <option value="Mobile App">Mobile App</option>
      <option value="Design">Design</option>
      <option value="Writing">Writing</option>
      <option value="Other">Other</option>
    </select>
    <input name="deadline" type="date" class="p-2 rounded text-black" required>
    <input name="skills" placeholder="Skill IDs (comma separated)" class="w-full p-2 rounded text-black">
    <button class="bg-blue-500 px-4 py-2 rounded text-white">Post Project</button>
  </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
