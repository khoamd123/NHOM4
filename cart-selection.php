<?php
session_start();
require_once __DIR__ . '/models/CartModel.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

$userId = $_SESSION['user']['id'];
$action = $_POST['action'] ?? '';

try {
    // Debug logging
    error_log("Cart selection request - User: $userId, Action: $action, POST data: " . json_encode($_POST));
    
    switch ($action) {
        case 'toggle_selection':
            $variantId = $_POST['variant_id'] ?? 0;
            error_log("Toggle selection for variant: $variantId");
            if ($variantId) {
                $success = CartModel::toggleItemSelection($userId, $variantId);
                error_log("Toggle result: " . ($success ? 'success' : 'failed'));
                if ($success) {
                    // Lấy thông tin cập nhật
                    $selectedTotal = CartModel::getSelectedCartTotal($userId);
                    $selectedCount = CartModel::getSelectedCartCount($userId);
                    $cartTotal = CartModel::getCartTotal($userId);
                    $cartCount = CartModel::getCartCount($userId);
                    
                    error_log("Updated values - SelectedTotal: $selectedTotal, SelectedCount: $selectedCount, CartTotal: $cartTotal, CartCount: $cartCount");
                    
                    echo json_encode([
                        'success' => true,
                        'selectedTotal' => number_format($selectedTotal, 0, ',', '.') . '₫',
                        'selectedCount' => $selectedCount,
                        'cartTotal' => number_format($cartTotal, 0, ',', '.') . '₫',
                        'cartCount' => $cartCount
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
            }
            break;
            
        case 'select_all':
            $success = CartModel::selectAllItems($userId);
            if ($success) {
                $selectedTotal = CartModel::getSelectedCartTotal($userId);
                $selectedCount = CartModel::getSelectedCartCount($userId);
                
                echo json_encode([
                    'success' => true,
                    'selectedTotal' => number_format($selectedTotal, 0, ',', '.') . '₫',
                    'selectedCount' => $selectedCount,
                    'message' => 'Đã chọn tất cả sản phẩm'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi chọn tất cả']);
            }
            break;
            
        case 'deselect_all':
            $success = CartModel::deselectAllItems($userId);
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'selectedTotal' => '0₫',
                    'selectedCount' => 0,
                    'message' => 'Đã bỏ chọn tất cả sản phẩm'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi bỏ chọn tất cả']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
            break;
    }
} catch (Exception $e) {
    error_log("Cart selection error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>
