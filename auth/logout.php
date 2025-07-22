<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Xóa cookie rememberme nếu có
if (isset($_COOKIE['rememberme'])) {
    setcookie('rememberme', '', time() - 3600, '/');
}
$_SESSION = array();
session_destroy();
header("Location: /php-project/");
exit();
?>