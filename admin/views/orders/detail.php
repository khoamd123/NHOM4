<?php
require_once __DIR__ . '/../../auth.php';
checkAdminAuth();
$currentAdmin = getCurrentAdmin();
?>
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/public/assets/css/Administrator.css">
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
        border: 1px solid #a7f3d0;
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
    .status-confirmed {
        background-color: #dbeafe;
        color: #1e40af;
        border: 2px solid #93c5fd;
    }
    
    /* Status flow display */
    .status-flow {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 1rem 0;
    }
    .status-flow .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    .status-flow .fas.fa-arrow-right {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Toast notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    .toast {
        min-width: 300px;
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
        <!-- Toast Container -->
        <div class="toast-container"></div>
        
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
                                            case 'delivered': $statusText = 'Đã giao hàng'; break;
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
                                            <?php 
                                            // Logic hiển thị trạng thái thanh toán
                                            $paymentStatus = $order['is_paid'];
                                            $paymentText = '';
                                            $paymentClass = '';
                                            
                                            // Nếu đơn hàng đã giao hàng thì tự động coi như đã thanh toán
                                            if ($order['status'] === 'delivered') {
                                                $paymentStatus = true;
                                                $paymentText = 'Đã thanh toán';
                                                $paymentClass = 'bg-success';
                                            } elseif ($paymentStatus) {
                                                $paymentText = 'Đã thanh toán';
                                                $paymentClass = 'bg-success';
                                            } else {
                                                $paymentText = 'Chưa thanh toán';
                                                $paymentClass = 'bg-warning';
                                            }
                                            ?>
                                            <span class="badge <?= $paymentClass ?> fs-6">
                                                <i class="fas fa-<?= $paymentStatus ? 'check' : 'clock' ?>"></i> 
                                                <?= $paymentText ?>
                                            </span>
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
                                            <?php if (!empty($item['product_image'])): ?>
                                                <img src="/NHOM4_DU_AN_1/public/uploads/products/<?= htmlspecialchars($item['product_image']) ?>" 
                                                     alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                                     class="product-image">
                                            <?php else: ?>
                                                <div class="product-image-placeholder">
                                                    <i class="fas fa-shoe-prints fa-2x text-muted"></i>
                                                </div>
                                            <?php endif; ?>
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
            <div class="modal-body">
                <form id="statusUpdateForm">
                    <input type="hidden" id="orderId" name="order_id" value="<?= $order['id'] ?>">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">Trạng thái mới:</label>
                        <select name="status" id="newStatus" class="form-control" required>
                            <option value="">Chọn trạng thái...</option>
                        </select>
                        <div class="form-text">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Chỉ có thể chuyển đổi trạng thái theo quy trình logic
                            </small>
                        </div>
                    </div>
                    
                    <!-- Quy tắc chuyển đổi trạng thái -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-route"></i> Quy trình chuyển đổi trạng thái:
                        </h6>
                        <div class="status-flow">
                            <span class="badge bg-warning">Chờ xử lý</span>
                            <i class="fas fa-arrow-right mx-2"></i>
                            <span class="badge bg-primary">Đang xử lý</span>
                            <i class="fas fa-arrow-right mx-2"></i>
                            <span class="badge bg-info">Đã gửi</span>
                            <i class="fas fa-arrow-right mx-2"></i>
                            <span class="badge bg-success">Đã giao hàng</span>
                        </div>
                        <div class="mt-2">
                            <small>
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <strong>Quy tắc:</strong> Chỉ có thể tiến tới bước tiếp theo, không thể lùi hoặc nhảy cóc
                            </small>
                        </div>
                        <div class="mt-1">
                            <small>
                                <i class="fas fa-info-circle text-info"></i>
                                Có thể hủy đơn hàng ở bất kỳ bước nào trước "Đã giao hàng"
                            </small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="updateStatusBtn" class="btn btn-primary">
                    <span class="btn-text">Cập nhật</span>
                    <span class="btn-loading d-none">
                        <i class="fas fa-spinner fa-spin"></i> Đang cập nhật...
                    </span>
                </button>
            </div>
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
        if (e.target.id === 'updateStatusModal') {
            // Cập nhật danh sách trạng thái có thể chuyển đổi
            updateAvailableStatuses('<?= $order['status'] ?>');
        }
    });
    
    // Xử lý cập nhật trạng thái
    $('#updateStatusBtn').on('click', function() {
        updateOrderStatus();
    });
    
    // Xử lý khi nhấn Enter trong form
    $('#newStatus').on('keypress', function(e) {
        if (e.which === 13) {
            updateOrderStatus();
        }
    });
});

