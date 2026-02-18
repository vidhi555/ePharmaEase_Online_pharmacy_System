<?php
require_once('connection/db.php');
$page_title = "Home";
require_once('header.php');
?>

<main class="site-main">

<!-- ================= Hero Slider Start ================= -->
<section class="ep-hero-slider">
  <div id="epHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" style="">

    <div class="carousel-inner">

      <div class="carousel-item active" data-bs-interval="5000">
        <img src="img/gallery/pic1.jpg" class="ep-carousel-img" alt="">
        <div class="carousel-caption ep-caption">
          <h2>Skin Care Essentials</h2>
          <p>Glow begins with great skincare</p>
        </div>
      </div>

      <div class="carousel-item" data-bs-interval="5000">
        <img src="img/gallery/pic2.jpg" class="ep-carousel-img" alt="">
        <div class="carousel-caption ep-caption">
          <h2>Daily Nutrition</h2>
          <p>Support your health every day</p>
        </div>
      </div>

      <div class="carousel-item" data-bs-interval="5000">
        <img src="img/gallery/pic3.jpg" class="ep-carousel-img" alt="">
        <div class="carousel-caption ep-caption">
          <h2>Quick Relief</h2>
          <p>Safe & effective medicines</p>
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


<!-- ================= Categories Owl Carousel ================= -->
  <section class="section-margin mt-0">
      <div class="section-intro pb-60px text-center">
          <!-- <img src="./img/abc.png" width="80" height="80" alt=""> -->
          <h2>Popular <span class="section-intro__style">Health Categories</span></h2>
        </div>
      <div class="owl-carousel owl-theme hero-carousel">
        <div class="hero-carousel__slide">
          <img src="img/home/06.jpg" alt="" class="img-fluid">
          <a href="#" class="hero-carousel__slideOverlay">
            <h3>Vitamins & Suppliments</h3>
            <p>Nourish your body with essential vitamins for a healthier lifestyle.</p>
          </a>
        </div>
        <div class="hero-carousel__slide">
          <img src="img/home/04.jpg" alt="" class="img-fluid">
          <a href="#" class="hero-carousel__slideOverlay">
            <h3>Diabetes Pills</h3>
            <p>Support effective blood sugar control for a healthier life.</p>
          </a>
        </div>
        <div class="hero-carousel__slide">
          <img src="img/home/05.jpg" alt="" class="img-fluid">
          <a href="#" class="hero-carousel__slideOverlay">
            <h3>Winter Care</h3>
            <p>Complete fever care essentials for your family’s health</p>
          </a>
        </div>
        <div class="hero-carousel__slide">
          <img src="img/home/images (2).jpg" alt="" class="img-fluid">
          <a href="#" class="hero-carousel__slideOverlay">
            <h3>Stomach Care</h3>
            <p>Medicines and supplements to support digestion, reduce acidity, and relieve common stomach.</p>
          </a>
        </div>
        <div class="hero-carousel__slide">
          <img src="img/home/images (3).jpg" alt="" class="img-fluid" style="height: 334px;">
          <a href="#" class="hero-carousel__slideOverlay">
            <h3>Antibiotics</h3>
            <p>Powerful medicines to fight bacterial infections and support faster recovery.</p>
          </a>
        </div>
        <div class="hero-carousel__slide">
          <img src="img/home/img.jpg" alt="" class="img-fluid" style="height: 334px;">
          <a href="#" class="hero-carousel__slideOverlay">
            <h3>Antibiotics</h3>
            <p>Powerful medicines to fight bacterial infections and support faster recovery.</p>
          </a>
        </div>
      </div>
    </section>

    <section class="section-margin calc-60px">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <div class="box left-item">
                <img class="img-fluid" src="./img/gallery/multivitamin-shield-icon-concept-inspiration-260nw-2131410211.png" alt="">
                <h3>15% off</h3>
                <p>Health care Products</p>
                <p>Vitamins and Suppliments</p>
                <button class="btn btn-primary">Shop Now</button>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box right-item">
              <img class="img-fluid" src="./img/home/Introducing-Korean-Skin-Care-removebg-preview (1).png" alt="">
              <h3>35% off  </h3>
              <p>Skin care products</p>
              <p>Glow begins with great skincare.</p>
              <button class="btn btn-primary">Shop Now</button>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--================ Discount advertise section end =================-->


    <!-- ================ trending product section start ================= -->
    <section class="section-margin calc-60px" style="background: aliceblue;padding-top:20px;">
      <div class="container">
        <div class="section-intro pb-60px text-center">
          <h2>Popular <span class="section-intro__style">Medicines</span></h2>
        </div>
        <div class="row g-4">
          <?php
          $result = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id LIMIT 6");
          $result->execute();
          $products = $result->fetchAll(PDO::FETCH_ASSOC);
          foreach ($products as $p) { ?>
            <div class="col-md-6 col-lg-3 col-xl-4">
              <div class="card text-center card-product">
                <form action="" method="post">
                <div class="card-product__img">
                  <img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="">
                  <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                      <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                  <ul class="card-product__imgOverlay">
                    <li><button><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><i class="ti-search"></i></a></button></li>
                    <li><button name="cart"><i class="ti-shopping-cart"></i></button></li>
                    <!-- <li><button><a href="cart.php?p_id=<?= $p['p_id'] ?>"><i class="ti-shopping-cart"></i></a></button></li> -->
                    <li><button><i class="ti-heart"></i></button></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h6 class="rating"><?php
                        for ($i = 0; $i < 5; $i++) {
                          echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                        }
                      
                      ?>
                  </h6>
                  <p style="text-transform: capitalize;"><?= $p['category_name'] ?></p>
                  <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>
                  <p class="card-product__price">₹<?= $p['price'] ?></p>
                </div>
                </form>
              </div>
            </div>
          <?php  } ?>
        </div>
    </section>
    <!-- ================ trending product section end ================= -->
    

    <!-- ================ offer section start ================= -->
    <section class="offer" id="parallax-1" data-anchor-target="#parallax-1" data-300-top="background-position: 20px 30px" data-top-bottom="background-position: 0 20px">
      <div class="container">
        <div class="row">
          <div class="col-xl-5">
            <div class="offer__content text-center">
              <h3>Up To 90% Off</h3>
              <h4>Winter Sale</h4>
              <p>Him she'd let them sixth saw light</p>
              <a class="button button--active mt-3 mt-xl-4" href="category.php">Shop Now</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ================ offer section end ================= -->

    <!-- ================ Best Selling item  carousel ================= -->
    <section class="section-margin calc-60px">
      <div class="container">
        <div class="section-intro pb-60px">
          <p>Popular Medicines in the market</p>
          <h2>Best <span class="section-intro__style">Sellers</span></h2>
        </div>
        <div class="owl-carousel owl-theme" id="bestSellerCarousel">
          <?php
          try {
            $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category e ON p.c_id = e.c_id WHERE price >= 100 LIMIT 4");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $p) {
          ?>
              
              <div class="card text-center card-product">
                <form action="" method="post">
                <div class="card-product__img">
                  <img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="">
                  <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
                      <input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
                  <ul class="card-product__imgOverlay">
                    <li><button><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><i class="ti-search"></i></a></button></li>
                    <li><button name="cart"><i class="ti-shopping-cart"></i></button></li>
                    <!-- <li><button><a href="cart.php?p_id=<?= $p['p_id'] ?>"><i class="ti-shopping-cart"></i></a></button></li> -->
                    <li><button><i class="ti-heart"></i></button></li>
                  </ul>
                </div>
                <div class="card-body">
                  <p><?= $p['category_name'] ?></p>
                  <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>
                  <p class="card-product__price">₹<?= $p['price'] ?></p>
                </div>
                </form>
              </div>
            
          <?php
            }
          } catch (PDOException $ex) {
            $message['WARNING'] = "Error: $ex";
          }
          ?>



        </div>
    </section>
    <!-- ================ Best Selling item  carousel end ================= -->

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



  </main>


  <!--================ Start footer Area  =================-->
  <?php require_once('footer.php'); ?>
  <!--================ End footer Area  =================-->


  <style>
    /* HERO SLIDER HEIGHT */
.ep-hero-slider,
#epHeroCarousel,
.carousel-inner,
.carousel-item {
  height: 100vh;
}

/* IMAGE FULL SIZE */
.ep-carousel-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* FADE OVER IMAGE */
.carousel-item::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: 1;
  background: linear-gradient(
    90deg,
    rgba(0,0,0,0.8) 0%,
    rgba(0,0,0,0.5) 40%,
    rgba(0,0,0,0.15) 70%,
    rgba(0,0,0,0) 100%
  );
}

/* TEXT ABOVE FADE */
.ep-caption {
  z-index: 2;
  text-align: left;
  left: 8%;
  right: auto;
  bottom: auto;
  top: 50%;
  transform: translateY(-50%);
}

/* TEXT STYLING */
.ep-caption h2 {
  font-size: 3rem;
  font-weight: 700;
}

.ep-caption p {
  font-size: 1.2rem;
  margin-top: 10px;
}

/* BUTTON ICON ABOVE FADE */
.carousel-control-prev,
.carousel-control-next {
  z-index: 3;
}

  </style>