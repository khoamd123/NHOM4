<?php
require_once __DIR__ . '/../auth.php';
checkAdminAuth();

// Đảm bảo kết nối database được load
require_once __DIR__ . '/../models/db.php';
require_once __DIR__ . '/../models/orderModel.php';

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];
    
    if (updateOrderStatus($orderId, $newStatus)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=orders&success=1");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=orders&error=1");
    }
    exit;
}

// Xử lý xem chi tiết đơn hàng
if (isset($_GET['detail'])) {
    $orderId = $_GET['detail'];
    $order = getOrderById($orderId);
    $orderItems = getOrderItems($orderId);
    
    if ($order) {
        include __DIR__ . '/../views/orders/detail.php';
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=orders&error=2");
    }
    exit;
}

// Xử lý lọc theo trạng thái
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
if ($status_filter) {
    $orders = getOrdersByStatus($status_filter);
} else {
    $orders = getAllOrders();
}

// Lấy danh sách đơn hàng
include __DIR__ . '/../views/orders/list.php';
?> 