<?php
// index.php
require __DIR__ . '/config/db.php'; // Kết nối database
require __DIR__ . '/templates/header.php'; // Include header


$conn = mysqli_connect('localhost', 'root', "", 'simple_blog') ;
mysqli_set_charset($conn, 'utf8');

require __DIR__ . '/templates/index.php'; // Include main content


?>



<?php require __DIR__ . '/templates/footer.php'; ?>