<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Xử lý redirect sau khi đăng nhập/đăng xuất
$current_page = basename($_SERVER['PHP_SELF']);
$allowed_pages = ['login.php', 'register.php', 'logout.php'];

// Nếu đang ở trang đăng nhập/đăng ký và đã đăng nhập, chuyển hướng
if (in_array($current_page, ['login.php', 'register.php']) && isset($_SESSION['user_id'])) {
    $redirect_url = $_SESSION['role'] === 'writer' ? '/php-project/writer-index.php' : '/php-project/user-index.php';
    header("Location: $redirect_url");
    exit();
}

// Nếu đang ở trang cần đăng nhập nhưng chưa đăng nhập, chuyển hướng
$protected_pages = ['writer-index.php', 'create-post.php', 'edit-post.php', 'delete-post.php'];
if (in_array($current_page, $protected_pages) && !isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: /php-project/login.php");
    exit();
}

// Kiểm tra role nếu truy cập trang writer
if (in_array($current_page, ['writer-index.php', 'create-post.php', 'edit-post.php', 'delete-post.php']) && 
    isset($_SESSION['user_id']) && $_SESSION['role'] !== 'writer') {
    header("Location: /php-project/user-index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Trang Bài Viết' ?></title>
    <link rel="stylesheet" href="/php-project/assets/css/header.css">
</head>
<body>
    <div class="header-container">
        <div class="category-menu">
            <a href="/php-project/" class="category-btn <?= !isset($_GET['category']) ? 'active' : '' ?>">Tất cả</a>
            <a href="?category=kinh-te" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'kinh-te' ? 'active' : '' ?>">Kinh tế</a>
            <a href="?category=chinh-tri" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'chinh-tri' ? 'active' : '' ?>">Chính trị</a>
            <a href="?category=van-hoa" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'van-hoa' ? 'active' : '' ?>">Văn hóa</a>
            <a href="?category=giao-duc" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'giao-duc' ? 'active' : '' ?>">Giáo dục</a>
            <a href="?category=the-thao" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'the-thao' ? 'active' : '' ?>">Thể thao</a>
            <a href="?category=the-gioi" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'the-gioi' ? 'active' : '' ?>">Thế giới</a>            
        </div>

        <div class="user-info">
    <?php if (isset($_SESSION['user_id'])): ?>
        <span>Xin chào, <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] === 'writer' ? 'Writer' : 'User' ?>)</span>
        <?php if ($_SESSION['role'] === 'writer'): ?>
            <a class="writer-btn">Trang Writer</a>
        <?php endif; ?>
        <a href="/php-project/auth/logout.php" class="logout-btn">Đăng xuất</a>
    <?php else: ?>
        <a href="/php-project/login.php" class="login-btn">Đăng nhập</a>
        <a href="/php-project/register.php" class="register-btn">Đăng ký</a>
    <?php endif; ?>
        </div>
    </div>

    <!-- Hiển thị thông báo nếu có -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</body>
</html>