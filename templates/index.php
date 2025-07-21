<?php
// templates/index.php

// Xử lý logic lấy bài viết từ database
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
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Trang Bài Viết' ?></title>
    <link rel="stylesheet" href="/php-project/assets/css/templates-index.css">
</head>
<body>
<!-- Form tìm kiếm -->
<form method="GET" class="search-form">
        <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
        <input type="text" name="search" placeholder="Tìm kiếm bài viết...">
        <button type="submit">Tìm kiếm</button>
    </form>
<div class="news-container">
    <?php while($row = $result->fetch_assoc()): ?>
        <a href="/php-project/posts/post.php?id=<?= $row['id'] ?>" class="news-card-link">
            <div class="news-card">
                <div class="news-content">
                    <h2 class="news-title"><?= htmlspecialchars($row['title']) ?></h2>
                    <div class="news-meta">
                        <?= htmlspecialchars($row['category']) ?> • 
                        <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                    </div>
                    <div class="news-excerpt">
                        <?= nl2br(htmlspecialchars(substr($row['content'], 0, 200))) ?>...
                    </div>
                </div>
            </div>
        </a>
    <?php endwhile; ?>
</div>
</body>
</html>