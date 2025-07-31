<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .variant-form {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .product-image {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .product-image:hover {
            transform: scale(1.1);
        }
        .image-preview {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .image-preview img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        .image-preview .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/NHOM4_DU_AN_1/admin/index.php?action=dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/NHOM4_DU_AN_1/admin/index.php?action=users">
                                <i class="fas fa-users"></i> Quản lý người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/NHOM4_DU_AN_1/admin/index.php?action=products">
                                <i class="fas fa-box"></i> Quản lý sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/NHOM4_DU_AN_1/admin/index.php?action=categories">
                                <i class="fas fa-tags"></i> Quản lý danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/NHOM4_DU_AN_1/admin/index.php?action=orders">
                                <i class="fas fa-shopping-cart"></i> Quản lý đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/NHOM4_DU_AN_1/admin/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản lý sản phẩm</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus"></i> Thêm sản phẩm mới
                    </button>
                </div>

                <!-- Thông báo -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php
                        switch ($_GET['success']) {
                            case '1': echo 'Thêm sản phẩm thành công!'; break;
                            case '2': echo 'Cập nhật sản phẩm thành công!'; break;
                            case '3': echo 'Xóa sản phẩm thành công!'; break;
                            case '4': echo 'Thêm biến thể thành công!'; break;
                            case '5': echo 'Cập nhật biến thể thành công!'; break;
                            case '6': echo 'Xóa biến thể thành công!'; break;
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php
                        switch ($_GET['error']) {
                            case '1': echo 'Lỗi khi thêm sản phẩm!'; break;
                            case '2': echo 'Lỗi khi cập nhật sản phẩm!'; break;
                            case '3': echo 'Lỗi khi xóa sản phẩm!'; break;
                            case '4': echo 'Lỗi khi thêm biến thể!'; break;
                            case '5': echo 'Lỗi khi cập nhật biến thể!'; break;
                            case '6': echo 'Lỗi khi xóa biến thể!'; break;
                        }
                        ?>
                        <?php if (isset($_GET['debug'])): ?>
                            <br><strong>Chi tiết lỗi:</strong> <?= htmlspecialchars($_GET['debug']) ?>
                        <?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Danh sách sản phẩm -->
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Thương hiệu</th>
                                <th>Giá (VNĐ)</th>
                                <th>Giới tính</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="<?= htmlspecialchars($product['image']) ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             class="product-image" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;"
                                             onclick="openImagePreview('<?= htmlspecialchars($product['image']) ?>', '<?= htmlspecialchars($product['name']) ?>')">
                                    <?php else: ?>
                                        <div class="no-image" style="width: 60px; height: 60px; background-color: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 12px;">
                                            No Image
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['brand']) ?></td>
                                <td><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</td>
                                <td>
                                    <?php
                                    switch ($product['gender']) {
                                        case 'men': echo 'Nam'; break;
                                        case 'women': echo 'Nữ'; break;
                                        case 'unisex': echo 'Unisex'; break;
                                        default: echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($product['category_names'] ?? 'Chưa phân loại') ?></td>
                                <td>
                                    <span class="badge bg-<?= $product['status'] ? 'success' : 'danger' ?>">
                                        <?= $product['status'] ? 'Hoạt động' : 'Ẩn' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/NHOM4_DU_AN_1/admin/index.php?action=products&edit=<?= $product['id'] ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/NHOM4_DU_AN_1/admin/index.php?action=products&delete=<?= $product['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Form chỉnh sửa sản phẩm -->
                <?php if ($editProduct): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Chỉnh sửa sản phẩm: <?= htmlspecialchars($editProduct['name']) ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($editProduct['name']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Thương hiệu</label>
                                        <input type="text" class="form-control" id="brand" name="brand" 
                                               value="<?= htmlspecialchars($editProduct['brand']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Giá (VNĐ)</label>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               value="<?= $editProduct['price'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Hình ảnh (URL)</label>
                                        <input type="text" class="form-control" id="image" name="image" 
                                               value="<?= htmlspecialchars($editProduct['image'] ?? '') ?>" 
                                               placeholder="Nhập URL hình ảnh">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Giới tính</label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="men" <?= $editProduct['gender'] == 'men' ? 'selected' : '' ?>>Nam</option>
                                            <option value="women" <?= $editProduct['gender'] == 'women' ? 'selected' : '' ?>>Nữ</option>
                                            <option value="unisex" <?= $editProduct['gender'] == 'unisex' ? 'selected' : '' ?>>Unisex</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="categories" class="form-label">Danh mục</label>
                                        <select class="form-select" id="categories" name="categories[]" multiple>
                                            <?php 
                                            $productCategories = getProductCategories($editProduct['id']);
                                            $productCategoryIds = array_column($productCategories, 'id');
                                            foreach ($categories as $category): 
                                            ?>
                                            <option value="<?= $category['id'] ?>" 
                                                    <?= in_array($category['id'], $productCategoryIds) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($editProduct['description']) ?></textarea>
                            </div>
                            <button type="submit" name="update_product" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật sản phẩm
                            </button>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=products" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </form>

                        <!-- Quản lý biến thể sản phẩm -->
                        <div class="variant-form">
                            <h6>Quản lý biến thể sản phẩm</h6>
                            <form method="POST" class="row g-3">
                                <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="size" placeholder="Size" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="color" placeholder="Màu sắc" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" name="stock" placeholder="Số lượng" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" name="price" placeholder="Giá" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="add_variant" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Thêm biến thể
                                    </button>
                                </div>
                            </form>

                            <!-- Danh sách biến thể -->
                            <?php if (!empty($productVariants)): ?>
                            <div class="table-responsive mt-3">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Màu sắc</th>
                                            <th>Số lượng</th>
                                            <th>Giá</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productVariants as $variant): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($variant['size']) ?></td>
                                            <td><?= htmlspecialchars($variant['color']) ?></td>
                                            <td><?= $variant['stock'] ?></td>
                                            <td><?= number_format($variant['price'], 0, ',', '.') ?> VNĐ</td>
                                            <td>
                                                <a href="/NHOM4_DU_AN_1/admin/index.php?action=products&delete_variant=<?= $variant['id'] ?>&product_id=<?= $editProduct['id'] ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Modal thêm sản phẩm mới -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                                 <form method="POST" enctype="multipart/form-data">
                     <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modal_name" class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="modal_name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modal_brand" class="form-label">Thương hiệu</label>
                                    <input type="text" class="form-control" id="modal_brand" name="brand">
                                </div>
                                <div class="mb-3">
                                    <label for="modal_price" class="form-label">Giá (VNĐ)</label>
                                    <input type="number" class="form-control" id="modal_price" name="price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modal_image" class="form-label">Hình ảnh</label>
                                    <input type="file" class="form-control" id="modal_image" name="image_file" 
                                           accept="image/*" onchange="previewImage(this)">
                                    <input type="hidden" name="image" id="modal_image_url">
                                    <div id="image_preview" class="mt-2" style="display: none;">
                                        <img id="preview_img" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                                    </div>
                                    <small class="form-text text-muted">Hoặc nhập URL hình ảnh:</small>
                                    <input type="text" class="form-control mt-1" name="image_url" 
                                           placeholder="Nhập URL hình ảnh (tùy chọn)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modal_gender" class="form-label">Giới tính</label>
                                    <select class="form-select" id="modal_gender" name="gender" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="men">Nam</option>
                                        <option value="women">Nữ</option>
                                        <option value="unisex">Unisex</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="modal_categories" class="form-label">Danh mục</label>
                                    <select class="form-select" id="modal_categories" name="categories[]" multiple>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Thêm sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal xem ảnh -->
    <div class="image-preview" id="imagePreview">
        <span class="close-btn" onclick="closeImagePreview()">&times;</span>
        <img id="previewImage" src="" alt="Preview">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hàm mở xem ảnh
        function openImagePreview(imageSrc, imageAlt) {
            document.getElementById('previewImage').src = imageSrc;
            document.getElementById('previewImage').alt = imageAlt;
            document.getElementById('imagePreview').style.display = 'flex';
        }

        // Hàm đóng xem ảnh
        function closeImagePreview() {
            document.getElementById('imagePreview').style.display = 'none';
        }

        // Đóng modal khi click bên ngoài
        document.getElementById('imagePreview').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImagePreview();
            }
        });

        // Thêm sự kiện click cho tất cả ảnh sản phẩm
        document.addEventListener('DOMContentLoaded', function() {
            const productImages = document.querySelectorAll('.product-image');
            productImages.forEach(function(img) {
                img.addEventListener('click', function() {
                    openImagePreview(this.src, this.alt);
                });
            });
        });

        // Hàm preview ảnh khi chọn file
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview_img').src = e.target.result;
                    document.getElementById('image_preview').style.display = 'block';
                    document.getElementById('modal_image_url').value = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html> 