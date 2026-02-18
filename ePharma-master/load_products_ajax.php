<?php
require_once("connection/db.php");

$limit = 9;
$page = $_POST['page'] ?? 1;
$offset = ($page - 1) * $limit;

$category_id = $_POST['category_id'] ?? '';
$search      = $_POST['search'] ?? '';
$sort        = $_POST['sort'] ?? '';
$max_price   = $_POST['max_price'] ?? '';

$where = "WHERE p.status = 'Active'";

// Category Filter
if ($category_id != '') {
    $where .= " AND p.c_id = :category_id";
}

// Search
if ($search != '') {
    $where .= " AND p.name LIKE :search";
}

// Price Filter
if ($max_price != '') {
    $where .= " AND p.price <= :max_price";
}

// Sorting
$order = "ORDER BY p.p_id DESC";

if ($sort == "low_to_high") {
    $order = "ORDER BY p.price ASC";
} elseif ($sort == "high_to_low") {
    $order = "ORDER BY p.price DESC";
} elseif ($sort == "latest") {
    $order = "ORDER BY p.p_id DESC";
}

$sql = "SELECT * FROM ep_products p
        JOIN ep_category c ON c.c_id = p.c_id
        $where
        $order
        LIMIT $offset, $limit";

$stmt = $conn->prepare($sql);

if ($category_id != '') {
    $stmt->bindValue(':category_id', $category_id);
}

if ($search != '') {
    $stmt->bindValue(':search', "%$search%");
}

if ($max_price != '') {
    $stmt->bindValue(':max_price', $max_price);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// rate average



foreach ($products as $p) {
    $rate_avg = $conn->prepare("SELECT AVG(rate) as rating_avg FROM ep_review WHERE p_id = :pid");
    $rate_avg->execute(['pid' => $p['p_id']]);
    $fetch_avg = $rate_avg->fetch(PDO::FETCH_ASSOC);

    $available_stock = $p['stock'];
?>
    <div class="col-md-6 col-lg-4">
        <div class="card text-center card-product">
            <form action="" method="post">
                <div class="card-product__img">
                    <h6 class="card-product__price">₹<?= $p['price'] ?></h6>
                    <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
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
<?php
}

$count_sql = "SELECT COUNT(*) as total
              FROM ep_products p
              JOIN ep_category c ON c.c_id = p.c_id
              $where";

$count_stmt = $conn->prepare($count_sql);

if ($category_id != '') {
    $count_stmt->bindValue(':category_id', $category_id);
}

if ($search != '') {
    $count_stmt->bindValue(':search', "%$search%");
}

if ($max_price != '') {
    $count_stmt->bindValue(':max_price', $max_price);
}

$count_stmt->execute();
$total = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

$pages = ceil($total / $limit);

// echo "<div class='pagination-wrapper'><ul class='ep-pagination'>";
?>
<div class="col-xl-12 col-lg-8 col-md-7">
    <div class="pagination-wrapper">
        <ul class="ep-pagination">
            <?php

            for ($i = 1; $i <= $pages; $i++) {
                echo "<li><a href='#' class='page-link' data-page='$i'>$i</a></li>";
            }

            // echo "</ul></div>";
            ?>

        </ul>
    </div>

</div>