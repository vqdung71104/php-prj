<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Xử lý redirect sau khi đăng nhập/đăng xuất
$current_page = basename($_SERVER['PHP_SELF']);
$is_writer_page = ($current_page === 'writer-index.php');
$base_url = $is_writer_page ? '/php-project/templates/writer-index.php' : '/php-project/';
$allowed_pages = ['login.php', 'register.php', 'logout.php'];

// Lấy URL hiện tại và phân tích
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url_parts = parse_url($current_url);
$current_path = $url_parts['path'];

// Xử lý query parameters
$query_params = [];
if (isset($url_parts['query'])) {
    parse_str($url_parts['query'], $query_params);
}

// Xóa tham số category nếu có (cho nút "Tất cả")
$all_params = $query_params;
unset($all_params['category']);

// Giữ lại các tham số khác (như username) khi thêm category
$category_params = $query_params;

// Tham số URL cần giữ lại
$query_params = [];
if (isset($_GET['username'])) {
    $query_params['username'] = $_GET['username'];
}

// Tạo URL cho nút "Tất cả"
$all_url = $base_url . (!empty($query_params) ? '?' . http_build_query($query_params) : '');


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
            <a href="<?= $current_path ?>?<?= http_build_query($all_params) ?>" 
       class="category-btn <?= !isset($_GET['category']) ? 'active' : '' ?>">
        Tất cả
    </a>
            <a href="<?= $current_path ?>?<?= http_build_query(array_merge($category_params, ['category' => 'kinh-te'])) ?>" 
       class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'kinh-te' ? 'active' : '' ?>">
        Kinh tế
    </a>
            <a href="<?= $current_path ?>?<?= http_build_query(array_merge($category_params, ['category' => 'chinh-tri'])) ?>" 
       class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'chinh-tri' ? 'active' : '' ?>">
        Chính trị
    </a>
    <a href="<?= $current_path ?>?<?= http_build_query(array_merge($category_params, ['category' => 'van-hoa'])) ?>" 
       class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'van-hoa' ? 'active' : '' ?>">
        Văn hóa
    </a>
            <a href="<?= $current_path ?>?<?= http_build_query(array_merge($category_params, ['category' => 'giao-duc'])) ?>" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'giao-duc' ? 'active' : '' ?>">Giáo dục</a>
            <a href="<?= $current_path ?>?<?= http_build_query(array_merge($category_params, ['category' => 'the-thao'])) ?>" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'the-thao' ? 'active' : '' ?>">Thể thao</a>
            <a href="<?= $current_path ?>?<?= http_build_query(array_merge($category_params, ['category' => 'the-gioi'])) ?>" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'the-gioi' ? 'active' : '' ?>">Thế giới</a>
        </div>

        <div class="user-info">
<?php if (isset($_SESSION['user_id'])): ?>
    <span>Xin chào, <?= htmlspecialchars($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : htmlspecialchars($_SESSION['email']) ?></span>
    <a href="/php-project/posts/create.php?email=<?= urlencode($_SESSION['email']) ?>" class="btn">Tạo bài viết mới</a>
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