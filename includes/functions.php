<?php
// includes/functions.php

if (!isset($_SESSION['user_id']) && isset($_COOKIE['rememberme'])) {
    require_once __DIR__ . '/../config/db.php';
    $user_id = intval($_COOKIE['rememberme']);
    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isWriter() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'writer';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotWriter() {
    redirectIfNotLoggedIn();
    if (!isWriter()) {
        header("Location: user-index.php");
        exit();
    }
}
?>