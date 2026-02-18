<?php
require_once('./connection/db.php');

if (isset($_POST['category_id'])) {

    $cid = $_POST['category_id'];

    $products = $conn->prepare("
        SELECT * 
        FROM ep_products p 
        JOIN ep_category c ON c.c_id = p.c_id  
        WHERE p.c_id = :cid AND p.status = 'Active'
    ");
    $products->execute(['cid' => $cid]);

    if ($products->rowCount() > 0) {
        while ($p = $products->fetch(PDO::FETCH_ASSOC)) {
?>

            <div class="col-md-6 col-lg-4">
                <div class="card text-center card-product">
                    <form method="post">
                        <div class="card-product__img">
                            <p class="card-product__price">₹<?= $p['price'] ?></p>

                            <a href="single-product.php?p_id=<?= $p['p_id'] ?>">
                                <img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt="">
                            </a>

                            <input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">

                            <ul class="card-product__imgOverlay">
                                <li>
                                    <button name="cart">
                                        <i class="ti-shopping-cart"></i>
                                    </button>
                                </li>
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
                            <h4 class="card-product__title">
                                <a href="single-product.php?p_id=<?= $p['p_id'] ?>">
                                    <?= $p['name'] ?>
                                </a>
                            </h4>
                        </div>
                    </form>
                </div>
            </div>

<?php
        }
    } else {
        echo "<div class='col-12'><p>No products found</p></div>";
    }
}
?>