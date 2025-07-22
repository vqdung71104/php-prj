<?php
// templates/index.php

// Xử lý logic lấy bài viết từ database
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$query = "SELECT * FROM posts";
$params = [];
$types = '';
$conditions = [];

if ($category) {
    $conditions[] = "category = ?";
    $params[] = $category;
    $types .= 's';
}
if ($search) {
    $conditions[] = "title LIKE ?";
    $params[] = "%$search%";
    $types .= 's';
}
if ($conditions) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}
$query .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
</head>
<body>
<!-- Form tìm kiếm -->
<form method="GET" class="search-form" autocomplete="off">
    <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
    <input type="text" name="search" id="search-input" placeholder="Tìm kiếm bài viết..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
    <button type="submit">Tìm kiếm</button>
    <div id="search-suggestions" class="search-suggestions"></div>
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
<script src="/php-project/assets/js/search-autocomplete.js"></script>
</body>
</html>