<?php
require_once '../models/userModel.php';

// Xử lý thêm tài khoản
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    addUser($name, $email, $password, $phone, $role);
    header("Location: userController.php");
    exit;
}

// Xử lý xóa tài khoản
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    deleteUser($id);
    header("Location: userController.php");
    exit;
}

// Lấy danh sách tài khoản
$users = getAllUsers();
include '../views/users/list.php';
?>