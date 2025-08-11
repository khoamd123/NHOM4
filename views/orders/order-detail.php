<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="fas fa-receipt"></i> Chi tiết đơn hàng #<?= $order['id'] ?></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/NHOM4_DU_AN_1/index.php">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="/NHOM4_DU_AN_1/index.php?controller=order&action=myOrders">Đơn hàng của tôi</a></li>
                                <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="/NHOM4_DU_AN_1/index.php?controller=order&action=myOrders" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>

                <!-- Thông tin đơn hàng -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Mã đơn hàng:</th>
                                        <td><strong>#<?= $order['id'] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Ngày đặt:</th>
                                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch($order['status']) {
                                                case 'pending':
                                                    $statusClass = 'badge bg-warning';
                                                    $statusText = 'Chờ xử lý';
                                                    break;
                                                case 'confirmed':
                                                    $statusClass = 'badge bg-info';
                                                    $statusText = 'Đã xác nhận';
                                                    break;
                                                case 'shipping':
                                                    $statusClass = 'badge bg-primary';
                                                    $statusText = 'Đang giao hàng';
                                                    break;
                                                case 'delivered':
                                                    $statusClass = 'badge bg-success';
                                                    $statusText = 'Đã giao hàng';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'badge bg-danger';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                                default:
                                                    $statusClass = 'badge bg-secondary';
                                                    $statusText = 'Không xác định';
                                            }
                                            ?>
                                            <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tổng tiền:</th>
                                        <td><strong class="text-danger"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h6>
                                <?php 
                                $shippingInfo = json_decode($order['shipping_info'], true);
                                if ($shippingInfo): 
                                ?>
                                <div class="border rounded p-3 bg-light">
                                    <p class="mb-1"><strong><?= htmlspecialchars($shippingInfo['fullname'] ?? '') ?></strong></p>
                                    <p class="mb-1"><i class="fas fa-phone"></i> <?= htmlspecialchars($shippingInfo['phone'] ?? '') ?></p>
                                    <p class="mb-0"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($shippingInfo['address'] ?? '') ?></p>
                                </div>
                                <?php else: ?>
                                <p class="text-muted">Chưa có thông tin giao hàng</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm trong đơn hàng -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Sản phẩm đã đặt</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60">Ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th width="100">Size</th>
                                        <th width="100">Màu</th>
                                        <th width="80">SL</th>
                                        <th width="120">Đơn giá</th>
                                        <th width="120">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="<?= htmlspecialchars($item['product_image'] ?? '/NHOM4_DU_AN_1/public/assets/images/featured-01.png') ?>" 
                                                 alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.src='/NHOM4_DU_AN_1/public/assets/images/featured-01.png'">
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                            <?php if (!empty($item['brand'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($item['brand']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($item['size']) ?></span></td>
                                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($item['color']) ?></span></td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                        <td><strong><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Tổng cộng:</strong></td>
                                        <td><strong class="text-danger fs-5"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <?php if ($order['status'] === 'pending'): ?>
                <div class="mt-4 text-center">
                    <button class="btn btn-danger" onclick="cancelOrder(<?= $order['id'] ?>)">
                        <i class="fas fa-times"></i> Hủy đơn hàng
                    </button>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cancelOrder(orderId) {
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                return;
            }

            fetch('/NHOM4_DU_AN_1/index.php?controller=order&action=cancelOrder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=' + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Hủy đơn hàng thành công!');
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            });
        }
    </script>
</body>
</html>
