<?php
require_once("connection/db.php");
require_once('insert_cart_logic.php');
$cid = $_GET['c_id'];
$limit = 9;

// =============================
//Sorting
if (isset($_POST['filter_data']) && isset($_POST['search_data'])) {
  $search_data = $_POST['search_data'];
  $sort = $_POST['filter_data'];
  // $fprice = (int) $_POST['f_price'];;

  $orderby = "ORDER BY price DESC";
  if ($sort == "low_to_high") {
    $orderby = " ORDER BY price";
  }

  if ($sort == "high_to_low") {
    $orderby = " ORDER BY price DESC";
  }

  if ($sort == "latest") {
    $orderby = " ORDER BY p_id DESC";
  }

  $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON p.c_id = c.c_id  WHERE name LIKE '{$search_data}%' AND c.c_id = :cid {$orderby}");
  $query->execute(['cid' => $cid]);

  $fetch_Prods = $query->fetchAll(PDO::FETCH_ASSOC);
  if ($fetch_Prods) {
    foreach ($fetch_Prods as $p) {
?>
      <div class="col-md-6 col-lg-4">
        <div class="card text-center card-product">
          <form action="" method="post">
            <div class="card-product__img">
              <h6 class="card-product__price">₹<?= $p['price'] ?></h6>
              <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="product"></a>
              <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
              <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
              <ul class="card-product__imgOverlay">
                <li><button><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><i class="ti-search"></i></a></button></li>
                <li><button name="cart"><i class="ti-shopping-cart"></i></button></li>
                <!-- <li><button name="cart"><a href="cart.php?p_id=<?= $p['p_id'] ?>"><i class="ti-shopping-cart"></i></a></button></li> -->
                <!-- <li><button name="add_to_cart"><i class="ti-shopping-cart"></i></button></li> -->
                <!-- <li><button><i class="ti-heart"></i></button></li>  -->
              </ul>
            </div>
            <div class="card-body">
              <h6 class="rating"><?php
                                  for ($i = 0; $i < 5; $i++) {
                                    echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                                  }

                                  ?>
              </h6>
              <p><?= $p['category_name'] ?></p>
              <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>
            </div>
          </form>
        </div>
      </div>
    <?php

    }
  } else { ?>
    <div class="alert alert-info text-center ml-3" style="width: 95%;">
      <p>No Product Found!</p>
    </div>
<?php
  }
  exit;
}

// =============================
?>
<!--================ Start Header Menu Area =================-->
<?php
$page_title = "ePharmaEase - Category wise Products";
require_once('header.php');


