<?php
require_once __DIR__ . '/../models/OrderModel.php';

class OrderController {
    
    // Hiển thị danh sách đơn hàng của user
    public function myOrders() {
        session_start();
        
        if (!isset($_SESSION['user'])) {
            header('Location: /NHOM4_DU_AN_1/index.php?controller=user&action=login');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $orders = OrderModel::getOrdersByUserId($userId);
        
        // Include OrderModel to use in view
        require_once __DIR__ . '/../models/OrderModel.php';
        
        include __DIR__ . '/../views/orders/my-orders.php';
    }
    
    // Hiển thị chi tiết đơn hàng
    public function orderDetail() {
        session_start();
        
        if (!isset($_SESSION['user'])) {
            header('Location: /NHOM4_DU_AN_1/index.php?controller=user&action=login');
            exit;
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header('Location: /NHOM4_DU_AN_1/index.php?controller=order&action=myOrders');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $order = OrderModel::getOrderDetail($orderId, $userId);
        
        if (!$order) {
            header('Location: /NHOM4_DU_AN_1/index.php?controller=order&action=myOrders');
            exit;
        }
        
        $orderItems = OrderModel::getOrderItems($orderId);
        
        include __DIR__ . '/../views/orders/order-detail.php';
    }
    
    // Hủy đơn hàng
    public function cancelOrder() {
        session_start();
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng']);
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $result = OrderModel::cancelOrder($orderId, $userId);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
} 