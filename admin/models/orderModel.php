<?php
// Đảm bảo kết nối database được load
if (!isset($conn)) {
    require_once __DIR__ . '/db.php';
}

// Lấy tất cả đơn hàng với thông tin khách hàng
function getAllOrders() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting orders: " . $e->getMessage());
        return [];
    }
}

// Lấy đơn hàng theo ID với thông tin chi tiết
function getOrderById($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting order by ID: " . $e->getMessage());
        return null;
    }
}

// Lấy items của đơn hàng
function getOrderItems($orderId) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image, pv.size, pv.color
            FROM order_items oi
            JOIN product_variants pv ON oi.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting order items: " . $e->getMessage());
        return [];
    }
}

// Cập nhật trạng thái đơn hàng
function updateOrderStatus($id, $status) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    } catch (PDOException $e) {
        error_log("Error updating order status: " . $e->getMessage());
        return false;
    }
}

// Cập nhật trạng thái thanh toán
function updateOrderPaymentStatus($id, $isPaid) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE orders SET is_paid = ? WHERE id = ?");
        return $stmt->execute([$isPaid ? 1 : 0, $id]);
    } catch (PDOException $e) {
        error_log("Error updating order payment status: " . $e->getMessage());
        return false;
    }
}

// Lấy đơn hàng theo trạng thái
function getOrdersByStatus($status) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.status = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting orders by status: " . $e->getMessage());
        return [];
    }
}

// Đếm tổng số đơn hàng
function getTotalOrders() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM orders");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error counting orders: " . $e->getMessage());
        return 0;
    }
}

// Đếm đơn hàng theo trạng thái
function getOrdersCountByStatus($status) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders WHERE status = ?");
        $stmt->execute([$status]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error counting orders by status: " . $e->getMessage());
        return 0;
    }
}

// Lấy đơn hàng chờ xử lý
function getPendingOrders() {
    return getOrdersCountByStatus('pending');
}

// Lấy đơn hàng đang xử lý
function getProcessingOrders() {
    return getOrdersCountByStatus('processing');
}

// Lấy đơn hàng đã gửi
function getShippedOrders() {
    return getOrdersCountByStatus('shipped');
}

// Lấy đơn hàng đã giao
function getDeliveredOrders() {
    return getOrdersCountByStatus('delivered');
}

// Lấy đơn hàng đã hủy
function getCancelledOrders() {
    return getOrdersCountByStatus('cancelled');
}

// Tính tổng doanh thu
function getTotalRevenue() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT SUM(total_amount) as total 
            FROM orders 
            WHERE status IN ('delivered', 'shipped') AND is_paid = 1
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    } catch (PDOException $e) {
        error_log("Error calculating revenue: " . $e->getMessage());
        return 0;
    }
}

// Lấy đơn hàng gần đây (7 ngày qua)
function getRecentOrders($limit = 5) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT o.*, u.name as customer_name, u.email as customer_email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ORDER BY o.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting recent orders: " . $e->getMessage());
        return [];
    }
}
?> 