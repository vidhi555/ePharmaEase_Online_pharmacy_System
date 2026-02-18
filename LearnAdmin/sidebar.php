<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <!-- Sidebar -->
    <div class="sidebar-header">
        <div class="lg-logo"><a href="index.php"><img src="assets/images/logo9.png" alt="logo large" style="object-fit: contain; width: 100%;"></a></div>
        <div class="sm-logo"><a href="index.php"><img src="./assets/images/logo6.ico" alt="logo small"></a></div>
    </div>
    <div class="sidebar-body  custom-scrollbar">
        <ul class="sidebar-menu">
            <li><a href="index.php" class=" sidebar-link <?= $current_page == 'index.php' ? 'active' : '' ?>"><i class="fa-solid fa-house"></i>
                    <p>Dashboard</p>
                </a></li>
            <li><a href="category.php" class="sidebar-link <?= $current_page == 'category.php' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i>
                    <p>Category</p>
                </a></li>
            <li><a href="products.php" class=" sidebar-link <?= $current_page == 'products.php' ? 'active' : '' ?>"><i class="fa-solid fa-boxes"></i>
                    <p>Products</p>
                </a></li>
            <li><a href="orders.php" class="sidebar-link <?= $current_page == 'orders.php' ? 'active' : '' ?>"><i class="fa-solid fa-truck"></i>
                    <p>Orders</p>
                </a></li>
            <li><a href="customers.php" class="sidebar-link <?= $current_page == 'customers.php' ? 'active' : '' ?>"><i class="fas fa-users"></i>
                    <p>Customers</p>
                </a></li>
            <li><a href="message_report.php" class=" sidebar-link <?= $current_page == 'message_report.php' ? 'active' : '' ?>"><i class="fa-solid fas fa-comments"></i>
                    <p>Contact Message</p>
                </a></li>
            <li><a href="userreview.php" class=" sidebar-link <?= $current_page == 'userreview.php' ? 'active' : '' ?>"><i class="fa-solid fa-thumbs-up"></i>
                    <p>Reviewes</p>
                </a></li>

            <!-- <li><a href="#" class=" sidebar-link submenu-parent"><i class="fa-solid fa-list"></i>
                    <p>Components <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="form.html" class="submenu-link"><i class="fa-solid fa-circle me-4 font-size-12"></i>
                            <p class="m-0">Form Element</p>
                        </a></li>
                </ul>
            </li> -->
        </ul>
    </div>
</div>