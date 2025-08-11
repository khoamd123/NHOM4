<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="/NHOM4_DU_AN_1/index.php" class="logo">
                        <img src="/NHOM4_DU_AN_1/public/assets/images/logo-new.jpg" 
                             alt="Shoe Shop Logo" 
                             style="height: 40px; max-width: 150px; object-fit: contain; 
                                    filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.1));">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">

                      <li><a href="/NHOM4_DU_AN_1/index.php">Trang chủ</a></li>
                      <li><a href="/NHOM4_DU_AN_1/index.php?page=shop">Cửa hàng</a></li>
                      <li><a href="/NHOM4_DU_AN_1/index.php?page=lienhe">Liên hệ</a></li>
                      
                      <?php if (isset($_SESSION['user'])): ?>
                        <li class="cart-menu-item">
                          <a href="/NHOM4_DU_AN_1/index.php?page=cart" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge">0</span>
                          </a>
                        </li>
                        <li><a href="/NHOM4_DU_AN_1/index.php?controller=order&action=myOrders">Đơn hàng của tôi</a></li>
                        <li><a href="/NHOM4_DU_AN_1/index.php?controller=user&action=logout">Đăng xuất</a></li>
                      <?php else: ?>
                        <li><a href="/NHOM4_DU_AN_1/index.php?controller=user&action=login">Đăng nhập</a></li>
                        
                      <?php endif; ?>

                  </ul>   
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
  </header>

<style>
.cart-menu-item {
  position: relative;
}

.cart-link {
  position: relative;
  display: flex !important;
  align-items: center;
  gap: 5px;
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background: #e75e8d;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  font-size: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  min-width: 20px;
}

.cart-badge[data-count="0"] {
  display: none;
}

.cart-link:hover .cart-badge {
  background: #d63384;
}

@media (max-width: 768px) {
  .cart-badge {
    width: 18px;
    height: 18px;
    font-size: 11px;
    top: -6px;
    right: -6px;
  }
}
</style>