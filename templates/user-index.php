<?php
// user-index.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Lấy username từ param hoặc session
$username = isset($_GET['username']) ? trim($_GET['username']) : (isset($_SESSION['username']) ? $_SESSION['username'] : null);

if (!$username) {
    header("Location: /php-project/");
    exit();
}

// Lấy thông tin user từ DB
$stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || $user['role'] !== 'user') {
    header("Location: /php-project/");
    exit();
}

$page_title = "Trang người dùng";
require_once __DIR__ . '/header.php';

// Build base query string for category links
$query_username = '?username=' . urlencode($username);
?>
<div class="container">
    <h1>Chào mừng <?= htmlspecialchars($username) ?> (Người dùng)</h1>
    <!-- Form tìm kiếm -->
    <form method="GET" class="search-form">
        <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
        <input type="text" name="search" placeholder="Tìm kiếm bài viết...">
        <button type="submit">Tìm kiếm</button>
    </form>
    <!-- Hiển thị bài viết -->
    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $query = "SELECT * FROM posts";
    $conditions = [];
    $params = [];
    $types = '';
    if ($search) {
        $conditions[] = "(title LIKE ? OR content LIKE ?)";
        $search_term = "%$search%";
        $params[] = $search_term;
        $params[] = $search_term;
        $types .= 'ss';
    }
    if ($category) {
        $conditions[] = "category = ?";
        $params[] = $category;
        $types .= 's';
    }
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }
    $query .= " ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    require_once __DIR__ . '/index.php';
    ?>
</div>
<script>
// Sửa các link category để giữ username param
window.addEventListener('DOMContentLoaded', function() {
    var username = "<?= htmlspecialchars($username) ?>";
    document.querySelectorAll('.category-menu a').forEach(function(link) {
        var url = new URL(link.href, window.location.origin);
        url.searchParams.set('username', username);
        link.href = url.pathname + url.search;
    });
});
</script>
<?php require_once __DIR__ . '/footer.php'; ?>