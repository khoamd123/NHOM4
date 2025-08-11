<?php
// Kiểm tra checkout page trước tiên - bypass main.php
if (isset($_GET['page']) && $_GET['page'] === 'checkout') {
    require_once __DIR__ . "/controllers/CheckoutController.php";
    $checkoutController = new CheckoutController();
    
    if (isset($_GET['action']) && $_GET['action'] === 'place-order') {
        $checkoutController->placeOrder();
    } else {
        $checkoutController->view();
    }
    exit; // Dừng xử lý hoàn toàn
}

// Kiểm tra nếu có controller và action (cho user auth)
if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'] ?? 'user';
    $action = $_GET['action'] ?? 'login';

    require_once __DIR__ . "/controllers/" . ucfirst($controller) . "Controller.php";
    $controllerClass = ucfirst($controller) . "Controller";
    $controllerObject = new $controllerClass();
    $controllerObject->$action();
} else {
    // Frontend - Lấy tham số page trên URL, mặc định là null
    $page = isset($_GET['page']) ? $_GET['page'] : null;
    
    // Nhúng layout chính
    include 'views/layout/main.php';
}
?> 