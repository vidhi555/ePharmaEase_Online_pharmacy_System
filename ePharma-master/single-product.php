<?php
require_once("connection/db.php");

$pid = $pid = $_GET['p_id'] ?? '';
// require_once('insert_cart_logic.php');

if (isset($_POST['cart'])) {
    try {
        //   $pid = $_POST['product_id'];
        //   $user_id = $_SESSION['user_id'];
        if (!isset($_SESSION['user_id'])) {
            $user_id = "";
            sweetAlert("Alert", "Please Login First!", "warning");
        } else {
            $user_id = $_SESSION['user_id'];
            // check product already in cart
            $check = $conn->prepare(
                "SELECT cart_id FROM ep_cart WHERE p_id = :pid AND u_id = :uid"
            );
            $check->execute([
                'pid' => $pid,
                'uid' => $user_id
            ]);
            if ($check->rowCount() > 0) {
                sweetAlert("Already in your cart!!", "This product is Already exist in your cart!!!", "warning");
            } else {

                // fetch product
                $product = $conn->prepare(
                    "SELECT name, price FROM ep_products WHERE p_id = :pid"
                );
                $product->execute(['pid' => $pid]);
                $p = $product->fetch(PDO::FETCH_ASSOC);

                if (!$p) {
                    // $_SESSION['warning'] = "Product not found";
                    sweetAlert("Error!", "Product Not Found!!", "warning");
                } else {
                    // insert into cart
                    $insert = $conn->prepare(
                        "INSERT INTO ep_cart (u_id, p_id, pname, qty, price)
                     VALUES (:uid, :pid, :pname, :qty, :price)"
                    );
                    $insert->execute([
                        'uid'   => $user_id,
                        'pid'   => $pid,
                        'pname' => $p['name'],
                        'qty'   => $_POST['qty'],
                        'price' => $p['price']
                    ]);
                    sweetAlert("Item Added!", "Successfully Added in Your cart!", "success");
                }
            }
        }
    } catch (PDOException $e) {
        // echo "ERROR: " . $e->getMessage();
        sweetAlert("Error!", "$e", "error");
    }
}

?>

<!--================ Start Header Menu Area =================-->

<?php
$page_title = "ePharmaEase - Product Detail";
require_once('header.php') ?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="blog">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>Explore Single Products</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Choose the Right Medicine</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ================ end banner area ================= -->

<?php
try {
    //Display Specific Product
    $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE p_id = $pid AND status = 'Active'");
    $query->execute();
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if ($product) { ?>
        <div class="product_image_area">
            <div class="container">
                <div class="row s_product_inner ">
                    <div class="col-lg-6 slide-right">
                        <div class="owl-carousel owl-theme s_Product_carousel" style="background: #f6f6f6;border-radius:20px;">
                            <div class="single-prd-item">
                                <img class="img-fluid" src="../LearnAdmin/upload/<?= $product['image'] ?>" alt="p_image">
                            </div>
                            <div class="single-prd-item">
                                <img class="img-fluid" src="../LearnAdmin/upload/<?= $product['image'] ?>" alt="">
                            </div>
                            <div class="single-prd-item">
                                <img class="img-fluid" src="../LearnAdmin/upload/<?= $product['image'] ?>" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1 slide-left">
                        <form action="" method="post">
                            <div class="s_product_text">
                                <h2 style="color:#1565c0;"><?= $product['name'] ?></h2>
                                <p><?= $product['description'] ?></p>
                                <ul class="list">
                                    <input type="hidden" name="product_id" value="<?= $product['p_id'] ?>">
                                    <input type="hidden" name="cart_id" value="<?= $product['p_id'] ?>">
                                    <li><a class="active" href="#"><span>Category</span> : <?= $product['category_name'] ?></a></li>
                                    <li><a href="#"><span>Availibility</span> : <?php echo $product['status'] == 'Active' ? "In-Stock" : "" ?></a></li>
                                    <li><a class="active" href="#"><span>Expiry Date</span> : <?php echo date("d M Y", strtotime($product['expiry_date'])); ?></a></li>


                                </ul>

                                <div class="product_count">
                                    <h2>₹<?= $product['price'] ?></h2>
                                    <label for="qty">Quantity:</label>
                                    <input type="number" name="qty" min="1" value="1" class="increase items-count">

                                    <!-- <a class="button primary-btn" name="cart" href="cart.php?p_id=<?= $product['p_id'] ?>">Add to Cart</a> -->
                                </div>
                                <button type="submit" class="btn btn-primary" name="cart">Add To Cart</button>

                                <!-- <div class="card_area d-flex align-items-center">
									<a class="icon_btn" href="#"><i class="lnr lnr lnr-diamond"></i></a>
									<a class="icon_btn" href="#"><i class="lnr lnr lnr-heart"></i></a>
								</div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php    }
} catch (PDOException $e) {
    echo "<p class='container mt-5 alert alert-danger'>Un-Authorized Access!!!!</p>";
}

