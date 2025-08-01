<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="/NHOM4_DU_AN_1/index.php" class="logo">
                        <span style="font-size: 24px; font-weight: bold; color: #e75e8d;">SHOE SHOP</span>
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">

                      <li><a href="/NHOM4_DU_AN_1/index.php">Home</a></li>
                      <li><a href="/NHOM4_DU_AN_1/index.php?page=shop">Our Shop</a></li>
                      <li><a href="/NHOM4_DU_AN_1/index.php?page=lienhe">Contact Us</a></li>
                      
                      <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="/NHOM4_DU_AN_1/client/index.php?controller=user&action=logout">Logout</a></li>
                      <?php else: ?>
                        <li><a href="/NHOM4_DU_AN_1/client/index.php?controller=user&action=login">Login</a></li>
                        
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