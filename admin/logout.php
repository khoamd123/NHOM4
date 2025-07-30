<?php
session_start();

// Xóa tất cả session
session_destroy();

// Chuyển về trang chủ client
header("Location: /NHOM4_DU_AN_1/index.php");
exit;
?> 