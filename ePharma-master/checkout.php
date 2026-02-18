<?php
require_once("connection/db.php");

// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
// } else {
//     $user_id = "";
//     header("Location:login.php");
// }
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
        $guest_id = $user_id ? NULL : session_id();

       
        // if ($user_id) {
        //     $check = $conn->prepare(
        //         "SELECT cart_id FROM ep_cart WHERE p_id = :pid AND u_id = :uid"
        //     );
        //     $check->execute([
        //         'pid' => $pid,
        //         'uid' => $user_id
        //     ]);
        // } else {
        //     $check = $conn->prepare(
        //         "SELECT cart_id FROM ep_cart WHERE p_id = :pid AND guest_id = :gid"
        //     );
        //     $check->execute([
        //         'pid' => $pid,
        //         'gid' => $guest_id
        //     ]);
        // }

require_once('order_checkout_logic.php');


?>

<!--================ Start Header Menu Area =================-->
<?php
$page_title = "ePharmaEase - Checkout page";
require_once('header.php');
?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>Product Checkout</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ================ end banner area ================= -->

<!--================Checkout Area =================-->
<section class="checkout_area section-margin--small">
    <div class="container">
        <div class="returning_customer">
            <div class="check_title">
                <h2>Returning Customer? <a href="login.php">Click here to login</a></h2>
            </div>
            <!-- <p>If you have shopped with us before, please enter your details in the boxes below. If you are a new
                customer, please proceed to the Billing & Shipping section.</p> -->
            <!-- <form class="row contact_form" action="#" method="post" novalidate="novalidate">
                <div class="col-md-6 form-group p_star">
                    <input type="text" class="form-control" placeholder="Username or Email*" onfocus="this.placeholder=''" onblur="this.placeholder = 'Username or Email*'" id="name" name="name">

                    <span class="placeholder" data-placeholder="Username or Email"></span>
                </div>
                <div class="col-md-6 form-group p_star">
                    <input type="password" class="form-control" placeholder="Password*" onfocus="this.placeholder=''" onblur="this.placeholder = 'Password*'" id="password" name="password">
                    <span class="placeholder" data-placeholder="Password"></span>
                </div>
                <div class="col-md-12 form-group">
                    <button type="submit" value="submit" class="button button-login">login</button>
                    <div class="creat_account">
                        <input type="checkbox" id="f-option" name="selector">
                        <label for="f-option">Remember me</label>
                    </div>
                    <a class="lost_pass" href="#">Lost your password?</a>
                </div>
            </form> -->
        </div>
        <!-- <div class="cupon_area">
            <div class="check_title">
                <h2>Have a coupon? <a href="#">Click here to enter your code</a></h2>
            </div>
            <input type="text" placeholder="Enter coupon code">
            <a class="button button-coupon" href="#">Apply Coupon</a>
        </div> -->

        

        <?php if(!empty($errors)){ 
            $class = "box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);border:1px solid #f44336;";
            ?>
        
        <div class="alert alert-danger mt-3" style=" background-color: #ffdddd;border-left: 6px solid #f44336;padding-left: 20px;">
            <ul>
                <?php foreach($errors as $er){ ?>
                <li><?= $er ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <div class="billing_details shadow-lg mt-3" style="border-radius: 20px;">
            <div class="row">
                <div class="col-lg-8" style="padding: 50px;">
                    <h3>Billing Details</h3>
                    <form class="row contact_form" action="" method="post" novalidate="novalidate">
                        <div class="col-md-6 form-group p_star">
                            <input type="text" class="form-control" id="first" name="fname" placeholder="First Name" style="<?= $class ?>">
                            <!-- <span class="reuired_field">*</span> -->
                            <!-- <span class="placeholder" data-placeholder="First name"></span> -->
                        </div>
                        <div class="col-md-6 form-group p_star">
                            <input type="text" class="form-control" id="last" name="lname" placeholder="Last Name" style="<?= $class ?>">
                            <!-- <span class="placeholder" data-placeholder="Last name"></span> -->
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="company" name="company" placeholder="Company name(Optional)">
                        </div>
                        <div class="col-md-6 form-group p_star">
                            <input type="number" class="form-control" id="mno" name="mno" placeholder="Phone Number" style="<?= $class ?>">
                            <!-- <span class="placeholder" data-placeholder="Phone number"></span> -->
                        </div>
                        <div class="col-md-6 form-group p_star">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" style="<?= $class ?>">
                            <!-- <span class="placeholder" data-placeholder="Email Address"></span> -->
                        </div>
                        <div class="col-md-12 form-group p_star">
                            <select class="country_select" name="country" style="<?= $class ?>">
                                <option selected="selected" value="">Select Country</option>
                                <option value="India">India</option>
                                <option value="Iran">Iran</option>
                                <option value="US">US</option>
                                <option value="UK">UK</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group p_star">
                            <input type="text" class="form-control" id="add1" name="address" placeholder="Address line 01" style="<?= $class ?>">
                            <!-- <span class="placeholder" data-placeholder="Address line 01"></span> -->
                        </div>
                        <div class="col-md-12 form-group p_star">
                            <input type="text" class="form-control" id="add2" name="add2" placeholder="Address line (Optional)">
                            <!-- <span class="placeholder" data-placeholder="Address line 02"></span>  -->
                        </div>
                        <div class="col-md-12 form-group p_star">
                            <input type="text" class="form-control" id="city" name="city" placeholder="Town/City" style="<?= $class ?>">
                            <!-- <span class="placeholder" data-placeholder="Town/City"></span> -->
                        </div>
                        <div class="col-md-12 form-group p_star">
                            <select class="country_select" name="district" style="<?= $class ?>">
                                <option value="" selected="selected">Select District</option>
                                <option value="Ahmedabad">Ahmedabad</option>
                                <option value="Surat">Surat</option>
                                <option value="Vadodara">Vadodara</option>
                                <option value="Rajkot">Rajkot</option>
                                <!-- <option value="Jaipur">Jaipur</option>
                                <option value="Udaipur">Udaipur</option>
                                <option value="Mumbai">Mumbai</option> -->
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="number" class="form-control" id="zip" name="zip" placeholder="Postcode/ZIP" style="<?= $class ?>">
                        </div>
                        <?php if(!empty($guest_id)){ ?>
                        <div class="col-md-12 form-group">
                            <div class="creat_account">
                                <input type="checkbox" id="c_acc" name="create_account">
                                <label for="f-option2">Create an account?</label>
                            </div>
                        </div>
                        
                         <div class="col-md-12 form-group p_star" id="password_field" style="display:none;">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        
                        <?php } ?>
                        <script>
                            let is_check = document.getElementById('c_acc');
                            let pwd_field = document.getElementById('password_field');
                            is_check.addEventListener("change", function(){
                                if(this.checked){
                                    // alert("Checked");
                                    pwd_field.style.display = "block";
                                }else{
                                    pwd_field.style.display = "none";
                                }
                            });
                        </script>
                        
                        <div class="col-md-12 form-group mb-0">
                            <div class="creat_account">
                                <h3>Shipping Details</h3>
                                <input type="checkbox" id="f-option3" name="selector">
                                <label for="f-option3">Ship to a different address?</label>
                            </div>
                            <textarea class="form-control" name="message" id="message" rows="1" placeholder="Order Notes"></textarea>
                        </div>

                </div>
                <div class="col-lg-4">
                    <div class="order_box">
                        <h2>Your Order</h2>
                        <ul class="list">
                            <li><a href="#">
                                    <h4><strong>Product</strong> <span><strong>Total</strong></span></h4>
                                </a></li>
                            <?php
                            try {
                                $grandTot = 0;
                                if($user_id){
                                    $cart = $conn->prepare("SELECT * FROM ep_cart WHERE u_id = :uid");
                                    $cart->execute(['uid' => $user_id]);
                                }else{
                                    $cart = $conn->prepare("SELECT * FROM ep_cart WHERE guest_id = :gid");
                                    $cart->execute(['gid' => $guest_id]);
                                }
                                $cart_items = $cart->fetchAll(PDO::FETCH_ASSOC);
                                if ($cart->rowCount() > 0) {
                                    foreach ($cart_items as $c) { ?>
                                        <li><a href="#"><?= $c['pname'] ?>
                                                <span class="middle">x<?= $c['qty'] ?></span> <span class="last">₹<?= $subtotal = $c['price'] * $c['qty']; ?></span></a></li>
                            <?php    }
                                } else {
                                    echo "Your Cart is Empty";
                                }
                            } catch (PDOException $e) {
                                echo $e;
                            }

                            ?>
                        </ul>
                        <ul class="list list_2">
                            <?php
                            //calculate grand total
                            try {
                            if($user_id){
                                $total = $conn->prepare("SELECT SUM(qty * price) as gTotal FROM ep_cart WHERE u_id = $user_id");
                            } else{
                                $total = $conn->prepare("SELECT SUM(qty * price) as gTotal FROM ep_cart WHERE guest_id = '$guest_id'");

                            }   
                            if ($total->execute()) {
                                    $result = $total->fetch(PDO::FETCH_ASSOC);
                                    // echo $result['gTotal'];

                            ?>
                                    <hr>
                                    <li><a href="#">Subtotal <span>₹<?= $result['gTotal'] ?></span></a></li>
                                    <li><a href="#">Shipping <span>Flat rate: ₹<?= $shipping; ?> </span>
                                            <?php if ($result['gTotal'] <= 1000) { ?>
                                                <span style="margin: 10px;text-align: end;border-bottom: 1px solid darkgray;">Add ₹<?= 1000 - $result['gTotal'] ?> more to get FREE shipping!
                                                    Current delivery charge: ₹50</span>
                                            <?php } else { ?>
                                                <span style="margin: 10px;text-align: end;border-bottom: 1px solid darkgray;">Congratulations! Your order qualifies for FREE delivery</span>
                                            <?php    } ?>
                                        </a></li>
                                    <li><a href="#">Total <span>₹<?= $grand_total; ?></span></a></li>


                            <?php
                                }
                            } catch (PDOException $e) {
                                echo $e;
                            }
                            ?>
                        </ul>
                        <div class="payment_item">
                            <div class="radion_btn">
                                <input type="radio" id="f-option5" name="payment" value="Bank">
                                <label for="f-option5">Check payments</label>
                                <div class="check"></div>
                            </div>
                            <p>Please send a check to Store Name, Store Street, Store Town, Store State / County,
                                Store Postcode.</p>
                        </div>
                        <!-- <div class="payment_item">
                            <div class="radion_btn">
                                <input type="radio" id="f-option6" name="payment" value="PayPal">
                                <label for="f-option6">Paypal </label>
                                <img src="img/product/card.jpg" alt="">
                                <div class="check"></div>
                            </div>
                            <p>Pay via PayPal; you can pay with your credit card if you don’t have a PayPal
                                account.</p>
                        </div> -->
                        <div class="payment_item active">
                            <div class="radion_btn">
                                <input type="radio" id="f-option7" name="payment" value="COD">
                                <label for="f-option7">COD </label>
                                <img src="img/product/card.jpg" alt="">
                                <div class="check"></div>
                            </div>
                            <p>Pay with cash on delivery!</p>
                        </div>
                        <div class="creat_account">
                            <input type="checkbox" id="f-option4" name="selector">
                            <label for="f-option4">I’ve read and accept the </label>
                            <a href="#">terms & conditions*</a>
                        </div>
                        <div class="text-center">
                            <!-- <a type="submit" class="button button-paypal" name="place_order" href="confirmation.php">Place Order</a> -->
                            <button type="submit" name="place_order" class="button button-paypal">Place Order</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>
<!--================End Checkout Area =================-->



<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>
<?php require_once('sweetAlert.php'); ?>
<!--================ End footer Area  =================-->