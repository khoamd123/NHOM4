# Hướng dẫn sử dụng phần Quản lý sản phẩm

## Cài đặt

### 1. Cập nhật cơ sở dữ liệu
Chạy file SQL để cập nhật cấu trúc database:
```sql
-- Chạy file admin/update_database.sql trong phpMyAdmin
```

### 2. Truy cập
- URL: `http://localhost/NHOM4_DU_AN_1/admin/index.php?action=products`
- Đăng nhập với tài khoản admin

## Tính năng

### 1. Xem danh sách sản phẩm
- Hiển thị tất cả sản phẩm với thông tin: ID, tên, thương hiệu, giá, giới tính, danh mục, trạng thái
- Sắp xếp theo thời gian tạo mới nhất

### 2. Thêm sản phẩm mới
- Click nút "Thêm sản phẩm mới"
- Điền thông tin:
  - Tên sản phẩm (bắt buộc)
  - Thương hiệu
  - Giá (bắt buộc)
  - Giới tính (Nam/Nữ/Unisex)
  - Danh mục (có thể chọn nhiều)
  - Mô tả
  - Hình ảnh (URL)

### 3. Chỉnh sửa sản phẩm
- Click nút "Sửa" (biểu tượng bút chì) bên cạnh sản phẩm
- Cập nhật thông tin sản phẩm
- Quản lý biến thể sản phẩm (size, màu sắc, số lượng, giá)

### 4. Xóa sản phẩm
- Click nút "Xóa" (biểu tượng thùng rác)
- Xác nhận xóa

### 5. Quản lý biến thể sản phẩm
- Thêm biến thể: Size, màu sắc, số lượng, giá
- Xem danh sách biến thể của sản phẩm
- Xóa biến thể không cần thiết

## Cấu trúc file

```
admin/
├── models/
│   └── productModel.php          # Model xử lý dữ liệu sản phẩm
├── controllers/
│   └── productController.php     # Controller xử lý logic
├── views/
│   └── products/
│       └── list.php              # Giao diện quản lý sản phẩm
└── update_database.sql           # File cập nhật database
```

## Lưu ý

1. **Slug tự động**: Tên sản phẩm sẽ tự động tạo slug URL-friendly
2. **Validation**: Các trường bắt buộc được kiểm tra
3. **Bảo mật**: Sử dụng prepared statements để tránh SQL injection
4. **Transaction**: Đảm bảo tính toàn vẹn dữ liệu khi thêm/sửa/xóa

## Troubleshooting

### Lỗi kết nối database
- Kiểm tra file `admin/models/db.php`
- Đảm bảo XAMPP đang chạy
- Kiểm tra tên database: `da1-nhom4-shopgiay`

### Lỗi quyền truy cập
- Đăng nhập với tài khoản admin
- Kiểm tra session trong `admin/auth.php`

### Lỗi hiển thị
- Kiểm tra đường dẫn file CSS/JS
- Đảm bảo Bootstrap và FontAwesome được load 