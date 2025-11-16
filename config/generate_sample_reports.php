<?php
// Auto-generate sample data for the reports table
require_once __DIR__ . '/../config/Database.php';
$db = new Database();
$conn = $db->connect();

// Get some user IDs and project IDs for realistic foreign keys
$user_ids = [];
$project_ids = [];
$res = $conn->query("SELECT user_id FROM users LIMIT 5");
while ($row = $res->fetch_assoc()) $user_ids[] = $row['user_id'];
$res = $conn->query("SELECT project_id FROM projects LIMIT 5");
while ($row = $res->fetch_assoc()) $project_ids[] = $row['project_id'];

if (count($user_ids) < 2) die('Not enough users in the database.');

$reports = [
    [
        'reported_by' => $user_ids[0],
        'reported_user_id' => $user_ids[1],
        'project_id' => isset($project_ids[0]) ? $project_ids[0] : null,
        'report_type' => 'user',
        'title' => 'Spam Account',
        'description' => 'User posted spam links in project discussions.',
        'status' => 'open',
    ],
    [
        'reported_by' => $user_ids[1],
        'reported_user_id' => $user_ids[0],
        'project_id' => isset($project_ids[1]) ? $project_ids[1] : null,
        'report_type' => 'project',
        'title' => 'Inappropriate Project',
        'description' => 'Project contains prohibited content.',
        'status' => 'in_review',
    ],
    [
        'reported_by' => $user_ids[0],
        'reported_user_id' => null,
        'project_id' => isset($project_ids[2]) ? $project_ids[2] : null,
        'report_type' => 'project',
        'title' => 'Fake Project',
        'description' => 'Project appears to be a scam.',
        'status' => 'resolved',
    ],
    [
        'reported_by' => $user_ids[1],
        'reported_user_id' => $user_ids[0],
        'project_id' => null,
        'report_type' => 'user',
        'title' => 'Harassment',
        'description' => 'User sent harassing messages.',
        'status' => 'closed',
    ],
    [
        'reported_by' => $user_ids[0],
        'reported_user_id' => null,
        'project_id' => null,
        'report_type' => 'other',
        'title' => 'Bug Report',
        'description' => 'Found a bug in the bidding system.',
        'status' => 'open',
    ],
];

foreach ($reports as $r) {
    $stmt = $conn->prepare("INSERT INTO reports (reported_by, reported_user_id, project_id, report_type, title, description, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param(
        "iiissss",
        $r['reported_by'],
        $r['reported_user_id'],
        $r['project_id'],
        $r['report_type'],
        $r['title'],
        $r['description'],
        $r['status']
    );
    $stmt->execute();
}
echo "Sample reports inserted!\n";
