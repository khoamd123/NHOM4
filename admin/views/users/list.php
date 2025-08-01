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
    .status-active {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .toggle-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 0.25rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .toggle-btn:hover {
        transform: scale(1.05);
    }
    .toggle-active {
        background-color: #dc3545;
        color: white;
    }
    .toggle-active:hover {
        background-color: #c82333;
    }
    .toggle-inactive {
        background-color: #28a745;
        color: white;
    }
    .toggle-inactive:hover {
        background-color: #218838;
    }
    
    /* Logout button styles */
    .logout-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .logout-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-align: center;
        justify-content: center;
    }
    
    .logout-button:hover {
        background: linear-gradient(135deg, #c0392b, #a93226);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }
    
    .logout-button i {
        font-size: 1rem;
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
                        <li class="menu-item active">
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
                    <h1 class="page-title">Quản lý tài khoản</h1>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Thành công!</strong> 
                    <?php 
                    switch($_GET['success']) {
                        case '1': echo 'Tài khoản đã được thêm thành công.'; break;
                        case '2': echo 'Tài khoản đã được xóa thành công.'; break;
                        case '3': echo 'Trạng thái tài khoản đã được cập nhật.'; break;
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
                        case '1': echo 'Có lỗi xảy ra khi thêm tài khoản.'; break;
                        case '2': echo 'Có lỗi xảy ra khi xóa tài khoản.'; break;
                        case '3': echo 'Có lỗi xảy ra khi cập nhật trạng thái.'; break;
                        default: echo 'Có lỗi xảy ra khi thực hiện thao tác.';
                    }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Add User Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thêm tài khoản mới</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="" class="row g-3">
                        <div class="col-md-2">
                            <input type="text" name="name" class="form-control" placeholder="Tên" required>
                        </div>
                        <div class="col-md-2">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-2">
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="phone" class="form-control" placeholder="SĐT">
                        </div>
                        <div class="col-md-2">
                            <select name="role" class="form-control" required>
                                <option value="0">User</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="add" class="btn btn-success w-100">
                                <i class="fas fa-plus"></i> Thêm tài khoản
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>SĐT</th>
                                    <th>Quyền</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['phone']) ?></td>
                                    <td>
                                        <span class="badge <?= $user['role'] == 1 ? 'bg-danger' : 'bg-primary' ?>">
                                            <?= $user['role'] == 1 ? 'Admin' : 'User' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <span class="status-badge <?= $user['status'] == 1 ? 'status-active' : 'status-inactive' ?>">
                                            <i class="fas <?= $user['status'] == 1 ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                            <?= $user['status'] == 1 ? 'Hoạt động' : 'Tạm khóa' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?toggle_status=<?= $user['id'] ?>" 
                                               class="toggle-btn <?= $user['status'] == 1 ? 'toggle-active' : 'toggle-inactive' ?>"
                                               onclick="return confirm('Bạn có chắc muốn <?= $user['status'] == 1 ? 'khóa' : 'mở khóa' ?> tài khoản này?')"
                                               title="<?= $user['status'] == 1 ? 'Khóa tài khoản' : 'Mở khóa tài khoản' ?>">
                                                <i class="fas <?= $user['status'] == 1 ? 'fa-lock' : 'fa-unlock' ?>"></i>
                                                <?= $user['status'] == 1 ? 'Khóa' : 'Mở khóa' ?>
                                            </a>
                                            <a href="?delete=<?= $user['id'] ?>" 
                                               class="btn btn-danger btn-sm ms-1" 
                                               onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')"
                                               title="Xóa tài khoản">
                                                <i class="fas fa-trash"></i>
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

<!-- Bootstrap JS -->
<script src="/NHOM4_DU_AN_1/vendor/bootstrap/js/bootstrap.min.js"></script>