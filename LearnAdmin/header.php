<!-- Search -->
<div class="d-flex align-items-center">
    <div class="collapse-sidebar me-3 d-none d-lg-block text-color-1"><span><i class="fa-solid fa-bars font-size-24"></i></span></div>
    <div class="menu-toggle me-3 d-block d-lg-none text-color-1"><span><i class="fa-solid fa-bars font-size-24"></i></span></div>
    <div class="d-none d-md-block d-lg-block">

        <!-- <div class="input-group flex-nowrap">
            <span class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
            <input type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search anything" aria-label="Username" aria-describedby="addon-wrapping">
        </div> -->


    </div>
    <!-- <div class="">
        <a href="search_page.php"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></a>
    </div> -->
</div>
<div class="d-flex align-items-center">
    <ul class="nav d-flex align-items-center">
        <!-- Messages Dropdown -->
        <!-- Notifications Dropdown -->

        <!-- User Profile -->
        <li class="nav-item dropdown user-profile">
            <a class="nav-link dropdown-toggle d-flex align-items-center"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <span class="user-avatar me-0 me-lg-3">
                    <?php
                    $u = ucfirst($_SESSION['user']);
                    echo substr($u, 0, 1);
                    ?></span>

                <div class="d-none d-lg-block text-start">
                    <span class="d-block auth-role">Administrator</span>
                    <span class="auth-name"><?php echo $_SESSION['user']; ?></span>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end mt-3">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li onclick="return confirm('Do You Really want to Log Out???');"><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</div>