// Cập nhật danh sách trạng thái có thể chuyển đổi
function updateAvailableStatuses(currentStatus) {
    console.log('updateAvailableStatuses called with:', currentStatus);
    
    const statusSelect = $('#newStatus');
    const availableStatuses = getAvailableStatuses(currentStatus);
    
    console.log('Available statuses:', availableStatuses);
    
    // Xóa tất cả options cũ
    statusSelect.empty();
    
    // Thêm option mặc định
    statusSelect.append('<option value="">Chọn trạng thái...</option>');
    
    // Thêm options mới theo quy tắc logic
    availableStatuses.forEach(status => {
        statusSelect.append(`<option value="${status.value}">${status.text}</option>`);
    });
    
    // Chọn trạng thái hiện tại
    statusSelect.val(currentStatus);
    
    // Disable select nếu chỉ có 1 option (không thể thay đổi)
    if (availableStatuses.length === 1) {
        statusSelect.prop('disabled', true);
        $('#updateStatusBtn').prop('disabled', true).text('Không thể thay đổi');
    } else {
        statusSelect.prop('disabled', false);
        $('#updateStatusBtn').prop('disabled', false).html('<span class="btn-text">Cập nhật</span>');
    }
}

// Lấy danh sách trạng thái có thể chuyển đổi từ trạng thái hiện tại
function getAvailableStatuses(currentStatus) {
    const statusRules = {
        'pending': [
            { value: 'pending', text: 'Chờ xử lý (giữ nguyên)' },
            { value: 'processing', text: 'Đang xử lý' },
            { value: 'cancelled', text: 'Đã hủy' }
        ],
        'processing': [
            { value: 'processing', text: 'Đang xử lý (giữ nguyên)' },
            { value: 'shipped', text: 'Đã gửi' },
            { value: 'cancelled', text: 'Đã hủy' }
        ],
        'shipped': [
            { value: 'shipped', text: 'Đã gửi (giữ nguyên)' },
            { value: 'delivered', text: 'Đã giao hàng' },
            { value: 'cancelled', text: 'Đã hủy' }
        ],
        'delivered': [
            { value: 'delivered', text: 'Đã giao hàng (giữ nguyên)' }
            // Không thể thay đổi từ "Đã giao hàng"
        ],
        'cancelled': [
            { value: 'cancelled', text: 'Đã hủy (giữ nguyên)' }
            // Không thể thay đổi từ "Đã hủy"
        ],
        // Xử lý trường hợp trạng thái không xác định
        'undefined': [
            { value: 'pending', text: 'Chờ xử lý' },
            { value: 'processing', text: 'Đang xử lý' },
            { value: 'shipped', text: 'Đã gửi' },
            { value: 'delivered', text: 'Đã giao hàng' },
            { value: 'cancelled', text: 'Đã hủy' }
        ],
        'null': [
            { value: 'pending', text: 'Chờ xử lý' },
            { value: 'processing', text: 'Đang xử lý' },
            { value: 'shipped', text: 'Đã gửi' },
            { value: 'delivered', text: 'Đã giao hàng' },
            { value: 'cancelled', text: 'Đã hủy' }
        ]
    };
    
    // Nếu trạng thái không xác định hoặc null, trả về tất cả options
    if (!currentStatus || currentStatus === 'undefined' || currentStatus === 'null' || currentStatus === '') {
        return statusRules['undefined'];
    }
    
    return statusRules[currentStatus] || statusRules['pending'];
}

// Cập nhật trạng thái đơn hàng
function updateOrderStatus() {
    const orderId = $('#orderId').val();
    const newStatus = $('#newStatus').val();
    
    if (!orderId || !newStatus) {
        showToast('Vui lòng chọn trạng thái mới', 'error');
        return;
    }
    
    // Hiển thị loading state
    const btn = $('#updateStatusBtn');
    btn.prop('disabled', true);
    btn.find('.btn-text').addClass('d-none');
    btn.find('.btn-loading').removeClass('d-none');
    
    // Gửi AJAX request
    $.ajax({
        url: '/NHOM4_DU_AN_1/admin/controllers/orderController.php',
        type: 'POST',
        data: {
            update_status: 1,
            order_id: orderId,
            status: newStatus
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Hiển thị thông báo thành công
                showToast('Cập nhật trạng thái thành công!', 'success');
                
                // Đóng modal
                bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
                
                // Reload trang để cập nhật trạng thái hiển thị và thanh toán
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast('Có lỗi xảy ra: ' + (response.message || 'Không thể cập nhật trạng thái'), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            showToast('Có lỗi xảy ra khi kết nối server', 'error');
        },
        complete: function() {
            // Khôi phục button state
            const btn = $('#updateStatusBtn');
            btn.prop('disabled', false);
            btn.find('.btn-text').removeClass('d-none');
            btn.find('.btn-loading').addClass('d-none');
        }
    });
}

// Hiển thị toast notification
function showToast(message, type = 'info') {
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    const toastHtml = `
        <div class="toast show" id="${toastId}" role="alert">
            <div class="toast-header ${bgClass} text-white">
                <i class="fas ${icon} me-2"></i>
                <strong class="me-auto">Thông báo</strong>
                <button type="button" class="btn-close btn-close-white" onclick="removeToast('${toastId}')"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    $('.toast-container').append(toastHtml);
    
    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        removeToast(toastId);
    }, 5000);
}

// Xóa toast
function removeToast(toastId) {
    $(`#${toastId}`).fadeOut(300, function() {
        $(this).remove();
    });
}
</script> 