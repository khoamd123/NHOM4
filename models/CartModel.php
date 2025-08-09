<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CartModel {
    
    private $cart;
    
    public function __construct() {
        // Khởi tạo giỏ hàng từ session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->cart = &$_SESSION['cart'];
    }
    
    /**
     * Thêm sản phẩm vào giỏ hàng
     * @param array $product - Thông tin sản phẩm
     * @return bool
     */
    public function addItem($product) {
        try {
            $productId = $product['id'];
            $size = $product['size'] ?? null;
            
            // Tạo key duy nhất cho sản phẩm
            $cartKey = $productId . ($size ? '_' . $size : '');
            
            if (isset($this->cart[$cartKey])) {
                // Nếu sản phẩm đã có, tăng số lượng
                $this->cart[$cartKey]['quantity'] += $product['quantity'];
            } else {
                // Thêm sản phẩm mới
                $this->cart[$cartKey] = [
                    'product_id' => $productId,
                    'name' => $product['name'],
                    'price' => floatval($product['price']),
                    'image' => $product['image'],
                    'quantity' => intval($product['quantity']),
                    'size' => $size,
                    'added_at' => date('Y-m-d H:i:s')
                ];
            }
            
            // Cập nhật session
            $_SESSION['cart'] = $this->cart;
            return true;
        } catch (Exception $e) {
            error_log("CartModel::addItem Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật số lượng sản phẩm
     * @param string $cartKey
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity($cartKey, $quantity) {
        try {
            if (!isset($this->cart[$cartKey])) {
                return false;
            }
            
            $quantity = intval($quantity);
            
            if ($quantity <= 0) {
                // Xóa sản phẩm nếu số lượng <= 0
                unset($this->cart[$cartKey]);
            } else {
                // Cập nhật số lượng
                $this->cart[$cartKey]['quantity'] = $quantity;
            }
            
            $_SESSION['cart'] = $this->cart;
            return true;
        } catch (Exception $e) {
            error_log("CartModel::updateQuantity Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * @param string $cartKey
     * @return bool
     */
    public function removeItem($cartKey) {
        try {
            if (isset($this->cart[$cartKey])) {
                unset($this->cart[$cartKey]);
                $_SESSION['cart'] = $this->cart;
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("CartModel::removeItem Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy tất cả sản phẩm trong giỏ hàng
     * @return array
     */
    public function getItems() {
        return $this->cart;
    }
    
    /**
     * Lấy sản phẩm theo key
     * @param string $cartKey
     * @return array|null
     */
    public function getItem($cartKey) {
        return $this->cart[$cartKey] ?? null;
    }
    
    /**
     * Đếm tổng số sản phẩm trong giỏ hàng
     * @return int
     */
    public function getItemCount() {
        $count = 0;
        foreach ($this->cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
    
    /**
     * Đếm số loại sản phẩm khác nhau
     * @return int
     */
    public function getUniqueItemCount() {
        return count($this->cart);
    }
    
    /**
     * Tính tổng giá trị giỏ hàng
     * @return float
     */
    public function getTotal() {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    /**
     * Tính tổng giá trị với thuế (nếu có)
     * @param float $taxRate - Tỷ lệ thuế (ví dụ: 0.1 cho 10%)
     * @return array - ['subtotal', 'tax', 'total']
     */
    public function getTotalWithTax($taxRate = 0) {
        $subtotal = $this->getTotal();
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;
        
        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ];
    }
    
    /**
     * Kiểm tra giỏ hàng có trống không
     * @return bool
     */
    public function isEmpty() {
        return empty($this->cart);
    }
    
    /**
     * Xóa toàn bộ giỏ hàng
     * @return bool
     */
    public function clear() {
        try {
            $this->cart = [];
            $_SESSION['cart'] = [];
            return true;
        } catch (Exception $e) {
            error_log("CartModel::clear Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra sản phẩm có tồn tại trong giỏ hàng không
     * @param string $productId
     * @param string $size
     * @return bool
     */
    public function hasItem($productId, $size = null) {
        $cartKey = $productId . ($size ? '_' . $size : '');
        return isset($this->cart[$cartKey]);
    }
    
    /**
     * Lấy thông tin tóm tắt giỏ hàng
     * @return array
     */
    public function getSummary() {
        return [
            'item_count' => $this->getItemCount(),
            'unique_count' => $this->getUniqueItemCount(),
            'total' => $this->getTotal(),
            'is_empty' => $this->isEmpty(),
            'items' => $this->cart
        ];
    }
    
    /**
     * Validate dữ liệu sản phẩm trước khi thêm vào giỏ
     * @param array $product
     * @return array - ['valid' => bool, 'errors' => array]
     */
    public function validateProduct($product) {
        $errors = [];
        
        if (empty($product['id'])) {
            $errors[] = 'ID sản phẩm không được để trống';
        }
        
        if (empty($product['name'])) {
            $errors[] = 'Tên sản phẩm không được để trống';
        }
        
        if (!isset($product['price']) || $product['price'] <= 0) {
            $errors[] = 'Giá sản phẩm không hợp lệ';
        }
        
        if (!isset($product['quantity']) || $product['quantity'] <= 0) {
            $errors[] = 'Số lượng không hợp lệ';
        }
        
        if (empty($product['image'])) {
            $errors[] = 'Hình ảnh sản phẩm không được để trống';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Lưu giỏ hàng vào database (cho user đã đăng nhập)
     * @param int $userId
     * @return bool
     */
    public function saveToDatabase($userId) {
        // TODO: Implement save to database functionality
        // Chức năng này sẽ được implement sau khi có bảng cart trong database
        return true;
    }
    
    /**
     * Load giỏ hàng từ database (cho user đã đăng nhập)
     * @param int $userId
     * @return bool
     */
    public function loadFromDatabase($userId) {
        // TODO: Implement load from database functionality
        // Chức năng này sẽ được implement sau khi có bảng cart trong database
        return true;
    }
    
    /**
     * Merge giỏ hàng từ session với database
     * @param int $userId
     * @return bool
     */
    public function mergeWithDatabase($userId) {
        // TODO: Implement merge functionality
        // Chức năng này sẽ merge giỏ hàng từ session với database khi user login
        return true;
    }
}
?>
