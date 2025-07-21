<?php
// includes/functions.php

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