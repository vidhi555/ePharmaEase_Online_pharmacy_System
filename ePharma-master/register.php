<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("PHPMailer/src/Exception.php");
require_once("PHPMailer/src/PHPMailer.php");
require_once("PHPMailer/src/SMTP.php");

require_once('connection/db.php');
$errors = [];
//registration
if (isset($_POST['submit'])) {
	//collect input
	$username = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirmPassword = $_POST['confirmPassword'];
	$mobile = $_POST['phone'];
	$dob = $_POST['dob'];
	$address = $_POST['address'];
	$gender = $_POST['gender'] ?? '';

	//Hash Password
	$hash_password = password_hash($password, PASSWORD_DEFAULT);

	// check empty fields  
	if (
		empty($username) || empty($email) ||
		empty($password) || empty($mobile) ||
		empty($dob) || empty($address) ||
		empty($gender)
	) {
		$errors[] = "Please Fill Required Fiels!!";
		sweetAlert("Required!", "Please Fill Required Fields!!", "warning");
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		//email validation
		$errors[] = "Invalid Email Format!!";
		// sweetAlert("Warning","Invalid Email Format!","warning");
	}
	// if (strlen($mobile) != 10) {
	// 	//validate mobile no
	// 	$errors[] =  "Mobile Number Must be 10 Digits !!";
	// 	// sweetAlert("Warning","Mobile No. Must be 10 Digits !!","warning");
	// }

	if (strlen($password) < 6) {
		$errors[] = "Password Must be Greater than 6 character!!";
		// sweetAlert("Warning","Password Must be Greater than 6 character!!","warning");
	}
	if ($password !== $confirmPassword) {
		$errors[] = "Password is not Match with Confirm-Password!";
		// sweetAlert("Warning","Password is not Match with Confirm-Password!","warning");

	}
	if (!empty($errors)) {
		sweetAlert("Error!", "Please Try Again!", "error");
	} else {
		$sql = "INSERT INTO `ep_users`( `name`, `email`,`password`, `mobile`, `dob`, `address`, `gender`, `image`, `role`) VALUES (:username,:email,:password,:mobile,:dob,:address,:gender,:photo,:role)";
		$result = $conn->prepare($sql);
		$data = $result->execute([
			'username' => $username,
			'email' => $email,
			'password' => $hash_password,
			'mobile' => $mobile,
			'dob' => $dob,
			'address' => $address,
			'gender' => $gender,
			'photo' => "user.jpg",
			'role' => 'customer'
		]);
		if ($data) {
			$success = [];
			$success[] = "Registered Successfully! Please Login Now!!!";
			sweetAlert("Registered Successfully", "Please Check your your Mail.", "success");
			$_SESSION['customer'] = $username;
			$_POST = []; //Clear all textboxes

			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->Host = "smtp.gmail.com";
				$mail->SMTPAuth = true;
				$mail->Username = "pvidhi782@gmail.com";
				$mail->Password = "ibcialcohyfmmvll";
				$mail->SMTPSecure = "tls";
				$mail->Port = 587;

				$message = "Hello Sir/Madam,

Welcome to ePharmaEase! 🌟

Thank you for registering on our website. We’re really happy to have you with us and hope you enjoy a smooth, safe, and convenient experience while using our services.

Your account has been successfully created. Below are your login details for future reference:

Username: $username
Password: $confirmPassword

Please keep these details safe and do not share them with anyone.

If you need any help or have questions, feel free to contact us anytime—we’re always here to support you.

Once again, welcome aboard. We’re glad you’re here!

Warm regards,
Administrator
Team ePharmaEase

";
				$mail->setFrom("pvidhi782@gmail.com", "ePharmaEase");
				$mail->addAddress($email);
				$mail->Subject = "Welcome! We are glad to have you with us!";
				$mail->Body = $message;
				$mail->send();
			} catch (Exception $e) {
				echo $e;
			}
		} else {
			// $message['WARNING'] = "Try Again! Registration Fail!";
			sweetAlert("Warning", "Try Again! Registration Fail!", "warning");
		}
	}
}
?>

