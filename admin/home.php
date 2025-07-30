<?php
require_once 'auth.php';
checkAdminAuth();
$currentAdmin = getCurrentAdmin();

// Load statistics
require_once 'models/statisticsModel.php';

// Get real data
$totalUsers = getTotalUsers();
$totalProducts = getTotalProducts();
$totalOrders = getTotalOrders();
$monthlyRevenue = getMonthlyRevenue();
$yearlyRevenue = getYearlyRevenue();
$pendingOrders = getPendingOrders();
$outOfStockProducts = getOutOfStockProducts();
$newUsersThisMonth = getNewUsersThisMonth();
$recentActivities = getRecentActivities();

// Function to format time ago
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Vừa xong';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' phút trước';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' giờ trước';
    } elseif ($diff < 2592000) {
        $days = floor($diff / 86400);
        return $days . ' ngày trước';
    } else {
        $months = floor($diff / 2592000);
        return $months . ' tháng trước';
    }
}
?>
<link rel="stylesheet" href="/NHOM4_DU_AN_1/assets/css/Administrator.css">
<link rel="stylesheet" href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css">
      <aside class="sidebar">
        <div class="sidebar-header">
          <div class="logo">
            <i class="fas fa-chart-bar"></i>
            <span class="logo-text">Admin </span>
          </div>
        </div>
        <div class="sidebar-content">
          <nav class="sidebar-menu">
            <div class="menu-group">
              <h3 class="menu-title">DANH MỤC</h3>
              <ul class="menu-list">
    <li class="menu-item active">
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
    <li class="menu-item">
        <a href="/NHOM4_DU_AN_1/admin/index.php?action=orders" class="menu-link">
            <i class="fas fa-clipboard-list"></i>
            <span class="menu-text">Đơn hàng</span>
        </a>
    </li>
</ul>
        <div class="sidebar-footer">
          <div class="user-profile">
            <div class="avatar">
              <img src="https://via.placeholder.com/40" alt="User" />
            </div>
            <div class="user-info">
              <span class="user-name"><?= $currentAdmin['name'] ?></span>
              <span class="user-role">Admin</span>
            </div>
            <a href="logout.php" class="logout-link" title="Đăng xuất">
              <i class="fas fa-sign-out-alt logout-icon"></i>
            </a>
          </div>
        </div>
      </aside>
      
      <!-- Main Content -->
      <main class="main-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1 class="page-title">Dashboard</h1>
            </div>
          </div>
          
          <!-- Statistics Cards -->
          <div class="row mb-4">
            <div class="col-md-3">
              <div class="stat-card">
                                    <div class="stat-icon">
                      <i class="fas fa-user-friends"></i>
                    </div>
                <div class="stat-content">
                  <h3 class="stat-number"><?= $totalUsers ?></h3>
                  <p class="stat-label">Tổng số người dùng</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-card">
                                    <div class="stat-icon">
                      <i class="fas fa-shoe-prints"></i>
                    </div>
                <div class="stat-content">
                  <h3 class="stat-number"><?= $totalProducts ?></h3>
                  <p class="stat-label">Tổng số sản phẩm</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-card">
                                    <div class="stat-icon">
                      <i class="fas fa-shopping-bag"></i>
                    </div>
                <div class="stat-content">
                  <h3 class="stat-number"><?= $totalOrders ?></h3>
                  <p class="stat-label">Tổng số đơn hàng</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-card">
                                    <div class="stat-icon">
                      <i class="fas fa-chart-line"></i>
                    </div>
                <div class="stat-content">
                  <h3 class="stat-number">$<?= number_format($monthlyRevenue, 2) ?></h3>
                  <p class="stat-label">Doanh thu tháng</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Recent Activities -->
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h5>Hoạt động gần đây</h5>
                </div>
                <div class="card-body">
                  <ul class="activity-list">
                    <?php if (empty($recentActivities)): ?>
                      <li>
                        <span class="activity-text text-muted">Chưa có hoạt động nào</span>
                      </li>
                    <?php else: ?>
                      <?php foreach ($recentActivities as $activity): ?>
                        <li>
                          <span class="activity-time"><?= timeAgo($activity['time']) ?></span>
                          <span class="activity-text"><?= htmlspecialchars($activity['message']) ?></span>
                        </li>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h5>Thống kê nhanh</h5>
                </div>
                <div class="card-body">
                  <div class="quick-stats">
                    <div class="stat-item">
                      <span class="stat-label">Người dùng mới tháng này:</span>
                      <span class="stat-value"><?= $newUsersThisMonth ?></span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-label">Sản phẩm hết hàng:</span>
                      <span class="stat-value"><?= $outOfStockProducts ?></span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-label">Đơn hàng chờ xử lý:</span>
                      <span class="stat-value"><?= $pendingOrders ?></span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-label">Tổng doanh thu năm:</span>
                      <span class="stat-value">$<?= number_format($yearlyRevenue, 2) ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>