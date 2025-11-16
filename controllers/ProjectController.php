<?php
require_once __DIR__ . '/../config/Database.php';

class ProjectController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Create a new project (with skills)
    public function createProject($client_id, $title, $description, $type, $budget_low, $budget_high, $deadline, $skills = []) {
        // Map UI project type to ENUM values
        $type = strtolower(trim($type));
        if ($type === 'web development' || $type === 'mobile app' || $type === 'design' || $type === 'writing' || $type === 'other') {
            $type = 'fixed'; // Default to 'fixed' for these UI types
        } elseif ($type !== 'fixed' && $type !== 'hourly') {
            $type = 'fixed'; // Fallback
        }
        $insert = $this->conn->prepare("INSERT INTO projects (client_id, title, description, project_type, budget_low, budget_high, deadline, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'posted', NOW(), NOW())");
        $insert->bind_param("isssdds", $client_id, $title, $description, $type, $budget_low, $budget_high, $deadline);
        if (!$insert->execute()) {
            return false;
        }
        $project_id = $this->conn->insert_id;
        // Insert skills if provided
        if (!empty($skills) && is_array($skills)) {
            $ps = $this->conn->prepare("INSERT INTO project_skills (project_id, skill_id) VALUES (?, ?)");
            foreach ($skills as $skill_id) {
                $ps->bind_param("ii", $project_id, $skill_id);
                $ps->execute();
            }
        }
        return $project_id;
    }

    // Ensure freelancer record exists for a user_id, create if missing, return freelancer_id
    public function ensureFreelancerRecord($user_id) {
        $query = "SELECT freelancer_id FROM freelancers WHERE user_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['freelancer_id'];
        }
        // Create freelancer record if not exists
        $insert = "INSERT INTO freelancers (user_id, full_name) VALUES (?, '')";
        $stmt = $this->conn->prepare($insert);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    // Fetch all active projects
    public function getAllProjects() {
        $query = "SELECT p.*, c.company_name 
                  FROM projects p 
                  JOIN clients c ON p.client_id = c.client_id 
                  WHERE p.status = 'posted' 
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Fetch project details
    public function getProjectById($id) {
        $query = "SELECT p.*, c.company_name, c.industry, c.website 
                  FROM projects p 
                  JOIN clients c ON p.client_id = c.client_id 
                  WHERE p.project_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    // Calculate AI-like match score (basic simulation)
    public function calculateMatchScore($freelancerId, $projectId) {
        // Default base
        $score = 50;

        // Count matching skills
        $skillQuery = "SELECT COUNT(*) AS matched_skills
                       FROM freelancer_skills fs
                       JOIN project_skills ps ON fs.skill_id = ps.skill_id
                       WHERE fs.freelancer_id = ? AND ps.project_id = ?";
        $stmt = $this->conn->prepare($skillQuery);
        $stmt->bind_param("ii", $freelancerId, $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        $matched = 0;
        if ($row = $result->fetch_assoc()) {
            $matched = $row['matched_skills'];
        }

        $score += $matched * 10;

        // Add rating & success rate influence
        $freelancerQuery = "SELECT avg_rating, success_rate FROM freelancers WHERE freelancer_id = ?";
        $stmt = $this->conn->prepare($freelancerQuery);
        $stmt->bind_param("i", $freelancerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $freelancer = $result ? $result->fetch_assoc() : null;

        if ($freelancer) {
            $score += ($freelancer['avg_rating'] * 5);
            $score += ($freelancer['success_rate'] / 10);
        }

        // Normalize to 100%
        return min(100, round($score, 2));
    }
}
?>
