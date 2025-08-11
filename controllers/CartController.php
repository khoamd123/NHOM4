<?php
session_start();
require_once __DIR__ . '/../models/CartModel.php';

class CartController {
    
    // Kiểm tra user đã đăng nhập chưa
    private function checkLogin() {
        if (!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'message' => 'Bạn cần đăng nhập để sử dụng giỏ hàng',
                'redirect' => '/NHOM4_DU_AN_1/views/user/login.php'
            ]);
            exit;
        }
        return $_SESSION['user']['id'];
    }
    
    // Thêm sản phẩm vào giỏ hàng
    public function add() {
        $userId = $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($variantId <= 0 || $quantity <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Thông tin không hợp lệ']);
            exit;
        }
        
        // Kiểm tra variant có tồn tại và có stock không
        $variantInfo = CartModel::getVariantInfo($variantId);
        if (!$variantInfo) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }
        
        if ($variantInfo['stock'] < $quantity) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'message' => 'Số lượng trong kho không đủ. Còn lại: ' . $variantInfo['stock']
            ]);
            exit;
        }
        
        // Thêm vào giỏ hàng
        $result = CartModel::addToCart($userId, $variantId, $quantity);
        
        if ($result) {
            $cartCount = CartModel::getCartCount($userId);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Đã thêm vào giỏ hàng',
                'cart_count' => $cartCount
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
        exit;
    }
    
    // Hiển thị trang giỏ hàng
    public function view() {
        $userId = $this->checkLogin();
        
        $cartItems = CartModel::getCartItems($userId);
        $cartTotal = CartModel::getCartTotal($userId);
        $cartCount = CartModel::getCartCount($userId);
        
        // Kiểm tra sản phẩm có vấn đề (hết hàng, ngừng bán)
        $invalidItems = CartModel::validateCartItems($userId);
        
        include __DIR__ . '/../views/cart/cart.php';
    }
    
    // Cập nhật số lượng
    public function update() {
        $userId = $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
        
        if ($variantId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Thông tin không hợp lệ']);
            exit;
        }
        
        // Kiểm tra stock nếu quantity > 0
        if ($quantity > 0) {
            $variantInfo = CartModel::getVariantInfo($variantId);
            if (!$variantInfo || $variantInfo['stock'] < $quantity) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Số lượng trong kho không đủ'
                ]);
                exit;
            }
        }
        
        $result = CartModel::updateCartItem($userId, $variantId, $quantity);
        
        if ($result) {
            $cartCount = CartModel::getCartCount($userId);
            $cartTotal = CartModel::getCartTotal($userId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Cập nhật thành công',
                'cart_count' => $cartCount,
                'cart_total' => number_format($cartTotal, 0, ',', '.')
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
        exit;
    }
    
    // Xóa sản phẩm khỏi giỏ
    public function remove() {
        $userId = $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
        
        if ($variantId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Thông tin không hợp lệ']);
            exit;
        }
        
        $result = CartModel::removeFromCart($userId, $variantId);
        
        if ($result) {
            $cartCount = CartModel::getCartCount($userId);
            $cartTotal = CartModel::getCartTotal($userId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Đã xóa khỏi giỏ hàng',
                'cart_count' => $cartCount,
                'cart_total' => number_format($cartTotal, 0, ',', '.')
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
        exit;
    }
    
    // Xóa toàn bộ giỏ hàng
    public function clear() {
        $userId = $this->checkLogin();
        
        $result = CartModel::clearCart($userId);
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Đã xóa toàn bộ giỏ hàng',
                'cart_count' => 0,
                'cart_total' => '0'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
        exit;
    }
    
    // Lấy số lượng giỏ hàng (AJAX)
    public function count() {
        if (!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode(['cart_count' => 0]);
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $cartCount = CartModel::getCartCount($userId);
        
        header('Content-Type: application/json');
        echo json_encode(['cart_count' => $cartCount]);
        exit;
    }
    
    // Mini cart content (AJAX)
    public function mini() {
        if (!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode(['items' => [], 'total' => 0, 'count' => 0]);
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $cartItems = CartModel::getCartItems($userId);
        $cartTotal = CartModel::getCartTotal($userId);
        $cartCount = CartModel::getCartCount($userId);
        
        // Chỉ lấy 5 sản phẩm gần nhất
        $miniItems = array_slice($cartItems, 0, 5);
        
        header('Content-Type: application/json');
        echo json_encode([
            'items' => $miniItems,
            'total' => $cartTotal,
            'count' => $cartCount,
            'formatted_total' => number_format($cartTotal, 0, ',', '.')
        ]);
        exit;
    }
}

// Xử lý routing cho cart
if (isset($_GET['action'])) {
    $controller = new CartController();
    $action = $_GET['action'];
    
    switch ($action) {
        case 'add':
            $controller->add();
            break;
        case 'view':
            $controller->view();
            break;
        case 'update':
            $controller->update();
            break;
        case 'remove':
            $controller->remove();
            break;
        case 'clear':
            $controller->clear();
            break;
        case 'count':
            $controller->count();
            break;
        case 'mini':
            $controller->mini();
            break;
        default:
            $controller->view();
            break;
    }
} else {
    $controller = new CartController();
    $controller->view();
}
?>


