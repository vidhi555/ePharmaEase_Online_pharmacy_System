<?php

// Temporary Page
require_once("connection/db.php");
if (isset($_POST['input'])) {
    $input = $_POST['input'];   //get data from textbox

    //Query
    $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE name LIKE '{$input}%' OR category_name LIKE '{$input}%' OR price LIKE '{$input}%' ");
    $query->execute();
    $fetch_prod = $query->fetchAll(PDO::FETCH_ASSOC);

    if (!$fetch_prod) { ?>
        <div class="alert alert-info" style="text-align: center;padding: 40px;color: #6c757d;">
            <i style=" font-size:50px;color:#0d6efd;margin-bottom:10px;" class="fas fa-search-minus"></i>
            <h3>Product Not Found!!</h3>
            <p>Try searching with a different name</p>
        </div>
        <?php }

    if ($query->rowCount() > 0) {
      echo "<h5>Showing results for '$input'</h5>";

        foreach ($fetch_prod as $p) { ?>

            <div class="col-md-6 col-lg-3 col-xl-3" data-aos="fade-in">
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
     
<?php    }
    }
}
?>