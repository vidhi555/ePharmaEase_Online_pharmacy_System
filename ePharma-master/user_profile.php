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
        $query = "UPDATE ep_users SET `name`= :name,`email`= :email ,`mobile`= :mno,`address`= :address ,`gender`= :gender,image=:photo WHERE u_id= :uid";
        $update_user = $conn->prepare($query);
        $update_user->execute([
            'name' => $name,
            'email' => $email,
            'mno' => $mobile,

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
$page_title = "ePharmaEase - Profile";
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
                    <div class="col-lg-6 col-md-6 col-12">



                        <?php if (!empty($_SESSION['success_msg'])) { ?>
                            <div class="alert alert-info" style="background-color: #e7f3fe;border-left: 6px solid #2196F3;">
                                <?= $_SESSION['success_msg'] ?>
                            </div>
                            <?php unset($_SESSION['success_msg']); ?>
                        <?php } ?>


                        <div class="profile-card shadow-sm border-0 rounded-4 text-center p-4">

                            <img src="uploads/<?= $fetch_user['image'] ?>"
                                class="imguser rounded-circle mx-auto mb-3"
                                width="140" height="140" style="border: 1px solid lightblue;"
                                alt="User Image">

                            <h5 class="fw-bold mb-1"><?= ucfirst($fetch_user['name']) ?></h5>
                            <p class="text-muted mb-3">Customer</p>

                            <div class="text-start small">
                                <p><strong>Email:</strong><?= $fetch_user['email'] ?></p>
                                <p><strong>Mobile:</strong> <?= substr($fetch_user['mobile'], 0, 3) . " " . substr($fetch_user['mobile'], 3, 11) ?></p>
                                <p><strong>Gender:</strong> <?= $fetch_user['gender'] ?></p>

                                <p><strong>Address:</strong> <?= $fetch_user['address'] ?></p>
                            </div>
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Modify Profile</button>
                        </div>
                    </div>
                    <!-- Profile starts -->
                     <div class="col-lg-6 col-md-6 col-12 mt-3">
                            <div class="profile-card shadow-sm border-0 rounded-4 text-center p-4">
                                 <?php 
            $errors= [];
try{
    if(isset($_POST['update_password'])){
        $old_pwd = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $hash_password = password_hash($new_password,PASSWORD_DEFAULT);

        if(empty($old_pass) || empty($new_password) || empty($confirm_password)){
            $errors[] = "Fields Are Empty! Please Fill the password!";
        }
       
        if($new_password !== $confirm_password){
            $errors[] = "Password Not Matched With confirm Password!!!!";
            // sweetAlert("Wrong","Password Not Matched With confirm Password!!!!","warning");
        }
        if(!preg_match('/^[a-zA-Z0-9]{6}$/',$new_password)){
          $errors[] = "Password must be exactly 6 characters (letters & numbers only)";
        }

        $old_pass = $conn->prepare("SELECT * FROM ep_users WHERE u_id = :uid");
        $old_pass->execute([
            
            'uid'=>$uid
        ]);
        
        $fetch_user = $old_pass->fetch(PDO::FETCH_ASSOC);
        
         if($old_pwd !== $fetch_user['password']){
            sweetAlert("Password Not Matched","","warning");
         }
        else{
               if($new_password !== $confirm_password){
                    sweetAlert("Wrong!","Password Not Matched With confirm Password!!!!","warning");
               }else{
                    $update_pwd = $conn->prepare("UPDATE ep_users SET password = :pwdd WHERE u_id = :id");
                    $update_pwd->execute([
                        'pwdd'=>$hash_password,
                        'id'=>$uid
                    ]);
                    sweetAlert("Done","Password Update Successful...","success");
               }
               
            

        }

    }
}catch(PDOException $e){
    echo $e;
}


            ?>
  
                                <h3 class="fw-bold mb-1">Change Password</h3>
                                <img class="banner_img" src="img/image-removebg-preview.png" alt="" width="250px">

                                <form method="post" >
                                        <div class="text-start small">

                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control custom-input" placeholder="Enter current password" >
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-control custom-input" placeholder="Enter new password" >
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control custom-input" placeholder="Confirm new password" >
                                        </div>

                                    

                                    </div>
                                    <button class="btn btn-primary" name="update_password" type="submit">Update Password</button>
                                </form>
                            </div>
                        </div>
                     <!-- Prodile End -->

                    <!-- ORDER HISTORY -->
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12 mt-3">
                        <div class="order-card shadow-sm border-0 rounded-4 p-4 ">
                            <a href="tracking-order.php"><img src="img/track-removebg-preview.png" id="order_tracking_icon" width="50px" height="50px" alt="track"></a>
                            <h3 class="fw-bold mb-4" style="color:#1f2937;">Order History</h3>
                            <div class="row">

                                <?php
                                try {
                                    if ($fetch_user) {
                                        $cuurent_user =  $fetch_user['u_id'];
                                        $order_detail = $conn->prepare("SELECT DISTINCT o.u_id , o.* ,o.*
FROM ep_orders_master o
JOIN ep_orders_items oi ON o.o_id = oi.o_id
JOIN ep_products p ON p.p_id = oi.p_id
WHERE o.u_id = :id

ORDER BY o.o_id DESC");
                                        $order_detail->execute(['id' => $cuurent_user]);
                                        //fetch orders
                                        $fetch_oredr = $order_detail->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($fetch_oredr as $order) { ?>
                                            <div class="col-lg-6 col-md-12 mt-4" id="colmd8">
                                                <div class="order-card shadow-sm">

                                                    <!-- Header -->
                                                    <div class="order-header d-flex justify-content-between">
                                                        <span class="order-date"><?= date('d-m-Y', strtotime($order['oder_date'])) ?></span>

                                                        <span class="badge <?= $order['payment_status'] == 'paid' ? 'bg-success' : 'bg-danger' ?>">
                                                           <?= $order['payment_method'] ?> (<?= ($order['payment_status'] == 'paid') ? '✅Payment Done' : 'Pending Payment' ?>)
                                                        </span>
                                                        <span>
                                                            <a href="reorder.php?o_id=<?= $order['o_id'] ?>" onclick="return confirm('Are you sure to re-Order these Items???');"><img src="img/reorder-removebg-preview.ico" class="tooltip-box" alt="icon"></a>
                                                        </span>
                                                    </div>

                                                    <!-- Products list inside order -->
                                                    <?php
                                                    $inside_loop = $conn->prepare("SELECT * FROM ep_orders_items oi 
JOIN ep_products p ON p.p_id = oi.p_id
WHERE oi.o_id = :id");
                                                    $inside_loop->execute(['id' => $order['o_id']]);
                                                    $fetch_inside = $inside_loop->fetchAll(PDO::FETCH_ASSOC);


                                                    foreach ($fetch_inside as $p) { ?>
                                                        <div class="product-row d-flex align-items-center">

                                                            <img src="../LearnAdmin/All_images_uploads/<?= $p['image'] ?>" class="product-img">

                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="mb-1"><?= $p['name'] ?></h6>
                                                                <small>Qty: <?= $p['qty'] ?> • $<?= $p['price'] ?></small>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <!-- Footer -->
                                                    <div class="order-footer d-flex justify-content-between">
                                                        <span>Order #<?= $order['o_id'] ?></span>

                                                        <span>Total: $<?= $order['total_amount'] ?></span>
                                                        <!-- <span class="status-badge <?= $order['order_status'] ?>">
                                                        <?= $order['order_status'] ?>
                                                    </span> -->
                                                    <?php if ($order['order_status'] == 'Placed') { ?>

                                                <span><a class="btn btn-danger" href="cancel_orders.php?o_id=<?= $order['o_id'] ?>" onclick="return confirm('Are you sure to Cancel the Order???');">Cancel</a></span>

                                            <?php }else{ ?>
                                            <!-- <span>Order Status:<?= ucfirst($order['order_status']) ?></span> -->
                                             <?php
                                            $today = date('d/m/Y');

                                            if($order['order_status']=='delivered'){
                                                echo "<span style='color:green;'>Delivered</span>";
                                            }
                                           elseif($order['order_status']=='cancelled'){
                                                echo "<span style='color:red;'>Cancelled</span>";
                                            }
                                             elseif($today > $order['expected_date']){
                                                echo "<span style='color:red;'>Delivery Delayed</span>";
                                            }
                                            else{
                                                echo "<span style='color:orange;'>On the way</span>";
                                            }
                                            ?>
                                            <?php } ?>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php } ?>





                                <?php    }
                                    if ($order_detail->rowCount() == 0) {
                                        echo "<p style='color:darkred;'>No Orders Found.<br>
You haven’t placed any orders yet.<br>
Start browsing medicines and place your first order today</p>";
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
    .profile-card .imguser {
        width: 140px;
        height: 140px;
        object-fit: contain;
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

    .badge-date {
        background: #d4dced;
        color: #3b82f6;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        width: fit-content;
    }

    .badge-cancel {
        background: #edd8d4;
        color: #dc3545;
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


    /* ================= ORDER HISTORY MAIN CARD ================= */
    .order-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px;
        border: 1px solid #eef1f6;
        transition: .35s ease;
        position: relative;
        overflow: hidden;
    }

    /* soft hover */
    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(0, 0, 0, .07);
    }

    /* subtle top gradient line */
    .order-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #4f46e5, #06b6d4);
    }

    /* ================= HEADER ================= */
    .order-header {
        margin-bottom: 10px;
    }

    .order-date {
        font-size: 12px;
        font-weight: 600;
        background: #eef2ff;
        color: #4f46e5;
        padding: 5px 12px;
        border-radius: 20px;
    }

    /* payment badge */
    .badge.bg-success {
        background: #dcfce7 !important;
        color: #15803d;
        font-weight: 600;
        border-radius: 20px;
        padding: 5px 12px;
    }

    .badge.bg-danger {
        background: #fee2e2 !important;
        color: #dc2626;
        font-weight: 600;
        border-radius: 20px;
        padding: 5px 12px;
    }

    /* ================= PRODUCT ROW ================= */
    .product-row {
        padding: 10px 0;
        border-bottom: 1px dashed #eee;
        transition: .2s;
    }

    .product-row:hover {
        background: #f8fbff;
        border-radius: 8px;
    }

    .product-row:last-child {
        border-bottom: none;
    }

    /* product image */
    .product-img {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #edf0f6;
    }

    /* product name */
    .product-row h6 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #1f2937;
    }

    /* qty price text */
    .product-row small {
        color: #6b7280;
        font-size: 12.5px;
    }

    /* ================= FOOTER ================= */
    .order-footer {
        margin-top: 10px;
        padding-top: 8px;
        border-top: 1px solid #f1f3f7;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    /* status chip */
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        text-transform: capitalize;
    }

    /* dynamic status colors */
    .status-badge.confirmed {
        background: #dcfce7;
        color: #15803d;
    }

    .status-badge.placed {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-badge.pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-badge.cancelled {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* ================= TRACK ICON ================= */
    #order_tracking_icon {
        position: absolute;
        right: 18px;
        top: 18px;
        opacity: .75;
        transition: .25s;
    }

    #order_tracking_icon:hover {
        transform: scale(1.12) rotate(6deg);
        opacity: 1;
    }

    /* ================= MOBILE ================= */
    @media(max-width:768px) {
        .product-img {
            width: 45px;
            height: 45px;
        }

        .order-card {
            padding: 15px;
        }
    }

    /* ===== Individual Order Box ===== */


    /* .img_order {
        float: right;
    width: 217px;
    height: 192px;
    position: absolute;
    right: 80px;
    top: 125px;
    } */
    /* ===== Responsive Tweaks ===== */
    @media (max-width: 768px) {

        .profile-card,
        .order-card {
            padding: 22px;
        }

        .profile-card .imguser {
            width: 110px;
            height: 110px;
        }

        .order-history h2 {
            font-size: 24px;
        }
       
    }
</style>