?>

<!--================Single Product Area =================-->
<!-- <div class="product_image_area">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
					<div class="owl-carousel owl-theme s_Product_carousel">
						<div class="single-prd-item">
							<img class="img-fluid" src="img/category/s-p1.jpg" alt="">
						</div>
						<div class="single-prd-item">
							<img class="img-fluid" src="img/category/s-p1.jpg" alt="">
						</div>
						<div class="single-prd-item">
							<img class="img-fluid" src="img/category/s-p1.jpg" alt="">
						</div>
					</div>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3>Faded SkyBlu Denim Jeans</h3>
						<h2>$149.99</h2>
						<ul class="list">
							<li><a class="active" href="#"><span>Category</span> : Household</a></li>
							<li><a href="#"><span>Availibility</span> : In Stock</a></li>
						</ul>
						<p>Mill Oil is an innovative oil filled radiator with the most modern technology. If you are looking for
							something that can make your interior look awesome, and at the same time give you the pleasant warm feeling
							during the winter.</p>
						<div class="product_count">
              <label for="qty">Quantity:</label>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
							 class="increase items-count" type="button"><i class="ti-angle-left"></i></button>
							<input type="text" name="qty" id="sst" size="2" maxlength="12" value="1" title="Quantity:" class="input-text qty">
							<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
               class="reduced items-count" type="button"><i class="ti-angle-right"></i></button>
							<a class="button primary-btn" href="#">Add to Cart</a>               
						</div>
						<div class="card_area d-flex align-items-center">
							<a class="icon_btn" href="#"><i class="lnr lnr lnr-diamond"></i></a>
							<a class="icon_btn" href="#"><i class="lnr lnr lnr-heart"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->
<!--================End Single Product Area =================-->

