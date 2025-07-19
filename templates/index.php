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

<div class="news-container">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="news-card">
            <div class="news-content">
                <h2 class="news-title"><?= htmlspecialchars($row['title']) ?></h2>
                <div class="news-meta">
                    <?= htmlspecialchars($row['category']) ?> • 
                    <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                </div>
                <div class="news-body">
                    <?= nl2br(htmlspecialchars($row['content'])) ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

