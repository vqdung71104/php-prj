<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../templates/header.php';

if (!isset($_GET['id'])) {
    header("Location: /php-project/templates/index.php");
    exit();
}

$postId = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /php-project/templates/index.php");
    exit();
}

$post = $result->fetch_assoc();
$category = $post['category'];

// Lấy các bài cùng category (trừ bài hiện tại)
$related_stmt = $conn->prepare("SELECT id, title FROM posts WHERE category = ? AND id != ? ORDER BY created_at DESC LIMIT 5");
$related_stmt->bind_param('si', $category, $postId);
$related_stmt->execute();
$related_posts = $related_stmt->get_result();
?>

<div class="main-content" style="display: flex; gap: 32px; margin: 32px auto; max-width: 1200px;">
    <div class="post-content" style="flex: 3;">
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="post-meta" style="color: #666; margin-bottom: 10px;">
            <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $post['category']))) ?> •
            <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
        </div>
        <div class="post-body" style="margin-bottom: 20px;">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'writer' && $_SESSION['user_id'] == $post['user_id']): ?>
            <div class="post-actions" style="margin-bottom: 20px;">
                <a href="/php-project/posts/edit.php?id=<?= $post['id'] ?>" class="btn">Sửa</a>
                <a href="/php-project/posts/delete.php?id=<?= $post['id'] ?>" class="btn danger" onclick="return confirm('Bạn chắc chắn muốn xóa bài viết này?')">Xóa</a>
            </div>
        <?php endif; ?>
        <a href="/php-project/" class="back-button">Quay lại danh sách</a>
    </div>
    <aside class="related-posts" style="flex: 1; border-left: 1px solid #eee; padding-left: 24px;">
        <h3>Bài cùng chuyên mục</h3>
        <?php if ($related_posts->num_rows > 0): ?>
            <ul style="list-style: none; padding: 0;">
                <?php while($rel = $related_posts->fetch_assoc()): ?>
                    <li style="margin-bottom: 12px;">
                        <a href="/php-project/posts/post.php?id=<?= $rel['id'] ?>" style="text-decoration: none; color: #0077cc;">
                            <?= htmlspecialchars($rel['title']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Không có bài cùng chuyên mục.</p>
        <?php endif; ?>
    </aside>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>