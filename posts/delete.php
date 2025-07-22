<?php
// delete-post.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'] ?? 0;
$email = isset($_GET['email']) ? $_GET['email'] : (isset($_SESSION['email']) ? $_SESSION['email'] : null);
if (!$email) {
    header("Location: /php-project/");
    exit();
}
// Lấy user_id từ email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    header("Location: /php-project/");
    exit();
}
$user_id = $user['id'];
// Kiểm tra bài viết thuộc về user này
$stmt = $conn->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error'] = "Bài viết không tồn tại hoặc bạn không có quyền xóa";
    header("Location: writer-post.php?email=" . urlencode($email));
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
header("Location: writer-post.php?email=" . urlencode($email));
exit();
?>