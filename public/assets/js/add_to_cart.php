<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng']);
    exit;
}

// Include database and CartModel
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../models/CartModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $variantId = $_POST['variant_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;
    $userId = $_SESSION['user']['id'];
    
    if (!$variantId) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
        exit;
    }
    
    try {
        // Validate quantity
        $quantity = max(1, intval($quantity));
        
        // Check if variant exists and has stock
        global $conn;
        $stmt = $conn->prepare("
            SELECT pv.*, p.name as product_name 
            FROM product_variants pv 
            JOIN products p ON pv.product_id = p.id 
            WHERE pv.id = ?
        ");
        $stmt->execute([$variantId]);
        $variant = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$variant) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }
        
        if ($variant['stock'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Không đủ hàng trong kho']);
            exit;
        }
        
        // Add to cart using CartModel
        $result = CartModel::addToCart($userId, $variantId, $quantity);
        
        if ($result) {
            // Get updated cart count
            $cartCount = CartModel::getCartCount($userId);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Đã thêm vào giỏ hàng',
                'cart_count' => $cartCount,
                'product_name' => $variant['product_name'],
                'size' => $variant['size'],
                'color' => $variant['color']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể thêm vào giỏ hàng']);
        }
        
    } catch (Exception $e) {
        error_log("Add to cart error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?> 