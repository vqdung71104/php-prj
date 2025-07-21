<?php
// post.php
require __DIR__ . '/config/db.php';
require __DIR__ . '/templates/header.php';

$conn = mysqli_connect('localhost', 'root', "", 'simple_blog');
mysqli_set_charset($conn, 'utf8');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$postId = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$post = $result->fetch_assoc();
?>

<div class="post-container">
    <div class="post-content">
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="post-meta">
            <?= htmlspecialchars($post['category']) ?> • 
            <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
        </div>
        <div class="post-body">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        <a href="index.php" class="back-button">Quay lại danh sách</a>
    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>