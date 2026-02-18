<?php
require_once("connection/db.php");
require_once('insert_cart_logic.php');

$limit = 9;
?>
<!--================ Start Header Menu Area =================-->
<?php
$page_title = "ePharmaEase - Shop Category";
require_once('header.php')
?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
  <div class="container h-100">
    <div class="blog-banner">
      <div class="text-center">
        <h1>Shop</h1>
        <nav aria-label="breadcrumb" class="banner-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Medicines Category</li>
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
    <div class="row">
      <div class="col-xl-3 col-lg-4 col-md-5">
        <div class="sidebar-categories">
          <div class="head">Browse Categories</div>
          <ul class="main-categories">
            <li class="common-filter">
              <form action="" method="get">
                <ul>
                  <?php
                  $query = $conn->prepare("SELECT * FROM ep_category WHERE 1 ORDER BY category_name");
                  $query->execute();
                  $fetch_cname = $query->fetchAll(PDO::FETCH_ASSOC);
                  // $selected_cat = $_GET['c_id'] ?? '';

                  foreach ($fetch_cname as $cname) { ?>
                    <li class="filter-list">
                      <!-- <input class="pixel-radio" type="radio" value="<?= $cname['c_id'] ?>" onchange="this.form.submit()" id="cname" name="c_id" <?= ($selected_cat == $cname['c_id']) ? 'checked' : '' ?>> -->
                      <input type="radio" class="pixel-radio" name="category" value="<?= $cname['c_id']; ?>" id="<?= $cname['c_id'] ?>">
                      <label for="<?= $cname['c_id'] ?>"><?= $cname['category_name'] ?></label>
                    </li>
                  <?php  }
                  ?>
                  <!-- <li class="filter-list"><input class="pixel-radio" type="radio" id="men" name="brand"><label for="men">Men<span> (3600)</span></label></li>
                    <li class="filter-list"><input class="pixel-radio" type="radio" id="women" name="brand"><label for="women">Women<span> (3600)</span></label></li>
                    <li class="filter-list"><input class="pixel-radio" type="radio" id="accessories" name="brand"><label for="accessories">Accessories<span> (3600)</span></label></li>
                    <li class="filter-list"><input class="pixel-radio" type="radio" id="footwear" name="brand"><label for="footwear">Footwear<span> (3600)</span></label></li>
                    <li class="filter-list"><input class="pixel-radio" type="radio" id="bayItem" name="brand"><label for="bayItem">Bay item<span> (3600)</span></label></li>
                    <li class="filter-list"><input class="pixel-radio" type="radio" id="electronics" name="brand"><label for="electronics">Electronics<span> (3600)</span></label></li>
                    <li class="filter-list"><input class="pixel-radio" type="radio" id="food" name="brand"><label for="food">Food<span> (3600)</span></label></li> -->
                </ul>
              </form>
            </li>
          </ul>
        </div>
        <div class="sidebar-filter shadow-sm">
          <div class="top-filter-head">Product Filters</div>
          <!-- <div class="common-filter">
            <div class="head">Brands</div>
            <form action="#" method="post">
              <ul>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="apple" name="brand"><label for="apple">Apple<span>(29)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="asus" name="brand"><label for="asus">Asus<span>(29)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="gionee" name="brand"><label for="gionee">Gionee<span>(19)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="micromax" name="brand"><label for="micromax">Micromax<span>(19)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="samsung" name="brand"><label for="samsung">Samsung<span>(19)</span></label></li>
              </ul>
            </form>
          </div>
          <div class="common-filter">
            <div class="head">Color</div>
            <form action="#">
              <ul>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="black" name="color"><label for="black">Black<span>(29)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="balckleather" name="color"><label for="balckleather">Black
                    Leather<span>(29)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="blackred" name="color"><label for="blackred">Black
                    with red<span>(19)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="gold" name="color"><label for="gold">Gold<span>(19)</span></label></li>
                <li class="filter-list"><input class="pixel-radio" type="radio" id="spacegrey" name="color"><label for="spacegrey">Spacegrey<span>(19)</span></label></li>
              </ul>
            </form>
          </div> -->
          <div class="common-filter shadow-sm mb-4">

            <div class="price-filter-card">
              <h4 class="filter-title">Filter By Price</h4>

              <div class="slidecontainer">
                <input type="range" min="20" max="4000" step="5" value="2000" class="slider" id="priceRange">
              </div>

              <div class="price-value">₹20 - ₹ <span id="priceValue">2500</span></div>

              <button class="filter-btn" type="button" onclick="load_price()">Filter</button>
            </div>

            <script>
              const slider = document.getElementById("priceRange");
              const priceText = document.getElementById("priceValue");

              slider.addEventListener("input", function() {
                priceText.innerText = this.value;
                // load_price();
              });

              function load_price() {
                const f_price = slider.value;
                $.ajax({
                  url: "load_products_ajax.php",
                  method: "POST",
                  data: {
                    max_Price: f_price
                  },

                  success: function(data) {
                    $("#product-list").html(data);
                  }
                });
              }
            </script>


          </div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8 col-md-7">
        <!-- Start Filter Bar -->
        <div class="filter-bar d-flex flex-wrap align-items-center shadow-sm">
          <div class="sorting">
            <select id="filter_by_status">
              <option value="" selected="selected">Sorting</option>
              <option value="low_to_high">Sort Price - Low to High</option>
              <option value="high_to_low">Sort Price - High to Low</option>
              <option value="latest">Sort by Latest</option>
            </select>
          </div>
          <div class="sorting mr-auto">
            <div class="item_show">
              <?php
              $result = $conn->prepare("SELECT * FROM ep_products");
              $result->execute();
              $count = $result->rowCount();
              ?>
              <!-- <p>Showing 1 - 9 of <?= $count ?> Results</p> -->
            </div>
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

          <div class="row" id="product-list">
            <!-- cards  -->
            <?php


            if (isset($_GET['page'])) {
              $page = $_GET['page'];
            } else {
              $page = 1;
            }
            $offset = ($page - 1) * $limit;
            $result = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE status = 'Active' ORDER BY category_name LIMIT {$offset} , {$limit}");
            $result->execute();
            // }

            $products = $result->fetchAll(PDO::FETCH_ASSOC);
            // if stock is Low OR out of stock
            if ($products) {


              foreach ($products as $p) {
                // rate average
                $rate_avg = $conn->prepare("SELECT AVG(rate) as rating_avg FROM ep_review WHERE p_id = :pid");
                $rate_avg->execute(['pid' => $p['p_id']]);
                $fetch_avg = $rate_avg->fetch(PDO::FETCH_ASSOC);

                $available_stock = $p['stock'];
            ?>
                <!-- <div class="col-md-6 col-lg-4">
                <a href="single-product.php?p_id=<?= $p['p_id'] ?>">  
                <div class="card text-center card-product">
                    <form action="" method="post">
                      <div class="card-product__img">
                        <h6 class="card-product__price">₹<?= $p['price'] ?></h6>

                        <img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="product">
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

                        <h6 class="rating"><?php
                                            for ($i = 0; $i < $fetch_avg['rating_avg']; $i++) {
                                              echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                                            }

                                            ?>
                        </h6>
                        <p><?= $p['category_name'] ?></p>
                        <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>
                      </div>
                    </form>
                  </div>
                  </a>
                </div> -->
                <div class="col-md-6 col-lg-4">
                  <div class="card text-center card-product">
                    <form action="" method="post">
                      <div class="card-product__img">
                        <h6 class="card-product__price">₹<?= $p['price'] ?></h6>
                        <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
                        <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                        <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                        <ul class="card-product__imgOverlay">
                          <!-- <li><button><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><i class="ti-search"></i></a></button></li> -->
                          <li><button name="cart"><i class="ti-shopping-cart"></i></button></li>
                          <!-- <li><button><a href="cart.php?p_id=<?= $p['p_id'] ?>"><i class="ti-shopping-cart"></i></a></button></li> -->
                          <!-- <li><button><i class="ti-heart"></i></button></li> -->
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

            <div class="col-xl-12 col-lg-8 col-md-7">
              <div class="pagination-wrapper">
                <ul class="ep-pagination">
                  <?php
                  try {
                    $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id");
                    $query->execute();
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
        </section>
        <!-- End Best Seller -->
      </div>
    </div>
  </div>
</section>
<!-- ================ category section end ================= -->

<!--================ Start related Product area =================-->

<?php $pid = 24;
require_once("related_products.php"); ?>
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


