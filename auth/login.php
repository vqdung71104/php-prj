<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (isset($_SESSION['user_id'])) {
    $username = urlencode($_SESSION['username']);
    $redirect_url = $_SESSION['role'] === 'writer' ? "/php-project/templates/writer-index.php?username=$username" : "/php-project/templates/user-index.php?username=$username";
    header("Location: $redirect_url");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = ($_POST['role'] === 'writer') ? 'writer' : 'user';
    if ($username === '' || $password === '') {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password']) && $user['role'] === $role) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $username_param = urlencode($user['username']);
            $redirect_url = $user['role'] === 'writer' ? "/php-project/templates/writer-index.php?username=$username_param" : "/php-project/templates/user-index.php?username=$username_param";
            header("Location: $redirect_url");
            exit();
        } else {
            $error = "Tên đăng nhập, mật khẩu hoặc vai trò không đúng.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/php-project/assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Đăng nhập</h2>
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Chọn vai trò:</label>
                <select id="role" name="role">
                    <option value="user">Người dùng</option>
                    <option value="writer">Người viết bài</option>
                </select>
            </div>
            <button type="submit" class="btn">Đăng nhập</button>
        </form>
        <p>Chưa có tài khoản? <a href="/php-project/register.php">Đăng ký ngay</a></p>
    </div>
</body>
</html>