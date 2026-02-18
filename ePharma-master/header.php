<?php
$uid = $_SESSION['user_id'] ?? '';
$user_id = $uid ? $_SESSION['user_id'] : NULL;
$gust_id = $user_id ? NULL : session_id();

if ($user_id) {
  $q = $conn->prepare("SELECT COUNT(*) FROM ep_cart where u_id = :user_id");
  $q->execute(['user_id' => $user_id]);
} else {
  $q = $conn->prepare("SELECT COUNT(*) FROM ep_cart where guest_id = :guest_id");
  $q->execute(['guest_id' => $gust_id]);
}
$count = $q->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo isset($page_title) ? $page_title : 'ePharmaEase'; ?></title>
  <link rel="icon" href="img/logo6.ico" type="image/x-icon">
  <link rel="stylesheet" href="./vendors/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="./vendors/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="./vendors/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="./vendors/nice-select/nice-select.css">
  <link rel="stylesheet" href="./vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="./vendors/owl-carousel/owl.carousel.min.css">
  <link rel="stylesheet" href="new_css/css/bootstrap.min.css">
  <!-- Mobile no. with country code -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="./css/style9.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    function confirm_order() {
      Swal.fire({
        title: 'Please Login First!',
        text: "Please Login to Save Your Cart!!!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect or AJAX call
          window.location.href = "checkout.php";

        }
      });
    }
  </script>

</head>

<body>

  <header class="header_area">
    <div class="main_menu">
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
          <a class="navbar-brand logo_h" href="index.php"><img style="object-fit: contain;width: 150px;" src="img/logo3.png" alt=""></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
            <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
              <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
              <!-- <li class="nav-item submenu dropdown">
              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Shop</a>
              <ul class="dropdown-menu">
                <li class="nav-item"><a class="nav-link" href="category.php">Shop Category</a></li>
                <li class="nav-item"><a class="nav-link" href="single-product.php">Product Details</a></li>
                <li class="nav-item"><a class="nav-link" href="checkout.php">Product Checkout</a></li>
                <li class="nav-item"><a class="nav-link" href="confirmation.php">Confirmation</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Shopping Cart</a></li>
              </ul>
            </li> -->
              <li class="nav-item"><a class="nav-link" href="category.php">Shop</a></li>

              <li class="nav-item"><a class="nav-link" href="about_us.php">About Us</a></li>
              <!-- <li class="nav-item submenu dropdown">
              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Blog</a>
              <ul class="dropdown-menu">
                <li class="nav-item"><a class="nav-link" href="about_us.php">About us</a></li>
                <li class="nav-item"><a class="nav-link" href="single-blog.html">Blog Details</a></li>
              </ul>
            </li> -->
              <!-- <li class="nav-item submenu dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                  aria-expanded="false">Pages</a>
                <ul class="dropdown-menu"> -->
                  <!-- <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li> -->
                  <!-- <li class="nav-item"><a class="nav-link" href="tracking-order.php">Tracking</a></li>
                </ul>
              </li> -->
              <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>

            <ul class="nav-shop">
              <li class="nav-item"><button><a style="text-decoration: none;" href="search_product.php"><i class="ti-search"></i></a></button></li>
              <li class="nav-item"><button id="cart_view"><i class="ti-shopping-cart"></i><span class="nav-shop__circle"><?= $count ?></span></button> </li>
              <li class="nav-item"><button id="user-btn"><i class="ti-user"></i></button> </li>

              <li class="nav-item"><a style="text-decoration: none;" class="button button-header" href="category.php">Buy Now</a></li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>
  <div class="profile-detail" id="profile_box">
    <?php

    if (isset($_SESSION['user_id'])) {

      $select_profile = $conn->prepare("SELECT * FROM ep_users WHERE u_id = :uid");
      $select_profile->execute(['uid' => $user_id]);

      if ($select_profile->rowCount() > 0) {
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    ?>
        <div class="profile">
          <img src="uploads/<?= $fetch_profile['image'] ?>" class="logo-image">
          <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
        </div>

        <div class="flex-btn">
          <a href="user_profile.php" class="btn">Profile</a>
          <a href="logout.php" class="btn"
            onclick="return confirm('Logout from this website?');">Logout</a>
        </div>

      <?php
      }
    } else {
      ?>
      <p class="name">Please login or register first!</p>
      <div class="flex-btn">
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
      </div>
    <?php } ?>
  </div>

  <div class="cart-detail" id="cart_box">

    <!-- Arrow -->
    <div class="cart-arrow"></div>
    <!-- Product -->
    <?php
    $gtotal = 0;
    if ($count > 0) {
      if ($user_id) {
        $cart = $conn->prepare("SELECT * FROM ep_cart c JOIN ep_products p ON p.p_id = c.p_id where u_id = :user_id");
        $cart->execute(['user_id' => $user_id]);
      } else {
        $cart = $conn->prepare("SELECT * FROM ep_cart c JOIN ep_products p ON p.p_id = c.p_id where guest_id = :guest_id");
        $cart->execute(['guest_id' => $gust_id]);
      }
      $fetch_cart = $cart->fetchAll(PDO::FETCH_ASSOC);

      if ($cart->rowCount() > 0) {
        foreach ($fetch_cart as $cart) { ?>
          <div class="cart-item">
            <img src="../LearnAdmin/upload/<?= $cart['image'] ?>" class="product-img">
            <div class="item-details">
              <h4><?= $cart['pname'] ?></h4>
              <p>Qty:<?= $cart['qty'] ?></p>
              <p>₹<?= $cart['price'] ?></p>
            </div>

            <!-- <a href="deletecart.php?cart_id=<?= $cart['cart_id'] ?>"><span class="delete">🗑</span></a> -->
          </div>
        <?php
          $total = $cart['qty'] * $cart['price'];
          $gtotal += $total;
        }
        ?>
        <div class="subtotal">
          <span>Subtotal:</span>
          <span>₹ <?= $gtotal ?></span>
        </div>
      <?php
      }
    } else { ?>
      <p class="text-muted" style="text-align: center;text-transform: capitalize;">Your Cart is Empty!</p>
      <img src="img/empty_cart.png" alt="empty_cart" style="width: 100px;height: 100px;margin: 0 95px 0;">
    <?php  }
    ?>

    <!-- Buttons -->
    <button class="btn-checkout"><a href="checkout.php">Checkout</a></button>
    <button class="btn-view"><a href="cart.php">View Cart</a></button>

  </div>

  <script>
    //cart hover
    const cart_btn = document.getElementById('cart_view');
    const card_box = document.getElementById('cart_box');

    cart_btn.onclick = () => {
      card_box.classList.toggle('active');
    };
    // close when clicking outside
    document.addEventListener('click', function(e) {
      if (!card_box.contains(e.target) && !cart_btn.contains(e.target)) {
        card_box.classList.remove('active');
      }
    });

    // profile block
    const userBtn = document.getElementById('user-btn');
    const profile_box = document.getElementById('profile_box');

    userBtn.onclick = () => {
      profile_box.classList.toggle('active');
    };

    // close when clicking outside
    document.addEventListener('click', function(e) {
      if (!profile_box.contains(e.target) && !userBtn.contains(e.target)) {
        profile_box.classList.remove('active');
      }
    });
  </script>