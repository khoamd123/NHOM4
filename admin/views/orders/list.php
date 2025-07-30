<?php
require_once __DIR__ . '/../../auth.php';
checkAdminAuth();
$currentAdmin = getCurrentAdmin();
?>
<link rel="stylesheet" href="/NHOM4_DU_AN_1/assets/css/Administrator.css">
<link rel="stylesheet" href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .status-processing {
        background-color: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }
    .status-shipped {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .status-delivered {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .filter-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .order-amount {
        font-weight: 600;
        color: #059669;
    }
    .customer-info {
        font-size: 0.875rem;
        color: #6b7280;
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
                        <li class="menu-item">
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
                        <li class="menu-item active">
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
                    <h1 class="page-title">Quản lý đơn hàng</h1>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Thành công!</strong> 
                    <?php 
                    switch($_GET['success']) {
                        case '1': echo 'Trạng thái đơn hàng đã được cập nhật thành công.'; break;
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
                        case '1': echo 'Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.'; break;
                        case '2': echo 'Không tìm thấy đơn hàng.'; break;
                        default: echo 'Có lỗi xảy ra khi thực hiện thao tác.';
                    }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Bộ lọc</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-list"></i> Tất cả
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=pending" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-clock"></i> Chờ xử lý
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=processing" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-cog"></i> Đang xử lý
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=shipped" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-shipping-fast"></i> Đã gửi
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=delivered" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-check-circle"></i> Đã giao
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=cancelled" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-times-circle"></i> Đã hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Khách hàng</th>
                                    <th>Thông tin liên hệ</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thanh toán</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p>Chưa có đơn hàng nào</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($orders as $order): ?>
                                    <tr>
                                        <td>
                                            <strong>#<?= $order['id'] ?></strong>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($order['customer_name']) ?></div>
                                        </td>
                                        <td>
                                            <div class="customer-info">
                                                <div><i class="fas fa-envelope"></i> <?= htmlspecialchars($order['customer_email']) ?></div>
                                                <div><i class="fas fa-phone"></i> <?= htmlspecialchars($order['customer_phone']) ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="order-amount">
                                                <?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ
                                            </div>
                                            <small class="text-muted">
                                                Phí ship: <?= number_format($order['shipping_fee'], 0, ',', '.') ?> VNĐ
                                            </small>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = 'status-' . $order['status'];
                                            $statusText = '';
                                            switch($order['status']) {
                                                case 'pending': $statusText = 'Chờ xử lý'; break;
                                                case 'processing': $statusText = 'Đang xử lý'; break;
                                                case 'shipped': $statusText = 'Đã gửi'; break;
                                                case 'delivered': $statusText = 'Đã giao'; break;
                                                case 'cancelled': $statusText = 'Đã hủy'; break;
                                                default: $statusText = 'Không xác định';
                                            }
                                            ?>
                                            <span class="status-badge <?= $statusClass ?>">
                                                <?= $statusText ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($order['is_paid']): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Đã thanh toán
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock"></i> Chưa thanh toán
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&detail=<?= $order['id'] ?>" 
                                                   class="btn btn-primary btn-sm"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#updateStatusModal<?= $order['id'] ?>"
                                                        title="Cập nhật trạng thái">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>

                                            <!-- Update Status Modal -->
                                            <div class="modal fade" id="updateStatusModal<?= $order['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Cập nhật trạng thái đơn hàng #<?= $order['id'] ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="post" action="/NHOM4_DU_AN_1/admin/index.php?action=orders" name="updateStatusForm">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                                <div class="mb-3">
                                                                    <label for="status" class="form-label">Trạng thái mới:</label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                                                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                                                        <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Đã gửi</option>
                                                                        <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Đã giao</option>
                                                                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                <button type="submit" name="update_status" class="btn btn-primary">Cập nhật</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="/NHOM4_DU_AN_1/vendor/jquery/jquery.min.js"></script>
<script src="/NHOM4_DU_AN_1/vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
// Đảm bảo modal hoạt động tốt
$(document).ready(function() {
    // Xử lý modal
    $('.modal').on('show.bs.modal', function (e) {
        // Đảm bảo modal hiển thị đúng
    });
    
    // Xử lý form submit
    $('form[name="updateStatusForm"]').on('submit', function(e) {
        // Có thể thêm validation ở đây nếu cần
    });
});
</script> 