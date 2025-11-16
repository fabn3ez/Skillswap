<?php
// controllers/MatchController.php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../ai/ai_match.php';

class MatchController {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Fetch freelancer skills as strings
    private function getFreelancerSkills($freelancerId) {
        $stmt = $this->conn->prepare("SELECT s.skill_name FROM freelancer_skills fs JOIN skills s ON fs.skill_id = s.skill_id WHERE fs.freelancer_id = :fid");
        $stmt->execute([':fid' => $freelancerId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r)=>$r['skill_name'], $rows);
    }

    // Fetch available projects (posted)
    private function getAvailableProjects() {
        $stmt = $this->conn->prepare("SELECT p.project_id, p.title, p.description, p.project_type, p.budget_low, p.budget_high FROM projects p WHERE p.status = 'posted' ORDER BY p.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Main recommendation function — calls AI embeddings helper
    public function recommendProjects($freelancerId, $limit = 5) {
        $skills = $this->getFreelancerSkills($freelancerId);
        if (empty($skills)) return [];

        $projects = $this->getAvailableProjects();
        if (empty($projects)) return [];

        // Call AI helper (uses OpenAI embeddings)
        $recommended = getAIRecommendations($skills, $projects, $limit);

        // Normalize similarity to 0..1 and return
        return $recommended;
    }
}
?>