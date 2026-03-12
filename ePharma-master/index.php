<?php
require_once("connection/db.php");
// require_once('session.php');
require_once('insert_cart_logic.php');
?>

<?php
$page_title = "Home";
require_once('header.php');  ?>
<!--================ End Header Menu Area =================-->

<main class="site-main">
  <!-- ================= Hero Slider Start ================= -->
  <section class="ep-hero-slider" data-aos="fade-in">
    <div id="epHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" style="">

      <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="4000">
          <img src="img/gallery/pic3.jpg" class="ep-carousel-img" alt="">
          <div class="carousel-caption ep-caption">
            <h2>Best health Care medicines</h2>
            <p>Medicine: the art of healing and the science of caring.</p>
          </div>
        </div>

        <div class="carousel-item" data-bs-interval="4000">
          <img src="img/gallery/pic2.jpg" class="ep-carousel-img" alt="">
          <div class="carousel-caption ep-caption">
            <h2>Medicine is the key to unlock a healthier you.</h2>
            <p>Medicine heals the body, music heals the soul.</p>
          </div>
        </div>

        <div class="carousel-item" data-bs-interval="4000">
          <img src="img/gallery/pic1.jpg" class="ep-carousel-img" alt="">
          <div class="carousel-caption ep-caption">
            <h2>Choose medicine, choose life.</h2>
            <p>Stay strong and trust the healing power of medicine.</p>
          </div>
        </div>

      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#epHeroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>

      <button class="carousel-control-next" type="button" data-bs-target="#epHeroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>

    </div>
  </section>

  <!-- ================= Hero Slider End ================= -->

  <!--================ Hero Carousel start =================-->
  <section class="section-margin mt-5 "  >
    <div class="section-intro pb-60px text-center">
      <!-- <img src="./img/abc.png" width="80" height="80" alt=""> -->
      <img data-aos="fade-up" src="img/logo6.png" alt="logo" style="margin-bottom: -42px;width: 100px;height: 80px;">

      <h2 data-aos="fade-up"><span class="section-intro__style">Popular Health Categories</span></h2>
      
    </div>
    <div class="owl-carousel owl-theme hero-carousel" id="categoryCarousel">
      <?php
      try {
        $query = $conn->prepare("SELECT * FROM ep_category ORDER BY category_name");
        $query->execute();
        $fetch_category = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($fetch_category) {
          foreach ($fetch_category as $cat) {
      ?>
            <div class="hero-carousel__slide">
              <img src="../LearnAdmin/All_images_uploads/<?= $cat['cat_image'] ?>" alt="" class="img-fluid">
              <a href="category_wise_listing.php?c_id=<?= $cat['c_id'] ?>" class="hero-carousel__slideOverlay">
                <i class="fas fa-heart"></i><h3><?= $cat['category_name'] ?></h3>
              </a>
            </div>
      <?php
          }
        }
      } catch (PDOException $e) {
        echo $e;
      }
      ?>
    </div>
    <!-- <div class="carousel-nav">
      <button class="carousel-prev">
        <i class="fa fa-chevron-left"></i>
      </button>
      <button class="carousel-next">
        <i class="fa fa-chevron-right"></i>
      </button>
    </div> -->


  </section>

  <!--================ Hero Carousel end =================-->

  <!--================ Discount advertise section Start =================-->
  <section class="section-margin calc-60px mt-3" style="background: linear-gradient(135deg, #c9f0f6, #d2e9f9);padding: 50px 10px;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">
          <div class="box left-item" data-aos="fade-up">
            <img class="img-fluid" src="./img/gallery/multivitamin-shield-icon-concept-inspiration-260nw-2131410211.png" alt="">
            <h3>15% off</h3>
            <p>Best Health care Products..</p>
            <p>Vitamins and Suppliments</p>
            <a class="shop-btn mt-3 mt-xl-4" href="category.php" style="box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);">Shop Now</a>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="box right-item" data-aos="fade-up">
            <img class="img-fluid" src="./img/Introducing-Korean-Skin-Care-removebg-preview (1).png" alt="">
            <h3> 35% off </h3>
            <p>Skin care products..</p>
            <p>Glow begins with great skincare.</p>
            <a class="shop-btn mt-3 mt-xl-4" href="category.php" style="box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);">Shop Now</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================ Discount advertise section end =================-->


  <!-- ================ trending product section start ================= -->
  <section class="section-margin calc-60px" style="background: aliceblue;">
    <div class="container">
      <div class="section-intro pb-60px text-center" data-aos="fade-up">
        <img data-aos="fade-up" src="img/logo6.png" alt="logo" style="margin-bottom: -42px;width: 100px;height: 80px;">
        <h2> <span class="section-intro__style">Most Popular Medicines</span></h2>
      </div>
      <div class="row g-4">
        <?php
        

        $result = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id LIMIT 4");
        $result->execute();
        $products = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $p) { 
        // rate average
        $rate_avg = $conn->prepare("SELECT AVG(rate) as rating_avg FROM ep_review WHERE p_id = :pid");
        $rate_avg->execute(['pid' => $p['p_id']]);
        $fetch_avg = $rate_avg->fetch(PDO::FETCH_ASSOC);

        $available_stock = $p['stock'];
        ?>
          <!-- <div class="col-md-6 col-lg-3 col-xl-4"> -->
          <div class="col-md-6 col-lg-3 col-xl-3" data-aos="fade-up">
            <div class="card text-center card-product">
              <form action="" method="post">
                <div class="card-product__img">
                  <h6 class="card-product__price">$<?= $p['price'] ?></h6>
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/All_images_uploads/<?= $p['image'] ?>" alt=""></a>
                  <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                  <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                  <ul class="card-product__imgOverlay">
                    <?php if($available_stock<5){
                             $alert = "<i class='fas fa-exclamation-triangle'></i> Only few items left";
                          }elseif($available_stock <= 0 ){
                            $alert = "<i class='far fa-exclamation-triangle'></i> Out Of Stock";
                          }else{
                            $alert = "<i class='ti-shopping-cart'></i>";
                          } ?>
                          <li><button name="cart"><?= $alert ?></button></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>

                  <h6 class="rating"><?php
                                      for ($i = 0; $i < $fetch_avg['rating_avg']; $i++) {
                                        echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                                      }

                                      ?>
                  </h6>
                  <p style="text-transform: capitalize;"><?= $p['category_name'] ?></p>
                </div>
              </form>
            </div>
          </div>
        <?php  } ?>
        <div class="shop_btn">
          <a class="button button--active mt-3 mt-xl-4" href="category.php">Shop Now</a>
        </div>
      </div>
  </section>
  <!-- ================ trending product section end ================= -->

  <!-- ================ offer section start ================= -->
  <section class="offer" id="parallax-1" data-anchor-target="#parallax-1" data-300-top="background-position: 20px 30px" data-top-bottom="background-position: 0 20px" data-aos="fade-in">
    <div class="container">
      <div class="row">
        <div class="col-xl-5">
          <div class="offer__content">
            <h3>Up To 30% Off</h3>
            <h4>Antibiotics Pills</h4>
            <p>Healing begins with the right medicine.</p>
            <a class="button button--active mt-3 mt-xl-4" href="category.php">Shop Now</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ offer section end ================= -->

  <!-- ================ Best Selling item  carousel ================= -->
  <section class="section-margin calc-60px" style="background: linear-gradient(135deg, #c9f0f6, #d2e9f9);">
    <div class="container">
      <div class="section-intro pb-60px text-center" data-aos="fade-up">
        <img data-aos="fade-up" src="img/logo6.png" alt="logo" style="margin-bottom: -42px;width: 100px;height: 80px;">
        <h2> <span class="section-intro__style">Best Selling Healthcare Products</span></h2>
      </div>
      <div class="owl-carousel owl-theme" id="bestSellerCarousel">
        <?php
        try {
          $query = $conn->prepare("SELECT p.*, c.category_name, SUM(oi.qty) AS total_sold
FROM ep_products p
JOIN ep_orders_items oi ON p.p_id = oi.p_id
JOIN ep_category c ON p.c_id = c.c_id
GROUP BY p.p_id
ORDER BY total_sold DESC
LIMIT 10");
          $query->execute();
          $result = $query->fetchAll(PDO::FETCH_ASSOC);
          foreach ($result as $p) {
             // rate average
                $rate_avg = $conn->prepare("SELECT AVG(rate) as rating_avg FROM ep_review WHERE p_id = :pid");
                $rate_avg->execute(['pid' => $p['p_id']]);
                $fetch_avg = $rate_avg->fetch(PDO::FETCH_ASSOC);

                $available_stock = $p['stock'];
        ?>

            <!-- <div class="card text-center card-product" data-aos="fade-up">
              <form action="" method="post">
                <div class="card-product__img">
                  <h6 class="card-product__price">$<?= $p['price'] ?></h6>
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/All_images_uploads/<?= $p['image'] ?>" alt=""></a>
                  <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                  <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                  <ul class="card-product__imgOverlay">
                     <?php if($available_stock<5){
                             $alert = "<i class='fas fa-exclamation-triangle'></i> Only few items left";
                          }elseif($available_stock <= 0 ){
                            $alert = "<i class='far fa-exclamation-triangle'></i> Out Of Stock";
                          }else{
                            $alert = "<i class='ti-shopping-cart'></i>";
                          } ?>
                          <li><button name="cart"><?= $alert ?></button></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>

                  <h6 class="rating"><?php
                                      for ($i = 0; $i < $fetch_avg['rating_avg']; $i++) {
                                        echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                                      }

                                      ?>
                  </h6>
                  <p style="text-transform: capitalize;"><?= $p['category_name'] ?></p>
                </div>
              </form>
            </div> -->

            <div class="card text-center card-product">
              <form action="" method="post">
                <div class="card-product__img">
                  <h6 class="card-product__price">$<?= $p['price'] ?></h6>
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/All_images_uploads/<?= $p['image'] ?>" alt=""></a>
                  <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                  <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                  <ul class="card-product__imgOverlay">
                    <?php if($available_stock<5){
                             $alert = "<i class='fas fa-exclamation-triangle'></i> Only few items left";
                          }elseif($available_stock <= 0 ){
                            $alert = "<i class='far fa-exclamation-triangle'></i> Out Of Stock";
                          }else{
                            $alert = "<i class='ti-shopping-cart'></i>";
                          } ?>
                          <li><button name="cart"><?= $alert ?></button></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>

                  <h6 class="rating"><?php
                                      for ($i = 0; $i < $fetch_avg['rating_avg']; $i++) {
                                        echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                                      }

                                      ?>
                  </h6>
                  <p style="text-transform: capitalize;"><?= $p['category_name'] ?></p>
                </div>
              </form>
            </div>
          
        <?php
          }
        } catch (PDOException $ex) {
          echo "Error: $ex";
        }
        ?>



      </div>
  </section>
  <!-- ================ Best Selling item  carousel end ================= -->
<!-- ===================Companies Names ========================= -->
  <section class="companies-section">
    <div class="container">
      <div class="section-intro pb-60px text-center" data-aos="fade-up">
        <img data-aos="fade-up" src="img/logo6.png" alt="logo" style="margin-bottom: -42px;width: 100px;height: 80px;">
        <h2> <span class="section-intro__style">Our Trusted Pharma Partners</span></h2>
      </div>
      <div class="company-logos" data-aos="fade-up">
        <div class="company-card">
          <img src="img/gallery/company4.png" alt="Healthcare Logo">
          <p>Healthcare</p>
        </div>
        <div class="company-card">
          <img src="img/gallery/company3.png" alt="HealthLife Logo">
          <p>HealthLife</p>
        </div>
        <div class="company-card">
          <img src="img/gallery/company2.png" alt="Medical Plus Logo">
          <p>Medical Plus</p>
        </div>
        <div class="company-card">
          <img src="img/gallery/company1.png" alt="Healthy Logo">
          <p>HealthCure</p>
        </div>
        <div class="company-card">
          <img src="img/gallery/company4.png" alt="Brand Logo">
          <p>MediCare</p>
        </div>
        
        <div class="company-card">
          <img src="img/gallery/company4.png" alt="Brand Logo">
          <p>Apollo</p>
        </div>
      </div>
    </div>
  </section>
  <!-- ===================Companies Names ========================= -->
  
  <!-- ================ Blog section start ================= -->
  <!-- <section class="blog">
      <div class="container">
        <div class="section-intro pb-60px">
          <p>Popular Item in the market</p>
          <h2>Latest <span class="section-intro__style">News</span></h2>
        </div>

        <div class="row">
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="card card-blog">
              <div class="card-blog__img">
                <img class="card-img rounded-0" src="img/blog/blog1.png" alt="">
              </div>
              <div class="card-body">
                <ul class="card-blog__info">
                  <li><a href="#">By Admin</a></li>
                  <li><a href="#"><i class="ti-comments-smiley"></i> 2 Comments</a></li>
                </ul>
                <h4 class="card-blog__title"><a href="single-blog.html">The Richland Center Shooping News and weekly shooper</a></h4>
                <p>Let one fifth i bring fly to divided face for bearing divide unto seed. Winged divided light Forth.</p>
                <a class="card-blog__link" href="#">Read More <i class="ti-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="card card-blog">
              <div class="card-blog__img">
                <img class="card-img rounded-0" src="img/blog/blog2.png" alt="">
              </div>
              <div class="card-body">
                <ul class="card-blog__info">
                  <li><a href="#">By Admin</a></li>
                  <li><a href="#"><i class="ti-comments-smiley"></i> 2 Comments</a></li>
                </ul>
                <h4 class="card-blog__title"><a href="single-blog.html">The Shopping News also offers top-quality printing services</a></h4>
                <p>Let one fifth i bring fly to divided face for bearing divide unto seed. Winged divided light Forth.</p>
                <a class="card-blog__link" href="#">Read More <i class="ti-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="card card-blog">
              <div class="card-blog__img">
                <img class="card-img rounded-0" src="img/blog/blog3.png" alt="">
              </div>
              <div class="card-body">
                <ul class="card-blog__info">
                  <li><a href="#">By Admin</a></li>
                  <li><a href="#"><i class="ti-comments-smiley"></i> 2 Comments</a></li>
                </ul>
                <h4 class="card-blog__title"><a href="single-blog.html">Professional design staff and efficient equipment you’ll find we offer</a></h4>
                <p>Let one fifth i bring fly to divided face for bearing divide unto seed. Winged divided light Forth.</p>
                <a class="card-blog__link" href="#">Read More <i class="ti-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->
  <!-- ================ Blog section end ================= -->

  <!-- ================ Subscribe section start ================= -->
  <!-- <section class="subscribe-position">
      <div class="container">
        <div class="subscribe text-center">
          <h3 class="subscribe__title">Get Update From Anywhere</h3>
          <p>Bearing Void gathering light light his eavening unto dont afraid</p>
          <div id="mc_embed_signup">
            <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscribe-form form-inline mt-5 pt-1">
              <div class="form-group ml-sm-auto">
                <input class="form-control mb-1" type="email" name="EMAIL" placeholder="Enter your email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Email Address '" >
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
    </section> -->
  <!-- ================ Subscribe section end ================= -->
<?php require_once('features.php') ?>



</main>


<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>
<!--================ End footer Area  =================-->