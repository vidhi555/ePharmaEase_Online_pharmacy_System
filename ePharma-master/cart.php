<?php
require_once("connection/db.php");
$pid = $_GET['p_id'] ?? '';
$user_id = isset($_SESSION['user_id']) ?? NULL;
$guest_id = $user_id ? NULL : session_id();

// echo $user_id."/ ";
// echo $guest_id;
// die();


if ($user_id) {
    $check = $conn->prepare(
        "SELECT cart_id FROM ep_cart WHERE p_id = :pid AND u_id = :uid"
    );
    $check->execute([
        'pid' => $pid,
        'uid' => $user_id
    ]);
} else {
    $check = $conn->prepare(
        "SELECT cart_id FROM ep_cart WHERE p_id = :pid AND guest_id = :gid"
    );
    $check->execute([
        'pid' => $pid,
        'gid' => $guest_id
    ]);
}
require_once('insert_cart_logic.php');

if (isset($_POST['update_cart'])) {
    if (empty($_POST['cart_id'])) {
        sweetAlert("Error", "Your Cart is Empty!!!  No item will Update!!", "error");
    } else {
        try {
            $cart_ids = $_POST['cart_id'];  //get cart_ids which will modify
            $qtys     = $_POST['qty'];

            for ($i = 0; $i < count($cart_ids); $i++) {     //iterate for total cart_id in cart table

                if ($user_id) {
                $update = $conn->prepare("UPDATE ep_cart SET qty = :qty WHERE cart_id = :cart_id AND u_id = :uid");
                    $update->execute([
                        'qty'     => $qtys[$i],
                        'cart_id' => $cart_ids[$i],
                        'uid'     => $user_id
                    ]);
                } else {
                    $update = $conn->prepare("UPDATE ep_cart SET qty = :qty WHERE cart_id = :cart_id AND guest_id = :gid");
                    $update->execute([
                        'qty'     => $qtys[$i],
                        'cart_id' => $cart_ids[$i],
                        'gid'     => $guest_id
                    ]);
                }
                
            }

            
            // $_SESSION['warning'] = "Cart updated successfully";
            sweetAlert("Success!", "Update Cart Successfully!", "success");
            header("Location: cart.php");
            exit;
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }
}


?>
<script>
    function subTotal() {
        let prices = document.getElementsByClassName('price');
        let qtys = document.getElementsByClassName('qty');
        let totals = document.getElementsByClassName('total');

        let grand = 0;

        for (let i = 0; i < prices.length; i++) {
            //class="price" = 4 , then it iterate 4 times

            let total = prices[i].value * qtys[i].value;
            totals[i].innerText = "₹" + total;
            grand += total;
        }

        document.getElementById('gTotal').innerText = "₹" + grand;
    }
</script>
<!--================ Start Header Menu Area =================-->
<?php
$page_title = "ePharmaEase - Cart";
require_once("header.php");
?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>Shopping Cart</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ================ end banner area ================= -->

<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <!-- ==============Show message to guest user =================== -->
        <?php if (!$user_id) { ?>
            <div class="alert alert-info">
                <p>You are shopping as a <strong>Guest</strong>.</p>
                <p>Please <a href="login.php">Login</a> to save your cart.</p>
            </div>
        <?php } ?>
        <!-- ==============Show message to guest user =================== -->

        <div class="cart_inner">
            <div class="table-responsive" style="border-radius: 20px;">
                <form action="" method="post">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php


                            $grand_total = 0;

                            try {
                                //display cart items
                                if ($user_id) {
                                    $cart = $conn->prepare("SELECT c.cart_id, c.qty, p.p_id, p.name, p.price, p.image
                                            FROM ep_cart c
                                            JOIN ep_products p ON c.p_id = p.p_id
                                            WHERE c.u_id = :uid");
                                    $cart->execute(['uid' => $user_id]);
                                    // echo "user";
                                } else {
                                    $cart = $conn->prepare("SELECT c.cart_id, c.qty, p.p_id, p.name, p.price, p.image
                                            FROM ep_cart c
                                            JOIN ep_products p ON c.p_id = p.p_id
                                            WHERE guest_id = :gid");
                                    $cart->execute(['gid' => $guest_id]);
                                    // echo "Guest";
                                }


                                // echo $user_id."/ ".$guest_id;
                                // die();
                                if ($cart->rowCount() > 0) {
                                    while ($p = $cart->fetch(PDO::FETCH_ASSOC)) {

                                        $item_total = $p['price'] * $p['qty'];  //calculate sub Total
                                        $grand_total += $item_total;    //calculate Grand Total
                            ?>
                                        <tr>
                                            <td>
                                                <div class="media">
                                                    <div class="d-flex">
                                                        <a href="deletecart.php?cart_id=<?= $p['cart_id'] ?>" name="cart_delete" style="display: flex;align-items: center;justify-items: center;margin-right: 20px;text-decoration: none;"><i style="margin-left: 10px;font-size: 30px;color: #0a35f5;" class="ti-trash" aria-hidden="true"></i></a>
                                                        <a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img style="width:80px;height:80px;" src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
                                                    </div>
                                                    <div class="media-body">
                                                        <p><?= $p['name'] ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h5>₹<?= $p['price'] ?></h5>
                                                <input type="hidden" class="price" value="<?= $p['price'] ?>">

                                            </td>
                                            <td>
                                                <div class="product_count">
                                                    <!-- Get cart ids -->
                                                    <input type="hidden" name="cart_id[]" value="<?= $p['cart_id'] ?>">

                                                    <input type="number" name="qty[]" min="1" onchange="subTotal()" maxlength="12" value="<?= $p['qty'] ?>" title="Quantity:"
                                                        class="input-text qty">
                                                    <!--<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                                        class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
                                                    <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                                                        class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button> -->
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="total">₹<?= $item_total ?></h5>
                                            </td>

                                        </tr>
                            <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>Cart is empty😥</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "ERROR: " . $e->getMessage();
                            }
                            ?>



                            <tr class="bottom_button">
                                <td>
                                    <button type="submit" name="update_cart" class="update-btn">
                                        Update Cart
                                    </button>
                                    <!-- <a class="button" type="submit" name="update_cart" href="update_cart.php?cart_id=<?= $p['cart_id'] ?>">Update Cart</a> -->
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <!-- <div class="cupon_text d-flex align-items-center">
                                        <input type="text" placeholder="Coupon Code">
                                        <a class="primary-btn" href="#">Apply</a>
                                        <a class="button" href="#">Have a Coupon?</a>
                                    </div> -->
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <?php

                                    ?>
                                    <h5 id="gTotal">₹<?= $grand_total ?></h5>
                                </td>
                            </tr>
                            <!-- <tr class="shipping_area">
                                    <td class="d-none d-md-block">

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <h5>Shipping</h5>
                                    </td>
                                    <td>
                                        <div class="shipping_box">
                                            <ul class="list">
                                                <li><a href="#">Flat Rate: $5.00</a></li>
                                                <li><a href="#">Free Shipping</a></li>
                                                <li><a href="#">Flat Rate: $10.00</a></li>
                                                <li class="active"><a href="#">Local Delivery: $2.00</a></li>
                                            </ul>
                                            <h6>Calculate Shipping <i class="fa fa-caret-down" aria-hidden="true"></i></h6>
                                            <select class="shipping_select">
                                                <option value="1">Bangladesh</option>
                                                <option value="2">India</option>
                                                <option value="4">Pakistan</option>
                                            </select>
                                            <select class="shipping_select">
                                                <option value="1">Select a State</option>
                                                <option value="2">Select a State</option>
                                                <option value="4">Select a State</option>
                                            </select>
                                            <input type="text" placeholder="Postcode/Zipcode">
                                            <a class="gray_btn" href="#">Update Details</a>
                                        </div>
                                    </td>
                                </tr> -->
                            <tr class="out_button_area">
                                <td class="d-none-l">

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <!-- <a class="gray_btn" href="#">Continue Shopping</a> -->
                                        <a class="primary-btn ml-2" href="checkout.php">Proceed to checkout</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->



<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>
<!--================ End footer Area  =================-->