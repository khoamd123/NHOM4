<?php
require_once __DIR__ . '/../config/database.php';

class CartModel {
    
    // Thêm sản phẩm vào giỏ hàng
    public static function addToCart($userId, $variantId, $quantity = 1) {
        global $conn;
        
        try {
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND variant_id = ?");
            $stmt->execute([$userId, $variantId]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existingItem) {
                // Nếu đã có, cập nhật số lượng
                $newQuantity = $existingItem['quantity'] + $quantity;
                $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND variant_id = ?");
                return $stmt->execute([$newQuantity, $userId, $variantId]);
            } else {
                // Nếu chưa có, thêm mới
                $stmt = $conn->prepare("INSERT INTO cart (user_id, variant_id, quantity) VALUES (?, ?, ?)");
                return $stmt->execute([$userId, $variantId, $quantity]);
            }
        } catch (PDOException $e) {
            error_log("Error adding to cart: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy tất cả sản phẩm trong giỏ hàng của user
    public static function getCartItems($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("
                SELECT 
                    c.*,
                    p.name as product_name,
                    p.image as product_image,
                    p.image as image,
                    p.brand,
                    pv.size,
                    pv.color,
                    pv.price,
                    pv.stock,
                    (c.quantity * pv.price) as total_price
                FROM cart c
                JOIN product_variants pv ON c.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE c.user_id = ?
                ORDER BY c.id DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting cart items: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy chỉ những sản phẩm được chọn trong giỏ hàng
    public static function getSelectedCartItems($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("
                SELECT 
                    c.*,
                    p.name as product_name,
                    p.image as product_image,
                    p.image as image,
                    p.brand,
                    pv.size,
                    pv.color,
                    pv.price,
                    pv.stock,
                    (c.quantity * pv.price) as total_price
                FROM cart c
                JOIN product_variants pv ON c.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE c.user_id = ? AND c.is_selected = 1
                ORDER BY c.id DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting selected cart items: " . $e->getMessage());
            return [];
        }
    }
    
    // Cập nhật số lượng sản phẩm trong giỏ
    public static function updateCartItem($userId, $variantId, $quantity) {
        global $conn;
        
        try {
            if ($quantity <= 0) {
                // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ
                return self::removeFromCart($userId, $variantId);
            }
            
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND variant_id = ?");
            return $stmt->execute([$quantity, $userId, $variantId]);
        } catch (PDOException $e) {
            error_log("Error updating cart item: " . $e->getMessage());
            return false;
        }
    }
    
    // Xóa sản phẩm khỏi giỏ hàng
    public static function removeFromCart($userId, $variantId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND variant_id = ?");
            return $stmt->execute([$userId, $variantId]);
        } catch (PDOException $e) {
            error_log("Error removing from cart: " . $e->getMessage());
            return false;
        }
    }
    
    // Xóa toàn bộ giỏ hàng của user
    public static function clearCart($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Error clearing cart: " . $e->getMessage());
            return false;
        }
    }
    
    // Đếm số lượng sản phẩm trong giỏ hàng
    public static function getCartCount($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error getting cart count: " . $e->getMessage());
            return 0;
        }
    }
    
    // Tính tổng tiền giỏ hàng
    public static function getCartTotal($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("
                SELECT SUM(c.quantity * pv.price) as total
                FROM cart c
                JOIN product_variants pv ON c.variant_id = pv.id
                WHERE c.user_id = ?
            ");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error getting cart total: " . $e->getMessage());
            return 0;
        }
    }
    
    // Kiểm tra tính khả dụng của sản phẩm trong giỏ
    public static function validateCartItems($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("
                SELECT 
                    c.*,
                    p.name as product_name,
                    pv.stock,
                    pv.size,
                    pv.color
                FROM cart c
                JOIN product_variants pv ON c.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE c.user_id = ? AND (pv.stock < c.quantity OR p.status = 0)
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error validating cart: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy thông tin variant cho việc thêm vào giỏ
    public static function getVariantInfo($variantId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("
                SELECT 
                    pv.*,
                    p.name as product_name,
                    p.image as product_image,
                    p.brand
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                WHERE pv.id = ? AND p.status = 1
            ");
            $stmt->execute([$variantId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting variant info: " . $e->getMessage());
            return null;
        }
    }
    
    // Chuyển đổi trạng thái chọn sản phẩm
    public static function toggleItemSelection($userId, $variantId) {
        global $conn;
        
        try {
            error_log("CartModel::toggleItemSelection - User: $userId, Variant: $variantId");
            
            // Kiểm tra xem cột is_selected có tồn tại không
            $checkColumn = $conn->query("SHOW COLUMNS FROM cart LIKE 'is_selected'");
            if ($checkColumn->rowCount() == 0) {
                error_log("Column 'is_selected' does not exist in cart table");
                return false;
            }
            
            $stmt = $conn->prepare("
                UPDATE cart 
                SET is_selected = CASE WHEN is_selected = 1 THEN 0 ELSE 1 END
                WHERE user_id = ? AND variant_id = ?
            ");
            $result = $stmt->execute([$userId, $variantId]);
            error_log("Toggle execution result: " . ($result ? 'success' : 'failed') . ", Rows affected: " . $stmt->rowCount());
            return $result;
        } catch (PDOException $e) {
            error_log("Error toggling item selection: " . $e->getMessage());
            return false;
        }
    }
    
    // Chọn tất cả sản phẩm
    public static function selectAllItems($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("UPDATE cart SET is_selected = 1 WHERE user_id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Error selecting all items: " . $e->getMessage());
            return false;
        }
    }
    
    // Bỏ chọn tất cả sản phẩm
    public static function deselectAllItems($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("UPDATE cart SET is_selected = 0 WHERE user_id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Error deselecting all items: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy tổng tiền chỉ những sản phẩm được chọn
    public static function getSelectedCartTotal($userId) {
        global $conn;
        
        try {
            error_log("CartModel::getSelectedCartTotal - User: $userId");
            
            $stmt = $conn->prepare("
                SELECT SUM(c.quantity * pv.price) as total
                FROM cart c
                JOIN product_variants pv ON c.variant_id = pv.id
                WHERE c.user_id = ? AND c.is_selected = 1
            ");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $result['total'] ?? 0;
            error_log("Selected cart total: $total");
            return $total;
        } catch (PDOException $e) {
            error_log("Error getting selected cart total: " . $e->getMessage());
            return 0;
        }
    }
    
    // Đếm số lượng sản phẩm được chọn
    public static function getSelectedCartCount($userId) {
        global $conn;
        
        try {
            error_log("CartModel::getSelectedCartCount - User: $userId");
            
            $stmt = $conn->prepare("
                SELECT SUM(quantity) as total 
                FROM cart 
                WHERE user_id = ? AND is_selected = 1
            ");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $result['total'] ?? 0;
            error_log("Selected cart count: $count");
            return $count;
        } catch (PDOException $e) {
            error_log("Error getting selected cart count: " . $e->getMessage());
            return 0;
        }
    }
    
    // Xóa những sản phẩm đã được chọn (sau khi thanh toán)
    public static function removeSelectedItems($userId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND is_selected = 1");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Error removing selected items: " . $e->getMessage());
            return false;
        }
    }
}
?>


