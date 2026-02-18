<?php

//database connection
require_once("connection/db.php");

$uid = $_SESSION['user_id'];

//update
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $tmp_photo = $_FILES['photo']['tmp_name'];
        $ext = pathinfo($photo, PATHINFO_EXTENSION);
        $img_name = "user" . time() . "." . $ext;
        $allowed = ['jpg', 'jpeg', 'png', 'webg'];
        $target = 'uploads/' . basename($img_name);

        if (!in_array(strtolower($ext), $allowed)) {
            $errors[] = "Invalid Image Type!!";
        }
        if (!move_uploaded_file($tmp_photo, $target)) {
            $errors[] = "Image Uploading Fail!";
        }
    } else {
        $img_name = $_POST['old_image'];
    }

    if (!empty($errors)) {
        sweetAlert("Error", "Please Try Again!", "error");
    } else {
        $query = "UPDATE ep_users SET `name`= :name,`email`= :email ,`mobile`= :mno,`dob`= :dob,`address`= :address ,`gender`= :gender,image=:photo WHERE u_id= :uid";
        $update_user = $conn->prepare($query);
        $update_user->execute([
            'name' => $name,
            'email' => $email,
            'mno' => $mobile,
            'dob' => $dob,
            'address' => $address,
            'gender' => $gender,
            'photo' => $img_name,
            'uid' => $uid
        ]);
        $_SESSION['success_msg'] = "Update Successfully";
        header("location:user_profile.php");
        exit;
    }
}

//Dynamic title
$page_title = "ePharmaEase - Forgot Password";
require_once('header.php');


