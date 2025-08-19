<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CartModel.php';

class CheckoutController {
    
    // Auto-fix database structure
    private function ensureOrdersTableStructure() {
        global $conn;
        
        try {
            // Check if shipping_info column exists
            $stmt = $conn->query("SHOW COLUMNS FROM orders LIKE 'shipping_info'");
            $hasShippingInfo = $stmt->rowCount() > 0;
            
            // Add missing column
            if (!$hasShippingInfo) {
                $conn->exec("ALTER TABLE orders ADD COLUMN shipping_info JSON DEFAULT NULL COMMENT 'Thông tin giao hàng (JSON format)'");
            }
            
        } catch (Exception $e) {
            // Continue anyway - the error will be caught later
        }
    }
    
    private function checkLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'message' => 'Vui lòng đăng nhập để tiếp tục',
                'redirect' => '/NHOM4_DU_AN_1/views/user/login.php'
            ]);
            exit;
        }
        
        return $_SESSION['user']['id'];
    }
    
    // Static flag để prevent multiple executions
    private static $viewExecuted = false;
    
    // Hiển thị trang checkout
    public function view() {
        // Prevent multiple executions
        if (self::$viewExecuted) {
            error_log("WARNING: CheckoutController->view() already executed, skipping duplicate call");
            return;
        }
        self::$viewExecuted = true;
        
        $userId = $this->checkLogin();
        
        // Lấy thông tin giỏ hàng (chỉ những sản phẩm được chọn)
        $cartItems = CartModel::getSelectedCartItems($userId);
        $cartTotal = CartModel::getSelectedCartTotal($userId);
        $cartCount = CartModel::getSelectedCartCount($userId);
        
        // Kiểm tra giỏ hàng trống
        if ($cartCount == 0) {
            header("Location: /NHOM4_DU_AN_1/index.php?page=cart");
            exit;
        }
        
        // Validate cart items (kiểm tra stock)
        $invalidItems = CartModel::validateCartItems($userId);
        if (!empty($invalidItems)) {
            // Có sản phẩm hết hàng, chuyển về cart để xử lý
            header("Location: /NHOM4_DU_AN_1/index.php?page=cart&error=invalid_items");
            exit;
        }
        
        // Tính phí ship (có thể customize sau)
        $shippingFee = $this->calculateShippingFee($cartTotal);
        $finalTotal = $cartTotal + $shippingFee;
        
        // Lấy địa chỉ đã lưu của user (nếu có)
        $savedAddresses = $this->getUserAddresses($userId);
        
        // Include checkout view
        $data = [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'shippingFee' => $shippingFee,
            'finalTotal' => $finalTotal,
            'savedAddresses' => $savedAddresses,
            'user' => $_SESSION['user']
        ];
        
        // Đổi lại về file chính
        include __DIR__ . '/../views/checkout/checkout.php';
    }
    
    // Xử lý đặt hàng
    public function placeOrder() {
        $userId = $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /NHOM4_DU_AN_1/index.php?page=checkout");
            exit;
        }
        
        try {
            global $conn;
            $conn->beginTransaction();
            
            // Ensure database structure is correct
            $this->ensureOrdersTableStructure();
            
            // Validate input
            $shippingInfo = [
                'fullname' => trim($_POST['fullname'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? '')
            ];
            
            $paymentMethod = $_POST['payment_method'] ?? 'cod';
            
            // Validate required fields với thông báo cụ thể
            $missingFields = [];
            if (empty($shippingInfo['fullname'])) {
                $missingFields[] = 'Họ và tên';
            }
            if (empty($shippingInfo['phone'])) {
                $missingFields[] = 'Số điện thoại';
            }
            if (empty($shippingInfo['address'])) {
                $missingFields[] = 'Địa chỉ giao hàng';
            }
            
            if (!empty($missingFields)) {
                $errorMessage = 'Thiếu thông tin: ' . implode(', ', $missingFields);
                header("Location: /NHOM4_DU_AN_1/index.php?page=checkout&error=" . urlencode($errorMessage));
                exit;
            }
            
            // Lấy giỏ hàng (chỉ những sản phẩm được chọn)
            $cartItems = CartModel::getSelectedCartItems($userId);
            if (empty($cartItems)) {
                throw new Exception('Không có sản phẩm nào được chọn');
            }
            
            // Validate stock một lần nữa
            $invalidItems = CartModel::validateCartItems($userId);
            if (!empty($invalidItems)) {
                throw new Exception('Một số sản phẩm đã hết hàng');
            }
            
            // Tính toán
            $cartTotal = CartModel::getSelectedCartTotal($userId);
            $shippingFee = $this->calculateShippingFee($cartTotal);
            $finalTotal = $cartTotal + $shippingFee;
            
            // Tạo đơn hàng
            $orderData = [
                'user_id' => $userId,
                'total_amount' => $finalTotal,
                'shipping_fee' => $shippingFee,
                'status' => 'pending',
                'is_paid' => 0,
                'shipping_info' => json_encode($shippingInfo)
            ];
            
            $orderId = $this->createOrder($orderData);
            
            // Tạo order items
            foreach ($cartItems as $item) {
                $this->createOrderItem([
                    'order_id' => $orderId,
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                
                // Cập nhật stock
                $this->updateStock($item['variant_id'], $item['quantity']);
            }
            
            // Tạo payment record
            $this->createPayment([
                'order_id' => $orderId,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'pending'
            ]);
            
            // Xóa những sản phẩm đã được chọn khỏi giỏ hàng
            CartModel::removeSelectedItems($userId);
            
            $conn->commit();
            
            // Redirect theo payment method
            if ($paymentMethod === 'cod') {
                header("Location: /NHOM4_DU_AN_1/index.php?page=checkout&action=success&order_id=" . $orderId);
            } else {
                // Redirect to payment gateway (implement later)
                header("Location: /NHOM4_DU_AN_1/index.php?page=checkout&action=payment&order_id=" . $orderId . "&method=" . $paymentMethod);
            }
            exit;
            
        } catch (Exception $e) {
            $conn->rollback();
            header("Location: /NHOM4_DU_AN_1/index.php?page=checkout&error=" . urlencode($e->getMessage()));
            exit;
        }
    }
    
    // Trang thành công
    public function success() {
        $userId = $this->checkLogin();
        
        $orderId = $_GET['order_id'] ?? 0;
        if (!$orderId) {
            header("Location: /NHOM4_DU_AN_1/index.php");
            exit;
        }
        
        // Lấy thông tin đơn hàng
        $order = $this->getOrderById($orderId);
        if (!$order || $order['user_id'] != $userId) {
            header("Location: /NHOM4_DU_AN_1/index.php");
            exit;
        }
        
        $orderItems = $this->getOrderItems($orderId);
        
        include __DIR__ . '/../views/checkout/order-success.php';
    }
    
    // Helper functions
    private function calculateShippingFee($cartTotal) {
        // Logic tính phí ship - có thể customize
        if ($cartTotal >= 1000000) { // Miễn phí ship cho đơn >= 1 triệu
            return 0;
        } elseif ($cartTotal >= 500000) { // Giảm 50% phí ship cho đơn >= 500k
            return 15000;
        } else {
            return 30000; // Phí ship chuẩn
        }
    }
    
    private function getUserAddresses($userId) {
        global $conn;
        try {
            $stmt = $conn->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting addresses: " . $e->getMessage());
            return [];
        }
    }
    
    private function createOrder($data) {
        global $conn;
        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, total_amount, shipping_fee, status, is_paid, shipping_info, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['user_id'],
            $data['total_amount'],
            $data['shipping_fee'],
            $data['status'],
            $data['is_paid'],
            $data['shipping_info']
        ]);
        return $conn->lastInsertId();
    }
    
    private function createOrderItem($data) {
        global $conn;
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, variant_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['order_id'],
            $data['variant_id'],
            $data['quantity'],
            $data['price']
        ]);
    }
    
    private function createPayment($data) {
        global $conn;
        $stmt = $conn->prepare("
            INSERT INTO payments (order_id, payment_method, payment_status) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([
            $data['order_id'],
            $data['payment_method'],
            $data['payment_status']
        ]);
    }
    
    private function updateStock($variantId, $quantity) {
        global $conn;
        $stmt = $conn->prepare("UPDATE product_variants SET stock = stock - ? WHERE id = ?");
        return $stmt->execute([$quantity, $variantId]);
    }
    
    private function getOrderById($orderId) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT o.*, u.name as customer_name, u.email as customer_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function getOrderItems($orderId) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT oi.*, p.name as product_name, p.image, p.brand, pv.size, pv.color 
            FROM order_items oi 
            JOIN product_variants pv ON oi.variant_id = pv.id 
            JOIN products p ON pv.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Xử lý đường dẫn ảnh cho từng item
        foreach ($items as &$item) {
            if (!empty($item['image'])) {
                // Kiểm tra xem ảnh đã có đường dẫn đầy đủ chưa
                if (strpos($item['image'], '/NHOM4_DU_AN_1/public/uploads/products/') === 0) {
                    // Đã có đường dẫn đầy đủ, giữ nguyên
                } elseif (strpos($item['image'], '/NHOM4_DU_AN_1/') === 0) {
                    // Có đường dẫn gốc nhưng không đúng format, sửa lại
                    $item['image'] = '/NHOM4_DU_AN_1/public/uploads/products/' . basename($item['image']);
                } else {
                    // Chỉ có tên file, thêm đường dẫn đầy đủ
                    $item['image'] = '/NHOM4_DU_AN_1/public/uploads/products/' . $item['image'];
                }
                
                // Kiểm tra và sửa đường dẫn bị duplicate
                if (strpos($item['image'], '/NHOM4_DU_AN_1/public/uploads/products//NHOM4_DU_AN_1/') === 0) {
                    $item['image'] = '/NHOM4_DU_AN_1/public/uploads/products/' . basename($item['image']);
                }
            } else {
                // Ảnh mặc định
                $item['image'] = '/NHOM4_DU_AN_1/public/assets/images/featured-01.png';
            }
        }
        
        return $items;
    }
}

// Routing cho checkout
if (isset($_GET['action'])) {
    $controller = new CheckoutController();
    $action = $_GET['action'];
    
    switch ($action) {
        case 'place-order':
            $controller->placeOrder();
            break;
        case 'success':
            $controller->success();
            break;
        default:
            $controller->view();
            break;
    }
} else {
    $controller = new CheckoutController();
    $controller->view();
}
?>


