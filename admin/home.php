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
$pendingOrders = getPendingOrders();
$outOfStockProducts = getOutOfStockProducts();
$newUsersThisMonth = getNewUsersThisMonth();

// Get chart data
$revenueData = getRevenueChartData();
$ordersData = getOrdersChartData();
$usersData = getUsersChartData();
$orderStatusData = getOrderStatusData();
$topProducts = getTopProducts();

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
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/public/assets/css/Administrator.css">
<link rel="stylesheet" href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                  <h3 class="stat-number"><?= number_format($monthlyRevenue, 0, ',', '.') ?>₫</h3>
                  <p class="stat-label">Doanh thu tháng</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Charts Section -->
          <div class="row mb-4">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <h5><i class="fas fa-chart-line"></i> Biểu đồ doanh thu theo tháng</h5>
                </div>
                <div class="card-body">
                  <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5><i class="fas fa-chart-pie"></i> Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                  <canvas id="orderStatusChart" width="400" height="200"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- More Charts -->
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h5><i class="fas fa-chart-bar"></i> Đơn hàng theo tháng</h5>
                </div>
                <div class="card-body">
                  <canvas id="ordersChart" width="400" height="200"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h5><i class="fas fa-users"></i> Người dùng mới theo tháng</h5>
                </div>
                <div class="card-body">
                  <canvas id="usersChart" width="400" height="200"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Top Products and Quick Stats -->
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <h5><i class="fas fa-trophy"></i> Top sản phẩm bán chạy</h5>
                </div>
                <div class="card-body">
                  <?php if (empty($topProducts)): ?>
                    <p class="text-muted">Chưa có dữ liệu bán hàng</p>
                  <?php else: ?>
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Sản phẩm</th>
                            <th>Số đơn hàng</th>
                            <th>Tổng số lượng</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($topProducts as $product): ?>
                            <tr>
                              <td><?= htmlspecialchars($product['name']) ?></td>
                              <td><?= $product['sold_count'] ?: 0 ?></td>
                              <td><?= $product['total_quantity'] ?: 0 ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5><i class="fas fa-info-circle"></i> Thống kê nhanh</h5>
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

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      <!-- Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      
      <script>
        // Dữ liệu cho biểu đồ
        const revenueData = <?= json_encode($revenueData) ?>;
        const ordersData = <?= json_encode($ordersData) ?>;
        const usersData = <?= json_encode($usersData) ?>;
        const orderStatusData = <?= json_encode($orderStatusData) ?>;

        // Biểu đồ doanh thu
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
          type: 'line',
          data: {
            labels: revenueData.map(item => {
              const [year, month] = item.month.split('-');
              return `${month}/${year}`;
            }),
            datasets: [{
              label: 'Doanh thu (VNĐ)',
              data: revenueData.map(item => item.revenue),
              borderColor: 'rgb(75, 192, 192)',
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              tension: 0.1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              title: {
                display: true,
                text: 'Doanh thu theo tháng'
              }
            }
          }
        });

        // Biểu đồ trạng thái đơn hàng
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(orderStatusCtx, {
          type: 'doughnut',
          data: {
            labels: orderStatusData.map(item => {
              const statusMap = {
                'pending': 'Chờ xử lý',
                'processing': 'Đang xử lý',
                'shipped': 'Đã gửi',
                'delivered': 'Đã giao',
                'cancelled': 'Đã hủy'
              };
              return statusMap[item.status] || item.status;
            }),
            datasets: [{
              data: orderStatusData.map(item => item.count),
              backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF'
              ]
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom',
              }
            }
          }
        });

        // Biểu đồ đơn hàng
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
          type: 'bar',
          data: {
            labels: ordersData.map(item => {
              const [year, month] = item.month.split('-');
              return `${month}/${year}`;
            }),
            datasets: [{
              label: 'Số đơn hàng',
              data: ordersData.map(item => item.orders),
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              }
            },
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });

        // Biểu đồ người dùng mới
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
          type: 'bar',
          data: {
            labels: usersData.map(item => {
              const [year, month] = item.month.split('-');
              return `${month}/${year}`;
            }),
            datasets: [{
              label: 'Người dùng mới',
              data: usersData.map(item => item.new_users),
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              borderColor: 'rgb(255, 99, 132)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              }
            },
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      </script>