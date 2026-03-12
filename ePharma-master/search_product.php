<?php
require_once("connection/db.php");
// require_once('session.php');
require_once("insert_cart_logic.php");
?>

<!--================ Start Header Menu Area =================-->
<?php
$page_title = "Search Medicine";
require_once('header.php');  ?>
<!--================ End Header Menu Area =================-->

 <!-- ================ start banner area ================= -->
            <section class="blog-banner-area fade-up" id="category">
                <div class="container h-100">
                    <div class="blog-banner">
                        <div class="text-center">
                            <h1>Search</h1>
                            <nav aria-label="breadcrumb" class="banner-breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Search</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ================ end banner area ================= -->
<main class="site-main">

  <!-- Search start -->
  <section class="section-margin calc-60px">
    <div class="container" data-aos="zoom-in">
      <div class="">
         <h2><span class="section-intro__style">Trusted Pharmacy Search</span></h2>
        <input type="text" id="livesearch" class="form-control mt-5" placeholder="Search Anything">
      </div>
      <div class="row mt-4" id="result">
        <!-- Display Products -->
      </div>
    </div>
  </section>
  <!-- Search End -->
  <!-- <section class="section-margin calc-60px">
    <div class="container"> -->
      <!-- <div class="section-intro pb-60px" > -->
      <!-- <div class="row mt-4" id="result"> -->
        <!-- Display Products -->
      <!-- </div>
      </div> -->
    <!-- </div> -->
  <!-- </section> -->
  <!-- ================ trending product section start ================= -->
  <section class="section-margin calc-60px">
    <div class="container">
      <div class="section-intro pb-60px text-center" data-aos="fade-up">
        <img data-aos="fade-up" src="img/logo6.png" alt="logo" style="margin-bottom: -42px;width: 100px;height: 80px;">
        <h2> <span class="section-intro__style">Our Top Rated Wellness Essentials</span></h2>
      </div>
      <div class="row">
        <?php
        $result = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id LIMIT 4");
        $result->execute();
        $products = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $p) { ?>
         
          <div class="col-md-6 col-lg-3 col-xl-3" data-aos="fade-up">
            <div class="card text-center card-product">
              <form action="" method="post">
                <div class="card-product__img">
                  <h6 class="card-product__price">$<?= $p['price'] ?></h6>
                  <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/All_images_uploads/<?= $p['image'] ?>" alt=""></a>
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
        <?php  } ?>
        <div class="shop_btn">
          <a class="button button--active mt-3 mt-xl-4" style="position: relative;" href="category.php">Shop Now</a>
        </div>
      </div>
  </section>
  <!-- ================ trending product section end ================= -->


  <!-- Search product -->
  <script>
    // $(document).ready(function(){   //run after page is loaded
    //   $('#livesearch').on('keyup',function(){    //function will execute when key released
    //     let input = $(this).val();  //get the value from textbox
    //     // alert(input);
    //     if(input != ""){
    //       $.ajax({
    //         url:"seach_prod_logic.php",  //Where to send request
    //         method:"POST",
    //         data:{input:input}, //define key & value

    //         success:function(data){
    //           $('#result').html(data);
    //         }
    //       });
    //     }else{
    //       //not using search display none
    //       $('#result').css("display","none");
    //     }
    //   });
    // });


    $(document).on('keyup', '#livesearch', function() { //function will execute when key released
      let input = $(this).val(); //get the value from textbox
      // alert(input);
      if (input != "") {
        $.ajax({
          url: "seach_prod_logic.php", //Where to send request
          method: "POST",
          data: {
            input: input
          }, //define key & value

          success: function(data) {
            $('#result').html(data).show();
          }
        });
      } else {
        //not using search display none
        $('#result').hide().html("");
      }
    });
  </script>
</main>


<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>
<!--================ End footer Area  =================-->