<?php
// search_suggest.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/db.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
if ($query === '') {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, title FROM posts WHERE title LIKE ? ORDER BY created_at DESC LIMIT 8";
$stmt = $conn->prepare($sql);
$like = "%$query%";
$stmt->bind_param('s', $like);
$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = [
        'id' => $row['id'],
        'title' => $row['title']
    ];
}
echo json_encode($posts); 