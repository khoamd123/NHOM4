<?php
require_once __DIR__ . '/../config/database.php';

class OrderModel {
    
    // Lấy danh sách đơn hàng theo user ID
    public static function getOrdersByUserId($userId) {
        global $conn;
        $query = "SELECT o.*, 
                         COUNT(oi.id) as total_items,
                         SUM(oi.quantity) as total_quantity
                  FROM orders o 
                  LEFT JOIN order_items oi ON o.id = oi.order_id 
                  WHERE o.user_id = ? 
                  GROUP BY o.id 
                  ORDER BY o.created_at DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy chi tiết đơn hàng
    public static function getOrderDetail($orderId, $userId) {
        global $conn;
        $query = "SELECT * FROM orders 
                  WHERE id = ? AND user_id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$orderId, $userId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Lấy items của đơn hàng
    public static function getOrderItems($orderId) {
        global $conn;
        $query = "SELECT oi.*, 
                         p.name as product_name, 
                         p.image as product_image,
                         p.brand,
                         pv.size, 
                         pv.color
                  FROM order_items oi
                  JOIN product_variants pv ON oi.variant_id = pv.id
                  JOIN products p ON pv.product_id = p.id
                  WHERE oi.order_id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$orderId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Hủy đơn hàng
    public static function cancelOrder($orderId, $userId) {
        global $conn;
        try {
            // Kiểm tra đơn hàng có thuộc về user không và có thể hủy không
            $query = "SELECT status FROM orders 
                      WHERE id = ? AND user_id = ?";
            
            $stmt = $conn->prepare($query);
            $stmt->execute([$orderId, $userId]);
            
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                return ['success' => false, 'message' => 'Không tìm thấy đơn hàng'];
            }
            
            if ($order['status'] !== 'pending') {
                return ['success' => false, 'message' => 'Không thể hủy đơn hàng này'];
            }
            
            // Cập nhật trạng thái đơn hàng
            $updateQuery = "UPDATE orders 
                           SET status = 'cancelled' 
                           WHERE id = ? AND user_id = ?";
            
            $updateStmt = $conn->prepare($updateQuery);
            
            if ($updateStmt->execute([$orderId, $userId])) {
                // Hoàn lại số lượng sản phẩm
                self::restoreStock($orderId);
                return ['success' => true, 'message' => 'Hủy đơn hàng thành công'];
            } else {
                return ['success' => false, 'message' => 'Lỗi khi hủy đơn hàng'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống'];
        }
    }
    
    // Hoàn lại số lượng sản phẩm khi hủy đơn
    private static function restoreStock($orderId) {
        global $conn;
        $query = "SELECT variant_id, quantity FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$orderId]);
        
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($items as $item) {
            $updateStock = "UPDATE product_variants 
                           SET stock = stock + ? 
                           WHERE id = ?";
            $updateStmt = $conn->prepare($updateStock);
            $updateStmt->execute([$item['quantity'], $item['variant_id']]);
        }
    }
    
    // Lấy trạng thái đơn hàng (cho việc hiển thị)
    public static function getStatusLabel($status) {
        $statusLabels = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy'
        ];
        
        return $statusLabels[$status] ?? $status;
    }
    
    // Lấy class CSS cho trạng thái
    public static function getStatusClass($status) {
        $statusClasses = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];
        
        return $statusClasses[$status] ?? 'secondary';
    }
} 