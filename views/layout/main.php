<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Shoe Shop - Cửa hàng giày chất lượng cao</title>
    <!-- Bootstrap core CSS -->
    <link href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/public/assets/css/fontawesome.css">
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/public/assets/css/templatemo-lugx-gaming.css">
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/public/assets/css/owl.css">
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/public/assets/css/animate.css">
    
</head>
<body>
<?php
// Include header
include __DIR__ . '/partials/header.php';

if (!isset($page)) {
    // Trang chủ: hiển thị các phần mặc định
    include __DIR__ . '/partials/banner.php';
    include __DIR__ . '/partials/danhmucsp.php';
    include __DIR__ . '/partials/giaymoi.php';
    include __DIR__ . '/partials/banchaynhat.php';
} else {
    // Trang động: chỉ hiển thị nội dung động
    if ($page === 'shop') {
        include __DIR__ . '/../sanpham/shop.php';
    } elseif ($page === 'product') {
        include __DIR__ . '/../sanpham/product-detail.php';
    } elseif ($page === 'lienhe') {
        include __DIR__ . '/../lienhe/index.php';
    } else {
        // Các trang khác sẽ được xử lý riêng
        header("Location: /NHOM4_DU_AN_1/index.php");
        exit;
    }
}

// Include footer
include __DIR__ . '/partials/fooder.php';
?>
</body>
</html> 