<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

class AuthController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($username, $email, $password, $user_type) {
        if (empty($username) || empty($email) || empty($password) || empty($user_type)) {
            return "All fields are required.";
        }
        $query = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return "Username or email already exists.";
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $insert = "INSERT INTO users (username, email, password_hash, user_type) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($insert);
        $stmt->bind_param("ssss", $username, $email, $password_hash, $user_type);
        $stmt->execute();
        return "success";
    }

    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            switch ($user['user_type']) {
                case 'admin': header("Location: admin/dashboard.php"); break;
                case 'client': header("Location: client/dashboard.php"); break;
                case 'freelancer': header("Location: freelancer/dashboard.php"); break;
                case 'moderator': header("Location: moderator/dashboard.php"); break;
                default: header("Location: index.php");
            }
            exit;
        } else {
            return "Invalid credentials.";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ../user/login.php");
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth = new AuthController();
    $auth->logout();
}
?>