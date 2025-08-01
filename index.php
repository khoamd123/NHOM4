<?php
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