try {
  if (!empty($cid)) {
    $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE status = 'Active' AND c.c_id = :cid");
    $query->execute(['cid' => $cid]);
    $fetch_category = $query->fetch(PDO::FETCH_ASSOC);
    if ($fetch_category) {
      $category_name = $fetch_category['category_name'];
      $cat_desc = $fetch_category['description'];
?>


      <!--================ End Header Menu Area =================-->

      <!-- ================ start banner area ================= -->
      <section class="blog-banner-area fade-up" id="category">
        <div class="container h-100">
          <div class="blog-banner">
            <div class="text-center">
              <h1><?= $category_name ?></h1>
              <nav aria-label="breadcrumb" class="banner-breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Category Listing</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>
      <!-- ================ end banner area ================= -->


      <!-- ================ category section start ================= -->
      <section class="section-margin--small mb-5">
        <div class="container shadow-lg" style="background: #ffffff;padding: 15px 15px 0;border-radius: 15px;">
          <div class="row ">
            
            
            <div class="col-xl-12 col-lg-8 col-md-7">
              <!-- Start Filter Bar -->
              <div class="filter-bar d-flex flex-wrap align-items-center shadow-sm">
                <div class="sorting ">
                  <select id="filter_by_status">
                    <option value="" selected="selected">Sorting</option>
                    <option value="low_to_high">Sort Price - Low to High</option>
                    <option value="high_to_low">Sort Price - High to Low</option>
                    <option value="latest">Sort by Latest</option>
                  </select>
                </div>
                <div class="sorting ">
                  <!-- <div class="item_show">
                    <?php
                    // $result = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE status = 'Active' AND c.c_id = :cid ");
                    // $result->execute(['cid' => $cid]);
                    // $count = $result->rowCount();
                    ?>
                    <p>Showing 1 - 9 of <?= $count ?> Results</p>
                  </div> -->
                  <!-- <select>
              <option value="1">Show 12</option>
              <option value="1">Show 12</option>
              <option value="1">Show 12</option>
            </select> -->
                </div>
                <div>
                  <div class="input-group filter-bar-search">
                    <input type="text" id="live_search" placeholder="Search">
                    <div class="input-group-append">
                      <button type="button"><i class="ti-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Filter Bar -->
              <!-- Start Best Seller -->
              <section class="lattest-product-area pb-40 category-list">

                <div class="description">
                  <h2 style="    color: #4a90e2;
    text-transform: capitalize;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);"><?= $category_name ?></h2>
                  <p><?= $cat_desc ?></p>
                </div>
                <div class="row mt-3" id="product-list">
                  <!-- cards  -->
                  <?php


                  if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                  } else {
                    $page = 1;
                  }
                  $offset = ($page - 1) * $limit;
                  $result = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE c.c_id = :cid AND status = 'Active' LIMIT {$offset} , {$limit}");
                  $result->execute(['cid' => $cid]);
                  // }

                  $products = $result->fetchAll(PDO::FETCH_ASSOC);
                  // if stock is Low OR out of stock
                  if ($products) {


                    foreach ($products as $p) {
                      $available_stock = $p['stock'];
                  ?>
                      <div class="col-md-6 col-lg-4">
                        <div class="card text-center card-product">
                          <form action="" method="post">
                            <div class="card-product__img">
                              <h6 class="card-product__price">₹<?= $p['price'] ?></h6>
                              <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
                              <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                              <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                              <ul class="card-product__imgOverlay">
                                <?php if ($available_stock > 1) { ?>
                                  <li><button name="cart"><i class="ti-shopping-cart"></i></button></li>
                                <?php } else { ?>
                                  <li><button disabled>Currently Unavailable</button></li>
                                <?php } ?>
                              </ul>
                            </div>

                            <div class="card-body">
                              <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>

                              <h6 class="rating"><?php
                                                  for ($i = 0; $i < 5; $i++) {
                                                    echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                                                  }

                                                  ?>
                              </h6>
                              <p style="text-transform: capitalize;"><?= $p['category_name'] ?></p>
                            </div>
                          </form>
                        </div>
                      </div>

                      <!-- End card -->
                  <?php
                    }
                  }
                  ?>

              </section>
              <!-- End Best Seller -->
            </div>
            <div class="col-xl-12 col-lg-8 col-md-7">
              <div class="pagination-wrapper">
                <ul class="ep-pagination">
                  <?php
                  try {
                    $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE c.c_id = :cid");
                    $query->execute(['cid' => $cid]);
                    $count = $query->rowCount();
                    // echo $count;
                    // die();

                    if ($count > 0) {
                      $pages = ceil($count / $limit);

                      for ($i = 1; $i <= $pages; $i++) {
                        $active = ($i == $page) ? 'active' : '';
                  ?>
                        <li><a href="category.php?page=<?= $i ?>" class="pagination_link <?= $active ?>"><?= $i ?></a></li>
                      <?php
                      }
                      if ($page < $pages) { ?>
                        <li><a href="category.php?page=<?= $page + 1 ?>" class="pagination_link next">›</a></li>

                  <?php  }
                    }
                  } catch (PDOException $e) {
                    echo $e;
                  }
                  ?>

                </ul>
              </div>

            </div>
          </div>
        </div>
      </section>
      <!-- ================ category section end ================= -->

      <!--================ Start related Product area =================-->
      <?php //require_once("related_products.php"); 
      ?>
      <!--================ end related Product area =================-->

      <!-- ================ Subscribe section start ================= -->
      <section class="subscribe-position">
        <div class="container">
          <div class="subscribe text-center">
            <h3 class="subscribe__title">Get Update From Anywhere</h3>
            <p>Bearing Void gathering light light his eavening unto dont afraid</p>
            <div id="mc_embed_signup">
              <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscribe-form form-inline mt-5 pt-1">
                <div class="form-group ml-sm-auto">
                  <input class="form-control mb-1" type="email" name="EMAIL" placeholder="Enter your email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Email Address '">
                  <div class="info"></div>
                </div>
                <button class="button button-subscribe mr-auto mb-1" type="submit">Subscribe Now</button>
                <div style="position: absolute; left: -5000px;">
                  <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
                </div>

              </form>
            </div>

          </div>
        </div>
      </section>
      <!-- ================ Subscribe section end ================= -->


      <!--================ Start footer Area  =================-->
      <?php require_once("footer.php"); ?>
      <!--================ End footer Area  =================-->
<?php
    }
  }
} catch (PDOException $e) {
  echo $e;
}
?>

<script>
  //on checkbox selection product listing
  $('input[name="category"]').on('change', function() {
    let category_id = $(this).val();
    // alert(category_id);
    $.ajax({
      url: "filter_products.php",
      type: "POST",
      data: {
        category_id: category_id
      },
      success: function(data) {
        $('#product-list').html(data);
      }
    });
  });

  // Searching & Sorting Functionality
  $(document).ready(function() {

    function load_preoduct() {
      let search_data = $('#live_search').val();
      let filter_data = $('#filter_by_status').val();
      // if(search_data != ''){
      //   alert(search_data);
      // }
      $.ajax({
        url: window.location.href,
        method: "POST",
        data: {
          search_data: search_data,
          filter_data: filter_data
        },

        success: function(data) {
          $("#product-list").html(data).show();
        }
      });
    }

    $(document).on("keyup", "#live_search", function() {
      load_preoduct();
    });

    $(document).on("change", "#filter_by_status", function() {
      load_preoduct();
    });
  });
</script>