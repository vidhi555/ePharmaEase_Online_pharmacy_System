<?php
require_once("connection/db.php");
// require_once('session.php');

?>

<!--================ Start Header Menu Area =================-->
<?php
$page_title = "Search Medicine";
require_once('header.php');  ?>
<!--================ End Header Menu Area =================-->

<main class="site-main">

  <!-- Search start -->
  <section class="section-margin calc-60px">
    <div class="container">
      <div class="">
        <input type="text" id="livesearch" class="form-control" placeholder="Search Anything">
      </div>
    </div>
  </section>
  <!-- Search End -->
  <section class="section-margin calc-60px">
    <div class="container">
      <div class="section-intro pb-60px">
        <h2><span class="section-intro__style">Search Medicines</span></h2>
      </div>
      <div class="row" id="result">
        <!-- Display Products -->
      </div>
    </div>
  </section>
  <!-- ================ trending product section start ================= -->
  <section class="section-margin calc-60px">
    <div class="container">
      <div class="section-intro pb-60px">
        <p>Popular Item in the market</p>
        <h2>Popular <span class="section-intro__style">Medicines</span></h2>
      </div>
      <div class="row">
        <?php
        $result = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id LIMIT 8");
        $result->execute();
        $products = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $p) { ?>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <h6 class="card-product__price">₹<?= $p['price'] ?></h6>
                <img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><i class="ti-search"></i></a></button></li>
                  <li><button><a href="cart.php?p_id=<?= $p['p_id'] ?>"><i class="ti-shopping-cart"></i></a></button></li>
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
            </div>
          </div>
        <?php  } ?>
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