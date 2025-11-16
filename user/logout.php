<?php
// user/logout.php
// Simple wrapper to call AuthController logout
require_once __DIR__ . '/../controllers/AuthController.php';
$auth = new AuthController();
$auth->logout();
?>