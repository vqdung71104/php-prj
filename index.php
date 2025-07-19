<?php
// index.php
require __DIR__ . '/config/db.php'; // Kết nối database
require __DIR__ . '/templates/header.php'; // Include header
require __DIR__ . '/templates/index.php'; // Include main content




// Xử lý logic lấy bài viết
$category = isset($_GET['category']) ? $_GET['category'] : null;
$query = "SELECT * FROM posts";
$params = [];

if ($category) {
    $query .= " WHERE category = ?";
    $params[] = $category;
}

$query .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($query);

if ($category) {
    $stmt->bind_param('s', $category);
}

$stmt->execute();
$result = $stmt->get_result();
?>



<?php require __DIR__ . '/templates/footer.php'; ?>