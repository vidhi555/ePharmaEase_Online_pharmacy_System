<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Mail Important files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
//database connection
require_once("connection/db.php");

//Dynamic title
$page_title = "ePharmaEase - Forgot Password";
require_once('header.php');

?>

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
	<div class="container h-100">
		<div class="blog-banner">
			<div class="text-center">
				<h1>Forgot Password</h1>
				<nav aria-label="breadcrumb" class="banner-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Fogot-password</li>
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
			<div class="col-lg-6">
				<div class="login_box_img">
					<div class="hover">
						<h4>Recover your account?</h4>
						<p>Enter your email and we’ll help you reset your password.</p>
						<!-- <a class="button button-account" href="register.php">Create an Account</a> -->
					</div>
				</div>
			</div>
			<?php
			try {
				if (isset($_POST['forgot'])) {
					$v_email = $_POST['email'];
					$query = "SELECT * FROM ep_users WHERE email = :email AND role = 'customer'";
					$verify_email = $conn->prepare($query);
					$verify_email->execute(['email' => $v_email]);
					$fetch_vemail = $verify_email->fetch(PDO::FETCH_ASSOC);

					$fp_username = $fetch_vemail['name'] ?? '';
					if (!filter_var($v_email, FILTER_VALIDATE_EMAIL)) {
						sweetAlert("Invalid Email Format", "", "warning");
					} else if (!$fetch_vemail) {
						// echo "Email is not Found";
						sweetAlert("Email Not Found!", "", "warning");
					} else {
						//if mail is exist in DB
						// require_once("send_mail.php");

						$mail = new PHPMailer(true);

						try {
							$mail->isSMTP();
							$mail->Host = 'smtp.gmail.com';
							$mail->SMTPAuth = true;
							$mail->Username = 'pvidhi782@gmail.com';
							$mail->Password = 'ibcialcohyfmmvll';   //must write app password for security
							$mail->SMTPSecure = 'tls';
							$mail->Port = 587;

							$mail->setFrom('pvidhi782@gmail.com', 'ePharmaEase');   //your email
							$mail->addAddress($v_email);    //Receiver email

							$token = bin2hex(random_bytes(32));   // secure token
							// $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));
							$expiry = date("Y-m-d H:i:s", strtotime("+1 day"));


							//update user table
							$update_query = $conn->prepare("UPDATE ep_users SET token=:token ,expity_token = :expiry_time WHERE email = :v_email");
							$update_query->execute([
								'token' => $token,
								'expiry_time' => $expiry,
								'v_email' => $v_email,
							]);
							$reset_link = "http://localhost/ePharmaEase_Project/ePharma-master/reset_password.php?token=$token";
							// $mail->Subject = "Reset Your ePharmaEase Account Password";
							// $mail->Body = str_replace("{{RESET_LINK}}", $resetLink, $message);

							$mail->Subject = 'Reset Your ePharmaEase Account Password';
							$mail->Body    = "Hello {$fp_username},

We received a request to reset the password for your ePharmaEase account.

Please click the link below to reset your password:
{{$reset_link}}

This link is valid for 15 minutes only.
If you did not request a password reset, please ignore this email.

For security reasons, do not share this link with anyone.
Regards,
ePharmaEase Team
Your trusted online pharmacy
";

							$mail->send();
							// echo "Mail sent successfully!";
							sweetAlert("Mail Sent Successfully", "Please Check your G-mail.", "success");
						} catch (Exception $e) {
							echo "Error: {$mail->ErrorInfo}";
						}
					}
				}
			} catch (PDOException $e) {
				echo $e;
			}
			?>
			<div class="col-lg-6">
				<div class="fp_form_inner">
					<h3>Email Verification</h3>
					<form class="row fp_form" id="contactForm" method="post">
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email'">
						</div>


						<div class="col-md-12 form-group">
							<button type="submit" name="forgot" class="button">Verify</button>
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