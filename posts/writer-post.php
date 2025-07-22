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

<div class="container">
    <h2>Bài viết của tôi</h2>
    
    <!-- Nút điều hướng -->
    <div class="navigation-buttons" style="margin-bottom: 20px;">
        <a href="/php-project/posts/create.php?email=<?= urlencode($email) ?>" class="btn">Viết bài mới</a>
        <a href="/php-project/templates/writer-index.php?email=<?= urlencode($email) ?>" class="btn">Về trang chính</a>
    </div>
    
    <!-- Danh sách bài viết -->
    <?php if (count($posts) > 0): ?>
        <div class="posts-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-card" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <div class="post-meta" style="color: #666; margin-bottom: 10px;">
                        <span>Chuyên mục: <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $post['category']))) ?></span> • 
                        <span>Ngày đăng: <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                    </div>
                    <div class="post-content" style="margin-bottom: 10px;">
                        <?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>...
                    </div>
                    <div class="post-actions">
                        <a href="/php-project/posts/edit.php?id=<?= $post['id'] ?>&email=<?= urlencode($email) ?>" class="btn">Sửa</a>
                        <a href="/php-project/posts/delete.php?id=<?= $post['id'] ?>&email=<?= urlencode($email) ?>" class="btn" onclick="return confirm('Bạn chắc chắn muốn xóa bài viết này?')">Xóa</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Bạn chưa có bài viết nào.</p>
    <?php endif; ?>
    
    <!-- Nút điều hướng ở cuối trang -->
    <div class="navigation-buttons" style="margin-top: 20px;">
        <a href="/php-project/posts/create.php?email=<?= urlencode($email) ?>" class="btn">Viết bài mới</a>
        <a href="/php-project/" class="btn">Về trang chính</a>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>