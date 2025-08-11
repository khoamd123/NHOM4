<?php
require_once __DIR__ . '/../config/database.php';

class ProductModel {
    
    // Lấy tất cả sản phẩm
    public static function getAllProducts() {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT p.*, GROUP_CONCAT(c.name) as category_names
                FROM products p
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                GROUP BY p.id
                ORDER BY p.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting products: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy sản phẩm theo ID
    public static function getProductById($id) {
        global $conn;
        try {
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product by ID: " . $e->getMessage());
            return null;
        }
    }
    
    // Lấy tất cả danh mục
    public static function getAllCategories() {
        global $conn;
        try {
            $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy sản phẩm theo danh mục
    public static function getProductsByCategory($categoryName) {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT p.*, GROUP_CONCAT(c.name) as category_names
                FROM products p
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                WHERE c.name = ?
                GROUP BY p.id
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$categoryName]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting products by category: " . $e->getMessage());
            return [];
        }
    }
    
    // Tìm kiếm sản phẩm
    public static function searchProducts($query) {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT p.*, GROUP_CONCAT(c.name) as category_names
                FROM products p
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                WHERE p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?
                GROUP BY p.id
                ORDER BY p.created_at DESC
            ");
            $searchTerm = "%$query%";
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching products: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy biến thể của sản phẩm
    public static function getProductVariants($productId) {
        global $conn;
        try {
            $stmt = $conn->prepare("SELECT * FROM product_variants WHERE product_id = ? ORDER BY size, color");
            $stmt->execute([$productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product variants: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy ảnh đại diện cho danh mục (lấy ảnh từ sản phẩm đầu tiên của danh mục)
    public static function getCategoryImage($categoryName) {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT p.image 
                FROM products p
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                WHERE c.name = ? AND p.image IS NOT NULL AND p.image != ''
                ORDER BY p.created_at DESC
                LIMIT 1
            ");
            $stmt->execute([$categoryName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['image'] : null;
        } catch (PDOException $e) {
            error_log("Error getting category image: " . $e->getMessage());
            return null;
        }
    }
    
    // Lấy số lượng sản phẩm trong mỗi danh mục
    public static function getCategoryProductCount($categoryName) {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT COUNT(DISTINCT p.id) as product_count
                FROM products p
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                WHERE c.name = ?
            ");
            $stmt->execute([$categoryName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['product_count'] : 0;
        } catch (PDOException $e) {
            error_log("Error getting category product count: " . $e->getMessage());
            return 0;
        }
    }
    
    // Lấy tất cả màu sắc có sẵn
    public static function getAllColors() {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT DISTINCT color 
                FROM product_variants 
                WHERE color IS NOT NULL AND color != ''
                ORDER BY color
            ");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Error getting colors: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy tất cả size có sẵn
    public static function getAllSizes() {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT DISTINCT size 
                FROM product_variants 
                WHERE size IS NOT NULL AND size != ''
                ORDER BY CAST(size AS UNSIGNED)
            ");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Error getting sizes: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy tất cả giới tính có sẵn  
    public static function getAllGenders() {
        global $conn;
        try {
            $stmt = $conn->prepare("
                SELECT DISTINCT gender 
                FROM products 
                WHERE gender IS NOT NULL AND gender != ''
                ORDER BY gender
            ");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Error getting genders: " . $e->getMessage());
            return [];
        }
    }
    
    // Lọc sản phẩm theo màu, size, giới tính
    public static function getFilteredProducts($filters = []) {
        global $conn;
        try {
            $query = "
                SELECT DISTINCT p.*, GROUP_CONCAT(DISTINCT c.name) as category_names
                FROM products p
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                LEFT JOIN product_variants pv ON p.id = pv.product_id
                WHERE 1=1
            ";
            
            $params = [];
            
            // Filter theo danh mục (giữ lại filter cũ)
            if (!empty($filters['category'])) {
                $query .= " AND c.name = ?";
                $params[] = $filters['category'];
            }
            
            // Filter theo màu sắc
            if (!empty($filters['colors']) && is_array($filters['colors'])) {
                $placeholders = str_repeat('?,', count($filters['colors']) - 1) . '?';
                $query .= " AND pv.color IN ($placeholders)";
                $params = array_merge($params, $filters['colors']);
            }
            
            // Filter theo size
            if (!empty($filters['sizes']) && is_array($filters['sizes'])) {
                $placeholders = str_repeat('?,', count($filters['sizes']) - 1) . '?';
                $query .= " AND pv.size IN ($placeholders)";
                $params = array_merge($params, $filters['sizes']);
            }
            
            // Filter theo giới tính
            if (!empty($filters['genders']) && is_array($filters['genders'])) {
                $placeholders = str_repeat('?,', count($filters['genders']) - 1) . '?';
                $query .= " AND p.gender IN ($placeholders)";
                $params = array_merge($params, $filters['genders']);
            }
            
            // Search query (giữ lại)
            if (!empty($filters['search'])) {
                $query .= " AND (p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $query .= " GROUP BY p.id ORDER BY p.created_at DESC";
            
            $stmt = $conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error filtering products: " . $e->getMessage());
            return [];
        }
    }

    // Lấy giá hiện tại của sản phẩm (từ variants hoặc base price)
    public static function getProductPrice($productId) {
        global $conn;
        
        try {
            // Kiểm tra xem có variants không
            $stmt = $conn->prepare("
                SELECT MIN(price) as min_price, MAX(price) as max_price, AVG(price) as avg_price
                FROM product_variants 
                WHERE product_id = ? AND stock > 0
            ");
            $stmt->execute([$productId]);
            $variants = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($variants && $variants['min_price'] !== null) {
                // Nếu có variants, trả về giá thấp nhất
                return $variants['min_price'];
            } else {
                // Nếu không có variants, trả về giá base
                $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                return $product ? $product['price'] : 0;
            }
        } catch (PDOException $e) {
            error_log("Error getting product price: " . $e->getMessage());
            return 0;
        }
    }

    // Lấy range giá cho sản phẩm (nếu có variants)
    public static function getProductPriceRange($productId) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("
                SELECT MIN(price) as min_price, MAX(price) as max_price
                FROM product_variants 
                WHERE product_id = ? AND stock > 0
            ");
            $stmt->execute([$productId]);
            $variants = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($variants && $variants['min_price'] !== null) {
                if ($variants['min_price'] == $variants['max_price']) {
                    return number_format($variants['min_price'], 0, ',', '.') . '₫';
                } else {
                    return number_format($variants['min_price'], 0, ',', '.') . '₫ - ' . number_format($variants['max_price'], 0, ',', '.') . '₫';
                }
            } else {
                // Fallback về giá base
                $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                return $product ? number_format($product['price'], 0, ',', '.') . '₫' : '0₫';
            }
        } catch (PDOException $e) {
            error_log("Error getting product price range: " . $e->getMessage());
            return '0₫';
        }
    }
}
?> 