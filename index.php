<?php
// Lấy tham số page trên URL, mặc định là null
$page = isset($_GET['page']) ? $_GET['page'] : null;
// Nhúng layout chính
include 'view/layout/main.php';
?> 