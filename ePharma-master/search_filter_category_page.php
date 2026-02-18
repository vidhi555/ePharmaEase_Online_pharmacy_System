<?php
include_once("connection/db.php");

//Sorting & Searching
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

    $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE name LIKE '{$search_data}%' {$orderby} LIMIT 9");
    $query->execute();

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
    }

    exit;
}
?>