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
    .status-confirmed {
        background-color: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
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
    
    /* Thêm CSS cho status update */
    .status-update-btn {
        position: relative;
        overflow: hidden;
    }
    .status-update-btn.loading {
        pointer-events: none;
    }
    .status-update-btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 16px;
        height: 16px;
        margin: -8px 0 0 -8px;
        border: 2px solid transparent;
        border-top: 2px solid #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
    
    /* Filter button active state */
    .filter-section .btn.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .filter-section .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
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
    
    /* Modal improvements */
    .modal-body .alert {
        margin-bottom: 0;
    }
    .form-text {
        margin-top: 0.5rem;
    }
    
    /* Disabled state styling */
    .form-control:disabled {
        background-color: #e9ecef;
        opacity: 0.65;
        cursor: not-allowed;
    }
    
    /* Status update button disabled */
    .btn:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }
    
    /* Alert improvements */
    .alert-info .alert-heading {
        color: #0c5460;
        font-weight: 600;
    }
    .alert-info small {
        color: #0c5460;
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

            <!-- Toast Container -->
            <div class="toast-container"></div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Bộ lọc</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders" class="btn btn-outline-secondary btn-sm <?= empty($_GET['status']) ? 'active' : '' ?>">
                                <i class="fas fa-list"></i> Tất cả
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=pending" class="btn btn-outline-warning btn-sm <?= ($_GET['status'] ?? '') == 'pending' ? 'active' : '' ?>">
                                <i class="fas fa-clock"></i> Chờ xử lý
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=processing" class="btn btn-outline-primary btn-sm <?= ($_GET['status'] ?? '') == 'processing' ? 'active' : '' ?>">
                                <i class="fas fa-cog"></i> Đang xử lý
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=shipped" class="btn btn-outline-info btn-sm <?= ($_GET['status'] ?? '') == 'shipped' ? 'active' : '' ?>">
                                <i class="fas fa-shipping-fast"></i> Đã gửi
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=delivered" class="btn btn-outline-success btn-sm <?= ($_GET['status'] ?? '') == 'delivered' ? 'active' : '' ?>">
                                <i class="fas fa-check-circle"></i> Đã giao hàng
                            </a>
                            <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders&status=cancelled" class="btn btn-outline-danger btn-sm <?= ($_GET['status'] ?? '') == 'cancelled' ? 'active' : '' ?>">
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
                                    <tr data-order-id="<?= $order['id'] ?>">
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
                                            <div class="status-cell" id="status-<?= $order['id'] ?>">
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
                                                <span class="status-badge <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
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
                                            <span class="badge <?= $paymentClass ?>">
                                                <i class="fas fa-<?= $paymentStatus ? 'check' : 'clock' ?>"></i> 
                                                <?= $paymentText ?>
                                            </span>
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
                                                        class="btn btn-warning btn-sm status-update-btn"
                                                        onclick="showStatusUpdateForm(<?= $order['id'] ?>, '<?= htmlspecialchars($order['status'], ENT_QUOTES) ?>')"
                                                        title="Cập nhật trạng thái">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusUpdateForm">
                    <input type="hidden" id="orderId" name="order_id">
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
$(document).ready(function() {
    // Khởi tạo modal
    const statusModal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
    
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
    
    // Xử lý filter button active state
    updateFilterButtonState();
});

// Cập nhật trạng thái active của filter buttons
function updateFilterButtonState() {
    const currentStatus = new URLSearchParams(window.location.search).get('status');
    
    // Xóa tất cả active state
    $('.filter-section .btn').removeClass('active');
    
    // Thêm active state cho button hiện tại
    if (currentStatus) {
        $(`.filter-section .btn[href*="status=${currentStatus}"]`).addClass('active');
    } else {
        $('.filter-section .btn[href*="action=orders"]:not([href*="status="])').addClass('active');
    }
}

// Hiển thị form cập nhật trạng thái
function showStatusUpdateForm(orderId, currentStatus) {
    console.log('showStatusUpdateForm called with:', { orderId, currentStatus });
    
    $('#orderId').val(orderId);
    
    // Cập nhật danh sách trạng thái có thể chuyển đổi TRƯỚC
    updateAvailableStatuses(currentStatus);
    
    // Sau đó mới set giá trị hiện tại
    $('#newStatus').val(currentStatus);
    
    const modal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
    modal.show();
}

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
                // Cập nhật UI
                updateOrderStatusUI(orderId, newStatus);
                
                // Đóng modal
                bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal')).hide();
                
                // Hiển thị thông báo thành công
                showToast('Cập nhật trạng thái thành công!', 'success');
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

// Kiểm tra xem việc chuyển đổi trạng thái có hợp lệ không
function isValidStatusTransition(currentStatus, newStatus) {
    // Nếu giữ nguyên trạng thái thì luôn hợp lệ
    if (currentStatus === newStatus) {
        return true;
    }
    
    // Quy tắc chuyển đổi trạng thái (sử dụng tên thực tế trong database)
    const validTransitions = {
        'pending': ['confirmed', 'cancelled'],
        'confirmed': ['shipping', 'cancelled'],
        'shipping': ['delivered', 'cancelled'],
        'delivered': [], // Không thể thay đổi từ "Đã giao hàng"
        'cancelled': []  // Không thể thay đổi từ "Đã hủy"
    };
    
    return validTransitions[currentStatus] && validTransitions[currentStatus].includes(newStatus);
}

// Cập nhật UI sau khi cập nhật trạng thái
function updateOrderStatusUI(orderId, newStatus) {
    const statusCell = $(`#status-${orderId}`);
    const statusText = getStatusText(newStatus);
    const statusClass = `status-${newStatus}`;
    
    statusCell.html(`
        <span class="status-badge ${statusClass}">
            ${statusText}
        </span>
    `);
}

// Lấy text hiển thị cho trạng thái
function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xử lý',
        'processing': 'Đang xử lý',
        'shipped': 'Đã gửi',
        'delivered': 'Đã giao hàng',
        'cancelled': 'Đã hủy'
    };
    return statusMap[status] || 'Không xác định';
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