-- Cập nhật bảng orders để hỗ trợ checkout
ALTER TABLE `orders` 
ADD COLUMN `shipping_info` JSON NULL COMMENT 'Thông tin giao hàng (fullname, phone, address)' AFTER `shipping_fee`,
ADD COLUMN `notes` TEXT NULL COMMENT 'Ghi chú đơn hàng' AFTER `shipping_info`;

-- Tạo index cho hiệu suất
ALTER TABLE `orders` ADD INDEX `idx_user_created` (`user_id`, `created_at`);
ALTER TABLE `orders` ADD INDEX `idx_status` (`status`);

-- Cập nhật bảng payments để có thêm thông tin
ALTER TABLE `payments` 
ADD COLUMN `amount` DECIMAL(10,2) NULL COMMENT 'Số tiền thanh toán' AFTER `payment_method`,
ADD COLUMN `gateway_response` TEXT NULL COMMENT 'Response từ payment gateway' AFTER `transaction_code`;

-- Tạo bảng shipping_methods (tùy chọn, có thể dùng sau)
CREATE TABLE IF NOT EXISTS `shipping_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `min_order_value` decimal(10,2) DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default shipping methods
INSERT INTO `shipping_methods` (`name`, `description`, `fee`, `min_order_value`, `is_active`) VALUES
('Giao hàng tiêu chuẩn', 'Giao hàng trong 3-5 ngày', 30000.00, 0.00, 1),
('Giao hàng nhanh', 'Giao hàng trong 1-2 ngày', 50000.00, 0.00, 1),
('Miễn phí giao hàng', 'Miễn phí cho đơn hàng trên 1 triệu', 0.00, 1000000.00, 1);


