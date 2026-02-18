<?php
require_once("connection/db.php");
require_once('insert_cart_logic.php');
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
        <h1>Healthcare Categories</h1>
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
  <div class="container">
    <div class="row shadow-sm" >
      <div class="col-xl-3 col-lg-4 col-md-5">
        <div class="sidebar-categories">
          <div class="head">Browse Categories</div>
          <ul class="main-categories">
            <li class="common-filter">
              <form action="" method="get">
                <ul>
                  <?php
                  $query = $conn->prepare("SELECT * FROM ep_category WHERE 1");
                  $query->execute();
                  $fetch_cname = $query->fetchAll(PDO::FETCH_ASSOC);
                  // $selected_cat = $_GET['c_id'] ?? '';

                  foreach ($fetch_cname as $cname) { ?>
                    <li class="filter-list">
                      <!-- <input class="pixel-radio" type="radio" value="<?= $cname['c_id'] ?>" onchange="this.form.submit()" id="cname" name="c_id" <?= ($selected_cat == $cname['c_id']) ? 'checked' : '' ?>> -->
                      <input type="radio" class="pixel-radio" name="category" value="<?= $cname['c_id']; ?>">
                      <label for="cname"><?= $cname['category_name'] ?></label>
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
        <div class="sidebar-filter">
          <div class="top-filter-head">Product Filters</div>
          <div class="common-filter">
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
          </div>
          <div class="common-filter">
            <div class="head">Price</div>
            <div class="price-range-area">
              <div id="price-range"></div>
              <div class="value-wrapper d-flex">
                <div class="price">Price:</div>
                <span>$</span>
                <div id="lower-value"></div>
                <div class="to">to</div>
                <span>$</span>
                <div id="upper-value"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8 col-md-7">
        <!-- Start Filter Bar -->
        <div class="filter-bar d-flex flex-wrap align-items-center">
          <div class="sorting">
            <select id="filter_by_status">
              <option value="" selected="selected">Sorting</option>
              <option value="low_to_high">Sort Price - Low to High</option>
              <option value="high_to_low">Sort Price - High to Low</option>
              <option value="latest">Sort by Latest</option>
            </select>
          </div>
          <div class="sorting mr-auto">
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
          
          <div class="row" id="product-list" >
            <!-- cards  -->
            <?php

            $result = $conn->prepare("
          SELECT * 
          FROM ep_products p 
          JOIN ep_category c ON c.c_id = p.c_id 
          LIMIT 9");
            $result->execute();
            // }

            $products = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($products as $p) {

            ?>
              <div class="col-md-6 col-lg-4" >
                
                <div class="card text-center card-product">
                  <form action="" method="post">
                    <div class="card-product__img">
                      <h6 class="card-product__price">₹<?= $p['price'] ?></h6>
                      <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="product"></a>
                      <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                      <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                      <ul class="card-product__imgOverlay">
                        <!-- <li><button><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><i class="ti-search"></i></a></button></li> -->
                        <li><button name="cart"><i class="ti-shopping-cart"></i></button></li>
                        <!-- <li><button name="cart"><a href="cart.php?p_id=<?= $p['p_id'] ?>"><i class="ti-shopping-cart"></i></a></button></li> -->
                        <!-- <li><button name="add_to_cart"><i class="ti-shopping-cart"></i></button></li> -->
                        <!-- <li><button><i class="ti-heart"></i></button></li> -->
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
              <!-- End card -->
            <?php
            }
            ?>

        </section>
        <!-- End Best Seller -->
      </div>
      <div class="col-xl-12 col-lg-8 col-md-7"> 
        <div class="pagination-wrapper">
          <ul class="ep-pagination">
              <li><a href="" class="pagination_link active" id="1">1</a></li>
              <li><a href="" class="pagination_link" id="2">2</a></li>
              <li><a href="" class="pagination_link" id="3">3</a></li>
              <li><a href="" class="pagination_link next">›</a></li>
          </ul>
      </div>

      </div>
    </div>
  </div>
</section>
<!-- ================ category section end ================= -->

<!-- ================ top product area start ================= -->
<section class="related-product-area">
  <div class="container">
    <div class="section-intro pb-60px">
      <p>Popular Item in the market</p>
      <h2>Top <span class="section-intro__style">Product</span></h2>
    </div>
    <div class="row mt-30">
      <!-- Product has less than 100 -->
      <div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
        <div class="single-search-product-wrapper">
          <?php
          try {
            $result = $conn->prepare("SELECT * FROM ep_products WHERE price <= 100 LIMIT 3");
            $result->execute();

            $fetch_p = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetch_p as $p) { ?>
              <div class="single-search-product d-flex">
                <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="shadow-sm" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
                <div class="desc">
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>" class="title"><?= $p['name'] ?></a>
                  <div class="price">₹<?= $p['price'] ?></div>
                </div>
              </div>
          <?php   }
          } catch (PDOException $e) {
            echo "Error:$e";
          }
          ?>
        </div>
      </div>

      <div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
        <div class="single-search-product-wrapper">
          <?php
          try {
            $result = $conn->prepare("SELECT * FROM ep_products WHERE price >= 100 AND price <= 200 LIMIT 3");
            $result->execute();

            $fetch_p = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetch_p as $p) { ?>
              <div class="single-search-product d-flex">
                <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="shadow-sm" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
                <div class="desc">
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>" class="title"><?= $p['name'] ?></a>
                  <div class="price">₹<?= $p['price'] ?></div>
                </div>
              </div>

          <?php   }
          } catch (PDOException $e) {
            echo "Error:$e";
          }
          ?>
        </div>
      </div>

      <div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
        <div class="single-search-product-wrapper">
          <?php
          try {
            $result = $conn->prepare("SELECT * FROM ep_products WHERE price >= 200 AND price <= 300 LIMIT 3");
            $result->execute();

            $fetch_p = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetch_p as $p) { ?>
              <div class="single-search-product d-flex">
                <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="shadow-sm" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
                <div class="desc">
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>" class="title"><?= $p['name'] ?></a>
                  <div class="price">₹<?= $p['price'] ?></div>
                </div>
              </div>

          <?php   }
          } catch (PDOException $e) {
            echo "Error:$e";
          }
          ?>
        </div>
      </div>

      <div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
        <div class="single-search-product-wrapper">
          <?php
          try {
            $result = $conn->prepare("SELECT * FROM ep_products WHERE price >= 300 AND price <= 400 LIMIT 3");
            $result->execute();

            $fetch_p = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetch_p as $p) { ?>
              <div class="single-search-product d-flex">
                <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="shadow-sm" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
                <div class="desc">
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>" class="title"><?= $p['name'] ?></a>
                  <div class="price">₹<?= $p['price'] ?></div>
                </div>
              </div>

          <?php   }
          } catch (PDOException $e) {
            echo "Error:$e";
          }
          ?>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- ================ top product area end ================= -->

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


<script>
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

  $(document).ready(function(){
    function load_preoduct(){
      let search_data = $('#live_search').val();
      let filter_data = $('#filter_by_status').val();

      // if(search_data != ''){
      //   alert(search_data);
      // }
      $.ajax({
        url:"search_filter_category_page.php",
        method:"POST",
        data:{
          search_data:search_data,
          filter_data:filter_data
        },

        success:function(data){
          $("#product-list").html(data).show();
        }
      });
    }

    $(document).on("keyup","#live_search",function(){
      load_preoduct();
    });

    $(document).on("change","#filter_by_status",function(){
      load_preoduct();
    });
  });
</script>