<!--================ Start Header Menu Area =================-->
<?php
$page_title = "ePharmaEase - Register";
require_once('header.php') ?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
	<div class="container h-100">
		<div class="blog-banner">
			<div class="text-center">
				<h1>Register</h1>
				<nav aria-label="breadcrumb" class="banner-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Register</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</section>
<!-- ================ end banner area ================= -->

<!--================Login Box Area =================-->

<section class="login_box_area section-margin">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="login_box_img">
					<div class="hover">
						<h4>Already have an account?</h4>
						<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
						<a class="button button-account" href="login.php">Login Now</a>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="login_form_inner register_form_inner">
				<?php if (!empty($errors)) { ?>
					<div class="alert alert-danger" style="margin: 40px;">
						<ul style="list-style: disc;margin: 10px;">
						<?php foreach ($errors as $err) { ?>
								<li><?= $err ?></li>
						<?php } ?>
					</ul>
				</div>
				<?php } else if (!empty($success)) { ?>
					<div class="alert alert-success" style="margin: 40px;"><ul>
						<?php foreach ($success as $suc) { ?>
							
								<li><?= $suc ?></li>
							
						<?php }  ?>
					</ul></div>
				<?php 
				} 
					if(!empty($errors)){
						$class = "border-bottom: 1px solid red";
					}
				?>
					
					<h3>Create an account</h3>
					<form class="row login_form" id="register_form" method="post">
						<div class="col-md-12 form-group">
			
							<input type="text" class="form-control" style="<?= $class ?>" id="name" name="name" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
						</div>
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" style="<?= $class ?>" id="email" name="email" placeholder="Email Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address'" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
						</div>
						<div class="col-md-12 form-group">
							<input type="password" class="form-control" style="<?= $class ?>" id="password" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '' ?>">
						</div>
						<div class="col-md-12 form-group">
							<input type="password" class="form-control" style="<?= $class ?>" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm Password'" value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '' ?>">
						</div>
						<div class="col-lg-12 form-group">
							<input id="phone" type="tel" name="phone" style="width: 353px;<?= $class ?>" class="form-control" placeholder="Mobile No." placeholder="Mobile No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mobile No.'" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
							<!-- <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Mobile No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mobile No.'" value="<?= isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : '' ?>"> -->
						</div>
						<div class="col-md-12 form-group">
							<input type="date" class="form-control" style="<?= $class ?>" id="dob" name="dob" placeholder="Birth Date" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Birth Date'" value="<?= isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : '' ?>">
						</div>
						<div class="col-md-12 form-group">
							<textarea class="form-control" name="address" style="<?= $class ?>" id="address" placeholder="Address" cols="30" rows="4" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>
						</div>
						<!-- <div class="col-md-12 form-group">
								<input type="radio" class="form-check" id="gender_male" name="gender">Male
								<input type="radio" class="form-check" id="gender_female" name="gender">Female
							</div> -->
						<div class=" col-lg-4 text-muted" style="display: flex;flex-direction: column;gap: 8px;">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="gender" id="male" value="Male">

								<label class="form-check-label" for="male" style="display: flex;align-items: center;font-size: 16px;gap: 20px;padding-left: 40px;">
									Male
								</label>
							</div>
							<div class="form-check ">
								<input class="form-check-input" type="radio" name="gender" id="female" value="Female">

								<label class="form-check-label" for="female" style="display: flex;align-items: center;font-size: 16px;gap: 20px;padding-left: 40px;">
									Female
								</label>
							</div>
						</div>

						<div class="col-md-12 form-group">
							<button type="submit" name="submit" value="submit" class="button button-register w-100">Register</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<!--================End Login Box Area =================-->



<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>
<!--================ End footer Area  =================-->