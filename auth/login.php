<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (isset($_SESSION['user_id'])) {
    $email = urlencode($_SESSION['email']);
    $redirect_url = "/php-project/templates/writer-index.php?email=$email";
    header("Location: $redirect_url");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);
    if ($email === '' || $password === '') {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, email FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            if ($remember) {
                setcookie('rememberme', $user['id'], time() + 30*24*60*60, '/'); // 30 ngày
            }
            $email_param = urlencode($user['email']);
            $redirect_url = "/php-project/templates/writer-index.php?email=$email_param";
            header("Location: $redirect_url");
            exit();
        } else {
            $error = "Email hoặc mật khẩu không đúng.";
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
                <label for="email">Gmail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" id="remember" name="remember" style="width:auto;">
                <label for="remember" style="margin:0;">Ghi nhớ đăng nhập</label>
            </div>
            <button type="submit" class="btn">Đăng nhập</button>
        </form>
        <p>Chưa có tài khoản? <a href="/php-project/register.php">Đăng ký ngay</a></p>
    </div>
</body>
</html>