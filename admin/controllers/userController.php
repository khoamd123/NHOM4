<?php
require_once __DIR__ . '/../auth.php';
checkAdminAuth();

// Đảm bảo kết nối database được load
require_once __DIR__ . '/../models/db.php';
require_once __DIR__ . '/../models/userModel.php';

// Xử lý thêm tài khoản
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    
    if (addUser($name, $email, $password, $phone, $role)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=users&success=1");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=users&error=1");
    }
    exit;
}



// Lấy danh sách tài khoản
$users = getAllUsers();
include __DIR__ . '/../views/users/list.php';
?>