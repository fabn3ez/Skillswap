<?php
// controllers/ClientController.php
require_once __DIR__ . '/../config/Database.php';

class ClientController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Get client info by user_id
    public function getClientByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE user_id = ? LIMIT 1");
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result ? $result->fetch_assoc() : null;
        return $client;
    }

    // Ensure client record exists for a user_id, create if missing, return client row
    public function ensureClientRecord($user_id) {
        $client = $this->getClientByUserId($user_id);
        if ($client) return $client;
        // Get user info for defaults
        $user_stmt = $this->conn->prepare("SELECT username FROM users WHERE user_id = ?");
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user = $user_result ? $user_result->fetch_assoc() : null;
        $company_name = $user ? $user['username'] . "'s Company" : 'New Company';
        $insert = $this->conn->prepare("INSERT INTO clients (user_id, company_name) VALUES (?, ?)");
        $insert->bind_param("is", $user_id, $company_name);
        $insert->execute();
        return $this->getClientByUserId($user_id);
    }

    // Get all projects for a client
    public function getProjectsByClientId($client_id) {
        $stmt = $this->conn->prepare("SELECT * FROM projects WHERE client_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Add more client-specific methods as needed
}
