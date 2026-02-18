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
        <div class="alert alert-info">
            <h6>Product Not Found!!</h6>
        </div>
        <?php }

    if ($query->rowCount() > 0) {
        foreach ($fetch_prod as $p) { ?>

            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card text-center card-product">
                    <div class="card-product__img">
                        <h6 class="card-product__price">₹<?= $p['price'] ?></h6>
                        <img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="">
                        <ul class="card-product__imgOverlay">
                            <li><button><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><i class="ti-search"></i></a></button></li>
                            <li><button><a href="cart.php?p_id=<?= $p['p_id'] ?>"><i class="ti-shopping-cart"></i></a></button></li>
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
                        <p><?= $p['category_name'] ?></p>
                        <h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>
                    </div>
                </div>
            </div>

<?php    }
    }
}
?>