try {
    if (isset($uid)) {
        $logged_user = $conn->prepare("SELECT * FROM ep_users WHERE u_id = :uid");
        $logged_user->execute(['uid' => $uid]);
        $fetch_user = $logged_user->fetch(PDO::FETCH_ASSOC);
        if ($fetch_user) {
?>


            <!-- ================ start banner area ================= -->
            <section class="blog-banner-area fade-up" id="category">
                <div class="container h-100">
                    <div class="blog-banner">
                        <div class="text-center">
                            <h1>Profile</h1>
                            <nav aria-label="breadcrumb" class="banner-breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ================ end banner area ================= -->

            <div class="container py-5">
                <div class="row g-4">
                    <!-- USER PROFILE -->
                    <div class="col-lg-4 col-md-5 col-12">


                        
                        <?php if (!empty($_SESSION['success_msg'])) { ?>
                            <div class="alert alert-info" style="background-color: #e7f3fe;border-left: 6px solid #2196F3;">
                                <?= $_SESSION['success_msg'] ?>
                            </div>
                            <?php unset($_SESSION['success_msg']); ?>
                        <?php } ?>


                        <div class="profile-card shadow-sm border-0 rounded-4 text-center p-4">

                            <img src="uploads/<?= $fetch_user['image'] ?>"
                                class="rounded-circle mx-auto mb-3"
                                width="140" height="140" style="border: 1px solid lightblue;"
                                alt="User Image">

                            <h5 class="fw-bold mb-1"><?= ucfirst($fetch_user['name']) ?></h5>
                            <p class="text-muted mb-3">Customer</p>

                            <div class="text-start small">
                                <p><strong>Email:</strong><?= $fetch_user['email'] ?></p>
                                <p><strong>Mobile:</strong> <?= substr($fetch_user['mobile'], 0, 3) . " " . substr($fetch_user['mobile'], 3, 11) ?></p>
                                <p><strong>Gender:</strong> <?= $fetch_user['gender'] ?></p>
                                <p><strong>Birth Date:</strong> <?= date("d/m/Y", strtotime($fetch_user['dob'])) ?></p>
                                <p><strong>Address:</strong> <?= $fetch_user['address'] ?></p>
                            </div>
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Modify Profile</button>
                        </div>
                    </div>

                    <!-- ORDER HISTORY -->
                    <div class="col-lg-8 col-md-7 col-12">
                        <div class="order-card shadow-sm border-0 rounded-4 p-4 ">
                            <a href="tracking-order.php"><img src="img/track-removebg-preview.png" id="order_tracking_icon" width="50px" height="50px" alt="track"></a>
                            <h3 class="order-history fw-bold mb-4">Order History</h3>


                            <?php
                            try {
                                if ($fetch_user) {
                                    $cuurent_user =  $fetch_user['u_id'];
                                    $order_detail = $conn->prepare("SELECT * FROM ep_orders_items i  JOIN ep_orders_master m ON i.o_id = m.o_id JOIN ep_products p ON p.p_id = i.p_id WHERE m.u_id = :id");
                                    $order_detail->execute(['id' => $cuurent_user]);
                                    //fetch orders
                                    $fetch_oredr = $order_detail->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($fetch_oredr as $o) {
                            ?>
                                        <div class="btm order-item mb-3 p-3 rounded-3 bg-light">
                                            <?php if ($o['order_status'] == 'delivered') { ?>
                                                <div class="order-status">
                                                    <span class="status-placed">✔ Package Delivered</span>
                                                </div>

                                            <?php   } ?>
                                            <?php
                                            if ($o['payment_status'] == 'pending') {
                                                $class = 'status-cancelled';
                                            } else {
                                                $class = 'status-placed';
                                            }
                                            ?>
                                            <div class="order-status">
                                                <p class="<?= $class ?>">Payment: <?= $o['payment_status'] ?> (<?= ($o['payment_method'] == 'COD') ? 'Cash on Delivery' : 'Online Payment' ?>)</p>
                                            </div>
                                            <img class="img_order" src="../LearnAdmin/upload/<?= $o['image'] ?>" alt="">
                                            <p class="mb-1"><strong>Product:</strong> <?= $o['name'] ?></p>
                                            <p class="mb-1"><strong>Order ID:</strong> <?= $o['o_id'] ?></p>
                                            <p class="mb-1"><strong>Quantity:</strong> <?= $o['qty'] ?></p>

                                            <p class="mb-1"><strong>Price:</strong> ₹<?= $o['price'] ?></p>
                                            
                                            
                                            <?php

                                            // cancel order button
                                            // || $o['order_status'] == 'confirmed'
                                            if ($o['order_status'] == 'Placed') { ?>

                                                <a class="btn btn-danger" href="cancel_orders.php?o_id=<?= $o['o_id'] ?>">Cancel Order</a>

                                            <?php }
                                            ?>

                                        </div>


                            <?php    }
                                    if ($order_detail->rowCount() == 0) {
                                        echo "<p style='color:darkred;'>No Orders Found.<br>
You haven’t placed any orders yet.<br>
Start browsing medicines and place your first order today</p>";
                                    }
                                }
                            } catch (PDOException $e) {
                                echo $e;
                            }
                            ?>
                            <!-- Order Item -->
                            <!-- <div class="order-box mb-3 p-3 rounded-3 bg-light">
          <p class="mb-1"><strong>Product:</strong> Amoxicillin 500 mg</p>
          <p class="mb-1"><strong>Order ID:</strong> #ORD1025</p>
          <p class="mb-1"><strong>Quantity:</strong> 1</p>
          <p class="mb-0 text-success fw-semibold">Delivered</p>
        </div>

        <div class="order-box mb-3 p-3 rounded-3 bg-light">
          <p class="mb-1"><strong>Product:</strong> Paracetamol</p>
          <p class="mb-1"><strong>Order ID:</strong> #ORD1026</p>
          <p class="mb-1"><strong>Quantity:</strong> 2</p>
          <p class="mb-0 text-warning fw-semibold">Pending</p>
        </div> -->

                            <!-- Empty State -->
                            <!--
        <div class="alert alert-info">
          No orders found.
        </div>
        -->

                        </div>
                    </div>

                </div>
            </div>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header" style="margin-top: 100px;">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Edit User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <?php
                    try {
                        if (isset($_SESSION['user_id'])) {
                            $uid = $_SESSION['user_id'];
                            $logged_user = $conn->prepare("SELECT * FROM ep_users WHERE u_id = :uid");
                            $logged_user->execute(['uid' => $uid]);
                            $fetch_data = $logged_user->fetch(PDO::FETCH_ASSOC);
                            if ($fetch_data) {
                    ?>
                                <form class="row login_form text-center" action="" enctype="multipart/form-data" id="register_form" method="post">
                                    <div class="col-md-12 form-group">
                                        <img class="rounded-circle text-center" src="uploads/<?= $fetch_data['image'] ?>" alt="user" width="100px" height="100px">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input type="text" class="form-control" id="name" name="name" value="<?= $fetch_data['name'] ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address'" value="<?= $fetch_data['email'] ?>">
                                    </div>

                                    <!-- <div class="col-md-12 form-group">
                                        <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Mobile No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mobile No.'" value="<?= $fetch_data['phone'] ?>">
                                    </div> -->
                                    <div class="col-lg-12 form-group">
                                        <input id="phone" type="tel" name="phone" style="width: 353px;<?= $class ?>" class="form-control" placeholder="Mobile No." placeholder="Mobile No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mobile No.'" value="<?= $fetch_data['mobile'] ?>">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input type="date" class="form-control" id="dob" name="dob" placeholder="Birth Date" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Birth Date'" value="<?= $fetch_data['dob'] ?>">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <textarea class="form-control" name="address" id="address" placeholder="Address" cols="30" rows="4" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'"><?= $fetch_data['address'] ?></textarea>
                                    </div>

                                    <div class="col-lg-4 text-muted">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male" <?= $fetch_data['gender'] == 'Male' ? 'checked' : '' ?>>

                                            <label class="form-check-label" for="male">
                                                Male
                                            </label>
                                        </div>
                                        <div class="form-check ">
                                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female" <?= $fetch_data['gender'] == 'Female' ? 'checked' : '' ?>>

                                            <label class="form-check-label" for="female">
                                                Female
                                            </label>
                                        </div>

                                    </div>

                                    <div class="col-md-12 form-group">
                                        <input type="hidden" name="old_image" value="<?= $fetch_data['image'] ?>">
                                        <label style="text-align: left;">Profile Photo:</label>
                                        <input type="file" class="form-control" id="photo" name="photo" value="<?= $fetch_data['image'] ?>">
                                    </div>

                                    <div class="col-md-12 form-group">

                                        <button class="update_profile_btn" type="submit" name="update" value="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>

                    <?php
                            }
                        } else {
                            echo "User Not Logged In!!!";
                        }
                    } catch (PDOException $e) {
                        echo $e;
                    }
                    ?>


                </div>
            </div>
            <!--================ Start footer Area  =================-->
            <?php require_once('footer.php'); ?>
            <?php require_once('sweetAlert.php'); ?>
            <!--================ End footer Area  =================-->

<?php
        }
    }
} catch (PDOException $e) {
    echo $e;
}
?>
<style>
    /* ===== Global Smoothness ===== */
    body {
        background: #f5f7fb;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    /* ===== Profile & Order Cards ===== */
    .profile-card,
    .order-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.06);
        transition: all 0.35s ease;
    }

    /* Hover effect */
    .profile-card:hover,
    .order-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
    }

    /* ===== Profile Image ===== */
    .profile-card img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 6px solid #eef4ff;
        background: #fff;
    }

    /* ===== Name & Role ===== */
    .profile-card h4 {
        margin-top: 15px;
        font-weight: 700;
        color: #1f2937;
    }

    .profile-card span {
        font-size: 14px;
        color: #6b7280;
    }

    /* ===== Profile Details ===== */
    .profile-info {
        margin-top: 25px;
        text-align: left;
    }

    .profile-info p {
        font-size: 14.5px;
        color: #374151;
        margin-bottom: 10px;
    }

    .profile-info p strong {
        color: #111827;
    }

    .badge-pending {
        background: #edd4d4;
        color: #fb2626;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        width: fit-content;
    }

    .update_profile_btn {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
        margin-top: 18px;
        transition: all 0.3s ease;
        color: #ffffff;
        width: 100%;
    }

    .update_profile_btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.35);
    }

    /* ===== Modify Button ===== */
    .profile-card .btn-primary {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
        margin-top: 18px;
        transition: all 0.3s ease;
    }

    .profile-card .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.35);
    }

    /* ===== Order History Section ===== */
    .order-history h2 {
        font-weight: 700;
        color: #111827;
        margin-bottom: 25px;
    }

    /* ===== Individual Order Box ===== */
    .order-item {
        background: #f9fafb;
        border-radius: 16px;
        padding: 22px 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border-left: 6px solid #3b82f6;
    }

    .order-item:hover {
        background: #eef5ff;
        transform: translateY(-3px);
    }

    /* ===== Order Text ===== */
    .order-item p {
        margin-bottom: 6px;
        font-size: 14.5px;

    }

    .order-item strong {
        color: #111827;
    }

    /* ===== Status Badge ===== */
    .order-status {
        display: inline-block;
        margin-top: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
    }

    /* Status Colors */
    .status-placed {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #fff7ed;
        color: #9a3412;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .img_order {
        float: right;
        width: 150px;
        height: 150px;
    }

    /* ===== Responsive Tweaks ===== */
    @media (max-width: 768px) {

        .profile-card,
        .order-card {
            padding: 22px;
        }

        .profile-card img {
            width: 110px;
            height: 110px;
        }

        .order-history h2 {
            font-size: 24px;
        }
    }
</style>