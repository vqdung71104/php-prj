<?php
// edit-post.php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Kiểm tra đăng nhập và role writer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'writer') {
    header("Location: login.php");
    exit();
}

$categories = ['kinh-te', 'chinh-tri', 'van-hoa', 'giao-duc', 'the-thao', 'the-gioi'];
$post_id = $_GET['id'] ?? 0;

// Lấy thông tin bài viết
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $post_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    $_SESSION['error'] = "Bài viết không tồn tại hoặc bạn không có quyền sửa";
    header("Location: writer-index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = $_POST['category'];
    
    if (empty($title) || empty($content)) {
        $error = "Vui lòng điền đầy đủ tiêu đề và nội dung";
    } elseif (!in_array($category, $categories)) {
        $error = "Danh mục không hợp lệ";
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, category = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param('sssii', $title, $content, $category, $post_id, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Bài viết đã được cập nhật";
            header("Location: writer-index.php");
            exit();
        } else {
            $error = "Có lỗi xảy ra khi cập nhật bài viết";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa bài viết</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require_once __DIR__ . '/templates/header.php'; ?>
    
    <div class="container">
        <h1>Sửa bài viết</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category">Danh mục:</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= $cat === $post['category'] ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('-', ' ', $cat)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            
            <button type="submit" class="btn">Cập nhật</button>
            <a href="writer-index.php" class="btn cancel">Hủy bỏ</a>
        </form>
    </div>
    
    <?php require_once __DIR__ . '/templates/footer.php'; ?>
</body>
</html>