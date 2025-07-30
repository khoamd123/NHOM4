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

// Xử lý xóa tài khoản
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (deleteUser($id)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=users&success=2");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=users&error=2");
    }
    exit;
}

// Xử lý toggle trạng thái tài khoản
if (isset($_GET['toggle_status'])) {
    $id = $_GET['toggle_status'];
    if (toggleUserStatus($id)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=users&success=3");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=users&error=3");
    }
    exit;
}

// Lấy danh sách tài khoản
$users = getAllUsers();
include __DIR__ . '/../views/users/list.php';
?>