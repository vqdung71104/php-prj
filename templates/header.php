<?php
// templates/header.php
session_start();
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Bài Viết</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* CSS từ phần trước giữ nguyên */
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .category-menu {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
            padding: 15px 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .category-btn {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        /* Thêm các style khác nếu cần */
        .news-container {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
    padding-right: 10px;
}

.news-card {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.news-title {
    color: #2c3e50;
    margin-top: 0;
}

.news-meta {
    color: #7f8c8d;
    font-size: 0.9em;
    margin-bottom: 15px;
}

.news-body {
    line-height: 1.6;
    color: #333;
}

/* Thanh cuộn tùy chỉnh */
.news-container::-webkit-scrollbar {
    width: 8px;
}

.news-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.news-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.news-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
    </style>
</head>
<body>
    <div class="category-menu">
        <a href="?category=kinh-te" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'kinh-te' ? 'active' : '' ?>">Kinh tế</a>
        <a href="?category=chinh-tri" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'chinh-tri' ? 'active' : '' ?>">Chính trị</a>
        <a href="?category=van-hoa" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'van-hoa' ? 'active' : '' ?>">Văn hóa</a>
        <a href="?category=giao-duc" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'giao-duc' ? 'active' : '' ?>">Giáo dục</a>
        <a href="?category=the-thao" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'the-thao' ? 'active' : '' ?>">Thể thao</a>
        <a href="?category=the-gioi" class="category-btn <?= isset($_GET['category']) && $_GET['category'] == 'the-gioi' ? 'active' : '' ?>">Thế giới</a>
    </div>