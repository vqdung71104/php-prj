<?php
// delete-post.php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Kiểm tra đăng nhập và role writer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'writer') {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'] ?? 0;

// Kiểm tra bài viết thuộc về user này
$stmt = $conn->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $post_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Bài viết không tồn tại hoặc bạn không có quyền xóa";
    header("Location: writer-index.php");
    exit();
}

// Thực hiện xóa
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param('i', $post_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Bài viết đã được xóa";
} else {
    $_SESSION['error'] = "Có lỗi xảy ra khi xóa bài viết";
}

header("Location: writer-index.php");
exit();
?>