<?php
require_once __DIR__ . '/db.php';

// Lấy tổng số người dùng
function getTotalUsers() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting total users: " . $e->getMessage());
        return 0;
    }
}

// Lấy tổng số sản phẩm
function getTotalProducts() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM products");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting total products: " . $e->getMessage());
        return 0;
    }
}

// Lấy tổng số đơn hàng
function getTotalOrders() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM orders");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting total orders: " . $e->getMessage());
        return 0;
    }
}

// Lấy tổng doanh thu tháng hiện tại
function getMonthlyRevenue() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT COALESCE(SUM(total_amount), 0) as total 
            FROM orders 
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'cancelled'
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting monthly revenue: " . $e->getMessage());
        return 0;
    }
}

// Lấy tổng doanh thu năm
function getYearlyRevenue() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT COALESCE(SUM(total_amount), 0) as total 
            FROM orders 
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'cancelled'
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting yearly revenue: " . $e->getMessage());
        return 0;
    }
}

// Lấy đơn hàng chờ xử lý
function getPendingOrders() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting pending orders: " . $e->getMessage());
        return 0;
    }
}

// Lấy sản phẩm hết hàng
function getOutOfStockProducts() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT COUNT(*) as total 
            FROM product_variants 
            WHERE stock = 0 OR stock IS NULL
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting out of stock products: " . $e->getMessage());
        return 0;
    }
}

// Lấy người dùng mới trong tháng
function getNewUsersThisMonth() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT COUNT(*) as total 
            FROM users 
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(created_at) = YEAR(CURRENT_DATE())
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Error getting new users: " . $e->getMessage());
        return 0;
    }
}

// Lấy hoạt động gần đây
function getRecentActivities() {
    global $conn;
    try {
        $activities = [];
        
        // Lấy user mới nhất
        $stmt = $conn->query("
            SELECT name, email, created_at 
            FROM users 
            ORDER BY created_at DESC 
            LIMIT 3
        ");
        $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'message' => "Người dùng mới đăng ký: {$user['name']} ({$user['email']})",
                'time' => $user['created_at']
            ];
        }
        
        // Lấy đơn hàng mới nhất
        $stmt = $conn->query("
            SELECT id, total_amount, created_at 
            FROM orders 
            ORDER BY created_at DESC 
            LIMIT 3
        ");
        $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($recentOrders as $order) {
            $activities[] = [
                'type' => 'order',
                'message' => "Đơn hàng mới #{$order['id']} - $" . number_format($order['total_amount'], 2),
                'time' => $order['created_at']
            ];
        }
        
        // Sắp xếp theo thời gian
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($activities, 0, 5); // Chỉ lấy 5 hoạt động gần nhất
        
    } catch (PDOException $e) {
        error_log("Error getting recent activities: " . $e->getMessage());
        return [];
    }
}

// Lấy thống kê theo tháng (cho biểu đồ)
function getMonthlyStats() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT 
                MONTH(created_at) as month,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue
            FROM orders 
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'cancelled'
            GROUP BY MONTH(created_at)
            ORDER BY month
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting monthly stats: " . $e->getMessage());
        return [];
    }
}

// Lấy thống kê doanh thu theo tháng (cho biểu đồ)
function getRevenueChartData() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total_amount) as revenue
            FROM orders 
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'cancelled'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting revenue chart data: " . $e->getMessage());
        return [];
    }
}

// Lấy thống kê đơn hàng theo tháng (cho biểu đồ)
function getOrdersChartData() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as orders
            FROM orders 
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting orders chart data: " . $e->getMessage());
        return [];
    }
}

// Lấy thống kê người dùng mới theo tháng (cho biểu đồ)
function getUsersChartData() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as new_users
            FROM users 
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting users chart data: " . $e->getMessage());
        return [];
    }
}

// Lấy thống kê trạng thái đơn hàng (cho biểu đồ tròn)
function getOrderStatusData() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT 
                status,
                COUNT(*) as count
            FROM orders 
            GROUP BY status
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting order status data: " . $e->getMessage());
        return [];
    }
}

// Lấy top sản phẩm bán chạy
function getTopProducts() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT 
                p.name,
                COUNT(oi.id) as sold_count,
                SUM(oi.quantity) as total_quantity
            FROM products p
            LEFT JOIN product_variants pv ON p.id = pv.product_id
            LEFT JOIN order_items oi ON pv.id = oi.variant_id
            LEFT JOIN orders o ON oi.order_id = o.id
            WHERE o.status != 'cancelled' OR o.status IS NULL
            GROUP BY p.id, p.name
            ORDER BY total_quantity DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting top products: " . $e->getMessage());
        return [];
    }
}
?> 