<!--================Product Description Area =================-->
<section class="product_description_area ">
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Description</a>
            </li>
            <!-- <li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
						aria-selected="false">Specification</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
						aria-selected="false">Comments</a> -->
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review"
                    aria-selected="false">Reviews</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <?php
                //Display Specific Product
                $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE p_id = $pid");
                $query->execute();
                $product = $query->fetch(PDO::FETCH_ASSOC);

                ?>
                <p class="text-muted" style="text-align: justify;"><?= $product['description'] ?></p>

            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <h5>Width</h5>
                                </td>
                                <td>
                                    <h5>128mm</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Height</h5>
                                </td>
                                <td>
                                    <h5>508mm</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Depth</h5>
                                </td>
                                <td>
                                    <h5>85mm</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Weight</h5>
                                </td>
                                <td>
                                    <h5>52gm</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Quality checking</h5>
                                </td>
                                <td>
                                    <h5>yes</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Freshness Duration</h5>
                                </td>
                                <td>
                                    <h5>03days</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>When packeting</h5>
                                </td>
                                <td>
                                    <h5>Without touch of hand</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Each Box contains</h5>
                                </td>
                                <td>
                                    <h5>60pcs</h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="comment_list">
                            <div class="review_item">
                                <div class="media">
                                    <div class="d-flex">
                                        <img src="img/product/review-1.png" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h4>Blake Ruiz</h4>
                                        <h5>12th Feb, 2018 at 05:56 pm</h5>
                                        <a class="reply_btn" href="#">Reply</a>
                                    </div>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                    commodo</p>
                            </div>
                            <div class="review_item reply">
                                <div class="media">
                                    <div class="d-flex">
                                        <img src="img/product/review-2.png" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h4>Blake Ruiz</h4>
                                        <h5>12th Feb, 2018 at 05:56 pm</h5>
                                        <a class="reply_btn" href="#">Reply</a>
                                    </div>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                    commodo</p>
                            </div>
                            <div class="review_item">
                                <div class="media">
                                    <div class="d-flex">
                                        <img src="img/product/review-3.png" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h4>Blake Ruiz</h4>
                                        <h5>12th Feb, 2018 at 05:56 pm</h5>
                                        <a class="reply_btn" href="#">Reply</a>
                                    </div>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                    commodo</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="review_box">
                            <h4>Post a comment</h4>
                            <form class="row contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Full name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="number" name="number" placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" name="message" id="message" rows="1" placeholder="Message"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button type="submit" value="submit" class="btn primary-btn">Submit Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row total_rate">
                            <div class="col-6">
                                <div class="box_total">
                                    <?php
                                    try {
                                        $query = $conn->prepare("SELECT AVG(rate) as overall_rate , COUNT(*) as total_review FROM ep_review r WHERE p_id = :pid");
                                        $query->execute(['pid' => $pid]);
                                        $fetch_r = $query->fetch(PDO::FETCH_ASSOC);
                                        $total_review = $fetch_r['total_review'];
                                        if ($fetch_r) {
                                            // echo $fetch_r['overall_rate'];

                                    ?>

                                            <h5>Overall</h5>
                                            <h4><?= round($fetch_r['overall_rate'],1) ?></h4>
                                            <h6>(<?= $total_review ?> Reviews)</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rating_list">
                                    <h3>Based on <?= $total_review ?> Reviews</h3>
                                    <ul class="list">
                                        <li>
                                            <a href="#">5 Star <?php
                                                                $query = $conn->prepare("SELECT COUNT(*) as rated FROM ep_review WHERE rate = 5 AND p_id = :pid ");
                                                                $query->execute(['pid' => $pid]);
                                                                $five_star = $query->fetch(PDO::FETCH_ASSOC);
                                                                if ($five_star) {
                                                                    // foreach($five_star as $f){
                                                                    // echo $f;
                                                                ?>


                                                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> <?= $five_star['rated'] ?></a>
                                        </li>
                                    <?php
                                                                }
                                                                // }
                                    ?>
                                    <li>
                                        <a href="#">4 Star <?php
                                                            $query = $conn->prepare("SELECT COUNT(*) as rated FROM ep_review WHERE rate = 4 AND p_id = :pid ");
                                                            $query->execute(['pid' => $pid]);
                                                            $five_star = $query->fetch(PDO::FETCH_ASSOC);
                                                            if ($five_star) {
                                                                // foreach($five_star as $f){
                                                                // echo $f;
                                                            ?>


                                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                    class="fa fa-star"></i><i class="far fa-star"></i> <?= $five_star['rated'] ?></a>
                                    </li>
                                <?php
                                                            }
                                                            // }
                                ?>
                                <li>
                                    <a href="#">3 Star <?php
                                                        $query = $conn->prepare("SELECT COUNT(*) as rated FROM ep_review WHERE rate = 3 AND p_id = :pid ");
                                                        $query->execute(['pid' => $pid]);
                                                        $five_star = $query->fetch(PDO::FETCH_ASSOC);
                                                        if ($five_star) {
                                                            // foreach($five_star as $f){
                                                            // echo $f;
                                                        ?>


                                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                class="far fa-star"></i><i class="far fa-star"></i> <?= $five_star['rated'] ?></a>
                                </li>
                            <?php
                                                        }
                                                        // }
                            ?>
                            <li>
                                <a href="#">2 Star <?php
                                                    $query = $conn->prepare("SELECT COUNT(*) as rated FROM ep_review WHERE rate = 2 AND p_id = :pid ");
                                                    $query->execute(['pid' => $pid]);
                                                    $five_star = $query->fetch(PDO::FETCH_ASSOC);
                                                    if ($five_star) {
                                                        // foreach($five_star as $f){
                                                        // echo $f;
                                                    ?>


                                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="far fa-star"></i><i
                                            class="far fa-star"></i><i class="far fa-star"></i> <?= $five_star['rated'] ?></a>
                            </li>
                        <?php
                                                    }
                                                    // }
                        ?>
                        <li>
                            <a href="#">1 Star <?php
                                                $query = $conn->prepare("SELECT COUNT(*) as rated FROM ep_review WHERE rate = 1 AND p_id = :pid ");
                                                $query->execute(['pid' => $pid]);
                                                $five_star = $query->fetch(PDO::FETCH_ASSOC);
                                                if ($five_star) {
                                                    // foreach($five_star as $f){
                                                    // echo $f;
                                                ?>


                                    <i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i
                                        class="far fa-star"></i><i class="far fa-star"></i> <?= $five_star['rated'] ?></a>
                        </li>
                    <?php
                                                }
                                                // }
                    ?>

                                    </ul>
                                </div>
                            </div>
                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        echo $e;
                                    }
                    ?>

                        </div>
                        <div class="review_list">
                            <?php
                            try {
                                $query = $conn->prepare("SELECT * FROM ep_review r JOIN ep_users p ON r.u_id = p.u_id WHERE p_id = :pid ORDER BY rate DESC");
                                $query->execute(['pid' => $pid]);
                                $fetch_review = $query->fetchAll(PDO::FETCH_ASSOC);
                                $count = $query->rowCount();
                                if ($fetch_review) {
                            ?>
                                    <div id="review_product">
                                        <h4><?= $count ?> review for <?= $product['name'] ?>:</h4>
                                        <hr>
                                    </div>
                                    <?php
                                    foreach ($fetch_review as $rev) {
                                    ?>

                                        <div class="review_item">
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="uploads/<?= $rev['image'] ?>" style="width: 60px;" alt="">
                                                </div>
                                                <div class="media-body">
                                                    <h4><?= $rev['name'] ?></h4>
                                                    <?php
                                                    for ($i = 1; $i <= $rev['rate']; $i++) {
                                                    ?>
                                                        <i class="fa fa-star"></i>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <p class="text-muted"><?= $rev['description'] ?></p>
                                            <hr>
                                        </div>
                            <?php

                                    }
                                } else {
                                    echo "<p class='text-muted'>No Review </p>";
                                }
                            } catch (PDOException $e) {
                                echo $e;
                            }
                            ?>


                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="review_box">
                            <h4>Add a Review</h4>
                            <p>Your Rating:</p>
                            <ul class="list rating_star">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>

                                    <li><i class="far fa-star star" data-value="<?= $i ?>"></i></li>
                                <?php }
                                ?>
                                <!-- <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li> -->
                            </ul>

                            <p id="rate_text">Outstanding</p>
                            <form action="" method="post" class="form-contact form-review mt-3">
                                <!-- <div class="form-group">
										<input class="form-control" name="name" type="text" placeholder="Enter your name" required>
									</div>
									<div class="form-group">
										<input class="form-control" name="email" type="email" placeholder="Enter email address" required>
									</div> -->
                                <input type="hidden" name="rate" id="rate" value="0">
                                <div class="form-group">
                                    <input class="form-control" name="subject" type="text" placeholder="Enter Subject">
                                </div>

                                <!-- <div class="form-group">
                                    <input class="form-control" name="rate" type="number" min="1" max="5" placeholder="Your Rating">
                                </div> -->
                                <div class="form-group">
                                    <textarea class="form-control different-control w-100" name="message" id="textarea" cols="30" rows="5" placeholder="Enter Message"></textarea>
                                </div>
                                <div class="form-group text-center text-md-right mt-3">
                                    <button type="submit" name="review" class="button button--active button-review">Submit Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    let stars = document.querySelectorAll('.star');
    let rating = document.getElementById('rate');
    let rating_text = document.getElementById('rate_text');

    stars.forEach(index => {
        index.addEventListener("mouseover", function() {
            let value = this.getAttribute('data-value');
            // alert(value);
            fill_stars(value);
            // rating_text.innerText = getText(value);
        });
        index.addEventListener('click', function() {
            let rating_value = this.getAttribute('data-value');
            rating.value = rating_value;
            rating_text.innerText = getText(rating_value);
        });
    });


    //reset star 
    document.querySelector(".rating_star").addEventListener("mouseleave", function() {
        fill_stars(rating.value);
    });

    function fill_stars(value) {
        // alert(value);
        stars.forEach(i => {
            if (i.getAttribute("data-value") <= value) {

                i.classList.remove("far");
                i.classList.add("fas");
            } else {

                i.classList.remove("fas");
                i.classList.add("far");
            }
        });
    }

    function getText(text) {

        switch (text) {
            case "1":
                return "Poor";
            case "2":
                return "Fair";
            case "3":
                return "Good";
            case "4":
                return "Very Good";
            case "5":
                return "Outstanding";
            default:
                return "Choose Rating";
        }
    }
</script>
<?php
try {
    if (isset($_POST['review'])) {
        $uid = $_SESSION['user_id'];

        // $name = $_POST['name'];
        // $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $rate = $_POST['rate'];
        // echo $rate;
        // die();

        $check = $conn->prepare("SELECT * FROM ep_cart WHERE p_id = :pid AND u_id = :uid ");
        $check->execute([
            'pid' => $pid,
            'uid' => $user_id
        ]);
        $fetch_pid = $check->fetch(PDO::FETCH_ASSOC);
        $cart_pid = $fetch_pid['p_id'] ?? '';
        if ($cart_pid != $pid) {
            sweetAlert("First, Add to cart this Product!!!!", "", "warning");
        } else {
            if (empty($subject) || empty($message) || empty($rate) || empty($pid)) {
                sweetAlert("Fill the required fields!!!", "", "warning");
            } else {

                $query = "INSERT INTO `ep_review`( `u_id`, `title`, `description`, `rate`, `p_id`) VALUES (:uid, :title , :description , :rate, :pid)";
                $review = $conn->prepare($query);
                $review->execute([
                    'uid' => $uid,
                    'title' => $subject,
                    'description' => $message,
                    'rate' => $rate,
                    'pid' => $pid,
                ]);
                sweetAlert("Review Submitted successfully.", " ", "success");
            }
        }
    }
} catch (PDOException $e) {
    echo $e;
}
?>
<!--================End Product Description Area =================-->

<!--================ Start related Product area =================-->
<?php require_once("related_products.php"); ?>
<!--================ end related Product area =================-->

<!--================ Start footer Area  =================-->
<?php require_once("footer.php"); ?>
<!--================ End footer Area  =================-->

<?php require_once('sweetAlert.php'); ?>