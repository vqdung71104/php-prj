<?php
require '../config/db.php';
session_start();

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM posts WHERE user_id = $user_id");

while($row = $result->fetch_assoc()) {
    echo "<h2>{$row['title']}</h2><p>{$row['content']}</p>";
    echo "<a href='edit.php?id={$row['id']}'>Sửa</a> | ";
    echo "<a href='delete.php?id={$row['id']}'>Xoá</a><hr>";
}
?>
<!DOCTYPE html> 
