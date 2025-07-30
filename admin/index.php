<?php
require_once 'auth.php';
checkAdminAuth();

// Lấy tham số action từ URL
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
$module = isset($_GET['module']) ? $_GET['module'] : '';

// Debug: Log current action
error_log("Admin action: " . $action);

// Router cho admin panel
switch ($action) {
    case 'dashboard':
        // Trang chủ admin
        include __DIR__ . '/home.php';
        break;
        
    case 'users':
        // Quản lý user
        include __DIR__ . '/controllers/userController.php';
        break;
        
    case 'categories':
        // Quản lý danh mục
        include __DIR__ . '/controllers/categoryController.php';
        break;
        
    case 'products':
        // Quản lý sản phẩm (chưa implement)
        echo '<div class="container mt-5"><h2>Quản lý sản phẩm</h2><p>Chức năng đang phát triển...</p></div>';
        break;
        
    case 'orders':
        // Quản lý đơn hàng (chưa implement)
        echo '<div class="container mt-5"><h2>Quản lý đơn hàng</h2><p>Chức năng đang phát triển...</p></div>';
        break;
        

        
    default:
        // Trang 404 hoặc redirect về dashboard
        header("Location: index.php?action=dashboard");
        exit;
}
?> 