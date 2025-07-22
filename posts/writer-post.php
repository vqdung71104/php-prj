<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Lấy email từ param
$email = isset($_GET['email']) ? trim($_GET['email']) : (isset($_SESSION['email']) ? $_SESSION['email'] : null);
if (!$email) {
    header("Location: /php-project/");
    exit();
}

// Lấy thông tin user từ DB
$stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    header("Location: /php-project/");
    exit();
}
$user_id = $user['id'];
$username = $user['username'];

// Lấy danh sách bài viết của user
$stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = 'Bài viết của tôi';
require_once __DIR__ . '/../templates/header.php';
?>

<?php
// ...existing code...
$page_title = 'Bài viết của tôi';
require_once __DIR__ . '/../templates/header.php';
?>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<link rel="stylesheet" href="/php-project/assets/css/reset.css">
<link rel="stylesheet" href="/php-project/assets/css/base.css">
<link rel="stylesheet" href="/php-project/assets/css/writer-post.css">
<link rel="stylesheet" href="/php-project/assets/css/responsive.css">



<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<div class="w3-content w3-padding" style="max-width:1100px">
    <h2 class="w3-text-red w3-margin-bottom">Bài viết của tôi</h2>
    
    <!-- Nút điều hướng -->
    <div class="w3-bar w3-margin-bottom">
        <a href="/php-project/posts/create.php?email=<?= urlencode($email) ?>" class="w3-button w3-red w3-round w3-margin-right">Viết bài mới</a>
        <a href="/php-project/templates/writer-index.php?email=<?= urlencode($email) ?>" class="w3-button w3-red w3-round">Về trang chính</a>
    </div>
    
    <!-- Danh sách bài viết -->
    <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <div class="w3-card w3-white w3-round w3-margin-bottom w3-padding">
                <h3 class="w3-text-red"><?= htmlspecialchars($post['title']) ?></h3>
                <div class="w3-text-grey w3-small w3-margin-bottom">
                    <span>Chuyên mục: <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $post['category']))) ?></span> • 
                    <span>Ngày đăng: <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                </div>
                <div class="w3-margin-bottom">
                    <?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>...
                </div>
                <div>
                    <a href="/php-project/posts/edit.php?id=<?= $post['id'] ?>&email=<?= urlencode($email) ?>" class="w3-button w3-blue w3-round w3-margin-right">Sửa</a>
                    <a href="/php-project/posts/delete.php?id=<?= $post['id'] ?>&email=<?= urlencode($email) ?>" class="w3-button w3-grey w3-round" onclick="return confirm('Bạn chắc chắn muốn xóa bài viết này?')">Xóa</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Bạn chưa có bài viết nào.</p>
    <?php endif; ?>
    
    <!-- Nút điều hướng ở cuối trang
    <div class="w3-bar w3-margin-top">
        <a href="/php-project/posts/create.php?email=<?= urlencode($email) ?>" class="w3-button w3-red w3-round w3-margin-right">Viết bài mới</a>
        <a href="/php-project/" class="w3-button w3-red w3-round">Về trang chính</a>
    </div>
    -->  
</div>
    