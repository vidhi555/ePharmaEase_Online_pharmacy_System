<?php

//database connection
require_once("connection/db.php");
try {
    $get_token = $_GET['token'] ?? '';
    // echo $get_token;
    $verify_link = $conn->prepare("SELECT * FROM ep_users WHERE token = :token AND expity_token >= NOW()");
    $verify_link->execute([
        'token' => $get_token
    ]);
    $fetch_user_data = $verify_link->fetch(PDO::FETCH_ASSOC);
    if ($fetch_user_data) {
        $uid = $fetch_user_data['u_id'];
        // echo $uid;
    }

    if (!$verify_link) {
        sweetAlert("Warning", "Your link is expired!", "warning");
    } else {
        if (isset($_POST['reset'])) {
            $pwd = $_POST['password'];
            $cpwd = $_POST['confirm_password'];

            if ($pwd !== $cpwd) {
                sweetAlert("Password not Matched", "", "warning");
            } else {
                $hash_pwd = password_hash($pwd, PASSWORD_DEFAULT);
                $reset_password  = $conn->prepare("UPDATE ep_users SET password = :password, token = NULL, expity_token = NULL WHERE u_id = :id");
                $result = $reset_password->execute([
                    'password' => $hash_pwd,
                    'id' => $uid
                ]);
                if ($result) {
                    $_SESSION['success_msg'] = "Password reset successfully. Please login.";
                    header("Location: login.php");
                    exit;
                } else {
                    sweetAlert("Fail", "Something Went Wrong", "warning");
                }
            }
        }
    }
} catch (PDOException $e) {
    echo $e;
}

//Dynamic title
$page_title = "ePharmaEase - Reset Password";
require_once('header.php');


if (isset($_SESSION['success_msg'])) {
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: '{$_SESSION['success_msg']}',
            icon: 'success'
        });
    </script>";
    unset($_SESSION['success_msg']);
}

?>

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>Reset Password</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reset-password</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ================ end banner area ================= -->

<!--================Forgot Password Box Area =================-->
<section class="login_box_area section-margin">
    <div class="container">
        <div class="row">
            <!-- <div class="col-lg-6">
					<div class="login_box_img">
						<div class="hover">
							<h4>Recover your account?</h4>
							<p>Enter your email and we’ll help you reset your password.</p>
							<a class="button button-account" href="register.php">Create an Account</a>
						</div>
					</div>
				</div> -->

            <div class="col-lg-6">
                <div class="fp_form_inner">
                    <h3>Reset Your Password</h3>
                    <form class="row fp_form" action="#/" id="contactForm" method="post">
                        <div class="col-md-12 form-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter New Password'">
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="password" class="form-control" id="cpassword" name="confirm_password" placeholder="Enter Confirm Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Confirm Password'">
                        </div>

                        <div class="col-md-12 form-group">
                            <button type="submit" name="reset" class="button">Reset Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- </section> -->
<!--================End Login Box Area =================-->

<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>
<?php require_once('sweetAlert.php'); ?>
<!--================ End footer Area  =================-->