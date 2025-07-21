<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Lấy username từ param
$username = isset($_GET['username']) ? trim($_GET['username']) : null;
if (!$username) {
    header("Location: /php-project/");
    exit();
}

// Lấy thông tin writer từ DB
$stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user || $user['role'] !== 'writer') {
    header("Location: /php-project/");
    exit();
}
$writer_id = $user['id'];

// Lấy danh sách category từ một bảng hoặc hardcode nếu chưa có bảng
$categories = ['kinh-te', 'chinh-tri', 'van-hoa', 'giao-duc', 'the-thao', 'the-gioi'];

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = $_POST['category'] ?? '';
    if ($title === '' || $content === '' || !in_array($category, $categories)) {
        $error = 'Vui lòng điền đầy đủ thông tin và chọn đúng chuyên mục.';
    } else {
        // Tìm id lớn nhất hiện tại
        $result = $conn->query("SELECT MAX(id) AS max_id FROM posts");
        $row = $result->fetch_assoc();
        $new_id = $row['max_id'] ? $row['max_id'] + 1 : 1;
        $created_at = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO posts (id, user_id, title, content, category, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iissss', $new_id, $writer_id, $title, $content, $category, $created_at);
        if ($stmt->execute()) {
            // Sau khi đăng bài thành công, chuyển hướng đến trang xem bài đã đăng
            header("Location: /php-project/posts/writer-post.php?username=" . urlencode($username));
            exit();
        } else {
            $error = 'Có lỗi khi đăng bài.';
        }
    }
}
$page_title = 'Viết bài mới';
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container">
    <h2>Viết bài mới</h2>
    
    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Luôn hiển thị form viết bài -->
    <form method="POST">
        <div class="form-group">
            <label for="title">Tiêu đề:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Nội dung:</label>
            <textarea id="content" name="content" rows="6" required></textarea>
        </div>
        <div class="form-group">
            <label for="category">Chuyên mục:</label>
            <select id="category" name="category" required>
                <option value="">--Chọn chuyên mục--</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>"><?= ucfirst(str_replace('-', ' ', $cat)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn">Đăng bài</button>
    </form>
    
    <!-- Luôn hiển thị các nút điều hướng -->
    <div class="navigation-buttons" style="margin-top: 20px;">
        <a href="/php-project/posts/writer-post.php?username=<?= htmlspecialchars($username) ?>" class="btn">Xem bài đã đăng</a>
        <a href="/php-project/templates/writer-index.php?username=<?= htmlspecialchars($username) ?>" class="btn">Về trang chính</a>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>