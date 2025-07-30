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
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
        border: 2px solid #fde68a;
    }
    .status-processing {
        background-color: #dbeafe;
        color: #1e40af;
        border: 2px solid #93c5fd;
    }
    .status-shipped {
        background-color: #d1fae5;
        color: #065f46;
        border: 2px solid #a7f3d0;
    }
    .status-delivered {
        background-color: #dcfce7;
        color: #166534;
        border: 2px solid #bbf7d0;
    }
    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
        border: 2px solid #fecaca;
    }
    .order-detail-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }
    .product-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .product-image-placeholder {
        width: 80px;
        height: 80px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
    }
    .total-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    .info-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.1rem;
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
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Chi tiết đơn hàng #<?= $order['id'] ?></h1>
                            <p class="text-muted">Ngày tạo: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="button" 
                                    class="btn btn-warning"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#updateStatusModal">
                                <i class="fas fa-edit"></i> Cập nhật trạng thái
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Thông tin đơn hàng -->
                <div class="col-md-8">
                    <div class="order-detail-card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> Thông tin đơn hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Trạng thái:</label>
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
                                        <div class="mt-1">
                                            <span class="status-badge <?= $statusClass ?>">
                                                <?= $statusText ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold">Thanh toán:</label>
                                        <div class="mt-1">
                                            <?php if ($order['is_paid']): ?>
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-check"></i> Đã thanh toán
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning fs-6">
                                                    <i class="fas fa-clock"></i> Chưa thanh toán
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Tổng tiền hàng:</label>
                                        <div class="fs-5 text-success fw-bold">
                                            <?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold">Phí vận chuyển:</label>
                                        <div class="fs-6">
                                            <?= number_format($order['shipping_fee'], 0, ',', '.') ?> VNĐ
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="order-detail-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart"></i> Sản phẩm đã mua
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($orderItems)): ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-2x mb-3"></i>
                                    <p>Không có sản phẩm nào trong đơn hàng</p>
                                </div>
                            <?php else: ?>
                                <?php foreach($orderItems as $item): ?>
                                <div class="product-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="product-image-placeholder">
                                                <i class="fas fa-shoe-prints fa-2x text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-1"><?= htmlspecialchars($item['product_name']) ?></h6>
                                            <p class="text-muted mb-0">
                                                Size: <?= $item['size'] ?> | Màu: <?= $item['color'] ?>
                                            </p>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span class="badge bg-secondary fs-6">x<?= $item['quantity'] ?></span>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="fw-bold text-success">
                                                <?= number_format($item['price'], 0, ',', '.') ?> VNĐ
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Thông tin khách hàng và tổng tiền -->
                <div class="col-md-4">
                    <!-- Thông tin khách hàng -->
                    <div class="order-detail-card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user"></i> Thông tin khách hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-bold">Họ tên:</label>
                                <div><?= htmlspecialchars($order['customer_name']) ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Email:</label>
                                <div><?= htmlspecialchars($order['customer_email']) ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Số điện thoại:</label>
                                <div><?= htmlspecialchars($order['customer_phone']) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tổng tiền -->
                    <div class="order-detail-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-calculator"></i> Tổng tiền
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="total-section">
                                <div class="info-row">
                                    <span>Tổng tiền hàng:</span>
                                    <span><?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ</span>
                                </div>
                                <div class="info-row">
                                    <span>Phí vận chuyển:</span>
                                    <span><?= number_format($order['shipping_fee'], 0, ',', '.') ?> VNĐ</span>
                                </div>
                                <div class="info-row">
                                    <span>Tổng cộng:</span>
                                    <span class="text-success"><?= number_format($order['total_amount'] + $order['shipping_fee'], 0, ',', '.') ?> VNĐ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái đơn hàng #<?= $order['id'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="/NHOM4_DU_AN_1/admin/index.php?action=orders">
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
});
</script> 