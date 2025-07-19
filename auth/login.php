<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];

            if ($remember) {
                setcookie("remember_me", $user['id'], time() + (86400 * 30), "/"); // 30 ngày
            }

            header("Location: ../index.php");
            exit();
        } else {
            echo "Sai mật khẩu";
        }
    } else {
        echo "Tài khoản không tồn tại";
    }
}

?>