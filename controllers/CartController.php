<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CartController {
    
    public function __construct() {
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart($productId, $name, $price, $image, $quantity = 1, $size = null) {
        $cart = &$_SESSION['cart'];
        
        // Tạo key duy nhất cho sản phẩm (bao gồm size nếu có)
        $cartKey = $productId . ($size ? '_' . $size : '');
        
        if (isset($cart[$cartKey])) {
            // Nếu sản phẩm đã có trong giỏ, tăng số lượng
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            // Thêm sản phẩm mới vào giỏ
            $cart[$cartKey] = [
                'product_id' => $productId,
                'name' => $name,
                'price' => $price,
                'image' => $image,
                'quantity' => $quantity,
                'size' => $size
            ];
        }
        
        return $this->getCartCount();
    }
    
    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCart($cartKey, $quantity) {
        if (isset($_SESSION['cart'][$cartKey])) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$cartKey]);
            } else {
                $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
            }
            return true;
        }
        return false;
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart($cartKey) {
        if (isset($_SESSION['cart'][$cartKey])) {
            unset($_SESSION['cart'][$cartKey]);
            return true;
        }
        return false;
    }
    
    /**
     * Lấy toàn bộ giỏ hàng
     */
    public function getCart() {
        return $_SESSION['cart'] ?? [];
    }
    
    /**
     * Đếm tổng số sản phẩm trong giỏ hàng
     */
    public function getCartCount() {
        $count = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
    
    /**
     * Tính tổng giá trị giỏ hàng
     */
    public function getCartTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clearCart() {
        $_SESSION['cart'] = [];
    }
    
    /**
     * Kiểm tra giỏ hàng có trống không
     */
    public function isEmpty() {
        return empty($_SESSION['cart']);
    }
}

// Xử lý AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $cartController = new CartController();
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $productId = $_POST['product_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $image = $_POST['image'] ?? '';
            $quantity = intval($_POST['quantity'] ?? 1);
            $size = $_POST['size'] ?? null;
            
            if ($productId && $name && $price > 0) {
                $cartCount = $cartController->addToCart($productId, $name, $price, $image, $quantity, $size);
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                    'cart_count' => $cartCount
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Thông tin sản phẩm không hợp lệ'
                ]);
            }
            break;
            
        case 'update':
            $cartKey = $_POST['product_id'] ?? '';
            $quantity = intval($_POST['quantity'] ?? 0);
            
            if ($cartController->updateCart($cartKey, $quantity)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã cập nhật giỏ hàng',
                    'cart_count' => $cartController->getCartCount(),
                    'cart_total' => $cartController->getCartTotal()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể cập nhật giỏ hàng'
                ]);
            }
            break;
            
        case 'remove':
            $cartKey = $_POST['product_id'] ?? '';
            
            if ($cartController->removeFromCart($cartKey)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                    'cart_count' => $cartController->getCartCount()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể xóa sản phẩm'
                ]);
            }
            break;
            
        case 'clear':
            $cartController->clearCart();
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng',
                'cart_count' => 0
            ]);
            break;
            
        case 'get_count':
            echo json_encode([
                'success' => true,
                'cart_count' => $cartController->getCartCount()
            ]);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Hành động không hợp lệ'
            ]);
            break;
    }
    exit;
}
?>
