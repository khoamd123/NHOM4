<?php
require_once __DIR__ . '/../../auth.php';
checkAdminAuth();
$currentAdmin = getCurrentAdmin();
?>
<link rel="stylesheet" href="/NHOM4_DU_AN_1/assets/css/Administrator.css">
<link rel="stylesheet" href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* Đảm bảo modal hiển thị đúng */
.modal {
    z-index: 1050;
}

.modal-dialog {
    margin: 1.75rem auto;
}

.modal-content {
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Ngăn chặn việc nhấp nháy */
.modal-backdrop {
    z-index: 1040;
}

/* Đảm bảo form trong modal hoạt động tốt */
.modal-body .form-control {
    border-radius: 0.375rem;
}

.modal-body .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>

<div class="admin-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-chart-bar"></i>
                <span class="logo-text">Admin</span>
            </div>
        </div>
        <div class="sidebar-content">
            <nav class="sidebar-menu">
                <div class="menu-group">
                    <h3 class="menu-title">DANH MỤC</h3>
                    <ul class="menu-list">
                        <li class="menu-item">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=dashboard" class="menu-link">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="menu-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=users" class="menu-link">
                                <i class="fas fa-user-cog"></i>
                                <span class="menu-text">Quản lý tài khoản</span>
                            </a>
                        </li>
                        <li class="menu-item active">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=categories" class="menu-link">
                                <i class="fas fa-tags"></i>
                                <span class="menu-text">Quản lý danh mục</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=products" class="menu-link">
                                <i class="fas fa-box"></i>
                                <span class="menu-text">Sản phẩm</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders" class="menu-link">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="menu-text">Đơn hàng</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="sidebar-footer">
            <div class="logout-section">
                <a href="/NHOM4_DU_AN_1/admin/logout.php" class="logout-button">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="page-title">Quản lý danh mục</h1>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Thành công!</strong> 
                    <?php 
                    switch($_GET['success']) {
                        case '1': echo 'Danh mục đã được thêm thành công.'; break;
                        case '2': echo 'Danh mục đã được cập nhật thành công.'; break;
                        default: echo 'Thao tác đã được thực hiện thành công.';
                    }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Lỗi!</strong> 
                    <?php 
                    switch($_GET['error']) {
                        case '1': echo 'Có lỗi xảy ra khi thêm danh mục.'; break;
                        case '2': echo 'Có lỗi xảy ra khi cập nhật danh mục.'; break;
                        default: echo 'Có lỗi xảy ra khi thực hiện thao tác.';
                    }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Add Category Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thêm danh mục mới</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="/NHOM4_DU_AN_1/admin/index.php?action=categories" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="name" class="form-control" placeholder="Tên danh mục" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="slug" class="form-control" placeholder="Slug" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="add" class="btn btn-success w-100">
                                <i class="fas fa-plus"></i> Thêm danh mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Slug</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($categories as $cat): ?>
                                <tr>
                                    <td><?= $cat['id'] ?></td>
                                    <td><?= htmlspecialchars($cat['name']) ?></td>
                                    <td><?= htmlspecialchars($cat['slug']) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm edit-category-btn"
                                                    data-category-id="<?= $cat['id'] ?>"
                                                    data-category-name="<?= htmlspecialchars($cat['name']) ?>"
                                                    data-category-slug="<?= htmlspecialchars($cat['slug']) ?>"
                                                    title="Sửa danh mục">
                                                <i class="fas fa-edit"></i> Sửa
                                            </button>
                                            <a href="?delete=<?= $cat['id'] ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')"
                                               title="Xóa danh mục">
                                                <i class="fas fa-trash"></i> Xóa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Sửa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="/NHOM4_DU_AN_1/admin/index.php?action=categories">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editCategoryId">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Tên danh mục:</label>
                        <input type="text" name="name" class="form-control" id="editCategoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategorySlug" class="form-label">Slug:</label>
                        <input type="text" name="slug" class="form-control" id="editCategorySlug" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="update" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="/NHOM4_DU_AN_1/vendor/jquery/jquery.min.js"></script>
<script src="/NHOM4_DU_AN_1/vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Xử lý nút sửa danh mục
    $('.edit-category-btn').on('click', function() {
        var categoryId = $(this).data('category-id');
        var categoryName = $(this).data('category-name');
        var categorySlug = $(this).data('category-slug');
        
        // Cập nhật modal với dữ liệu
        $('#editCategoryId').val(categoryId);
        $('#editCategoryName').val(categoryName);
        $('#editCategorySlug').val(categorySlug);
        $('#editCategoryModalLabel').text('Sửa danh mục: ' + categoryName);
        
        // Hiển thị modal
        var modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    });
    
    // Đảm bảo modal hoạt động ổn định
    $('#editCategoryModal').on('show.bs.modal', function (e) {
        // Ngăn chặn việc nhấp nháy
        $(this).find('.modal-dialog').css('margin-top', '50px');
    });
    
    // Xử lý khi modal đóng
    $('#editCategoryModal').on('hidden.bs.modal', function (e) {
        // Reset form
        $(this).find('form')[0].reset();
    });
});
</script>