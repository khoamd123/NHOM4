<?php
require_once '../models/categoryModel.php';

// Xử lý thêm danh mục
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    addCategory($name, $slug);
    header("Location: categoryController.php");
    exit;
}

// Xử lý xóa danh mục
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    deleteCategory($id);
    header("Location: categoryController.php");
    exit;
}

// Lấy danh sách danh mục
$categories = getAllCategories();
include '../views/categories/list.php';
?>