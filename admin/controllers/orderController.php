<?php
require_once __DIR__ . '/../auth.php';
checkAdminAuth();

// Đảm bảo kết nối database được load
require_once __DIR__ . '/../models/db.php';
require_once __DIR__ . '/../models/orderModel.php';

// Xử lý cập nhật trạng thái đơn hàng (AJAX)
if (isset($_POST['update_status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];
    
    // Kiểm tra dữ liệu đầu vào
    if (!$orderId || !$newStatus) {
        echo json_encode([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ'
        ]);
        exit;
    }
    
    // Lấy trạng thái hiện tại của đơn hàng
    $currentOrder = getOrderById($orderId);
    if (!$currentOrder) {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy đơn hàng'
        ]);
        exit;
    }
    
    $currentStatus = $currentOrder['status'];
    
    // Kiểm tra quy tắc chuyển đổi trạng thái
    if (!isValidStatusTransition($currentStatus, $newStatus)) {
        echo json_encode([
            'success' => false,
            'message' => 'Không thể chuyển đổi trạng thái từ "' . getStatusText($currentStatus) . '" sang "' . getStatusText($newStatus) . '"'
        ]);
        exit;
    }
    
    // Cập nhật trạng thái
    if (updateOrderStatus($orderId, $newStatus)) {
        // Nếu trạng thái mới là "delivered" thì tự động cập nhật thanh toán thành công
        if ($newStatus === 'delivered') {
            updateOrderPaymentStatus($orderId, true);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'order_id' => $orderId,
            'new_status' => $newStatus,
            'old_status' => $currentStatus
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Không thể cập nhật trạng thái đơn hàng'
        ]);
    }
    exit;
}

// Hàm kiểm tra quy tắc chuyển đổi trạng thái
function isValidStatusTransition($currentStatus, $newStatus) {
    // Nếu giữ nguyên trạng thái thì luôn hợp lệ
    if ($currentStatus === $newStatus) {
        return true;
    }
    
    // Xử lý trường hợp trạng thái không xác định - cho phép chuyển đổi sang bất kỳ trạng thái nào
    if (empty($currentStatus) || $currentStatus === 'undefined' || $currentStatus === 'null') {
        return true;
    }
    
    // Quy tắc chuyển đổi trạng thái (sử dụng tên thực tế trong database)
    $validTransitions = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => [], // Không thể thay đổi từ "Đã giao hàng"
        'cancelled' => []  // Không thể thay đổi từ "Đã hủy"
    ];
    
    return isset($validTransitions[$currentStatus]) && 
           in_array($newStatus, $validTransitions[$currentStatus]);
}

// Hàm lấy text hiển thị cho trạng thái
function getStatusText($status) {
    $statusMap = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đã gửi',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy'
    ];
    return $statusMap[$status] ?? 'Không xác định';
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