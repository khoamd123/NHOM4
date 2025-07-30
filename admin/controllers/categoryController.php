<?php
require_once __DIR__ . '/../auth.php';
checkAdminAuth();
require_once __DIR__ . '/../models/categoryModel.php';

// Xử lý thêm danh mục
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    
    if (addCategory($name, $slug)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=categories&success=1");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=categories&error=1");
    }
    exit;
}

// Xử lý xóa danh mục
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    deleteCategory($id);
    header("Location: /NHOM4_DU_AN_1/admin/index.php?action=categories");
    exit;
}

// Lấy danh sách danh mục
$categories = getAllCategories();
include __DIR__ . '/../views/categories/list.php';
?>