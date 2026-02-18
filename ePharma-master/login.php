<?php
require_once("connection/db.php");
$errors = [];
$success = [];
$gid = session_id();
// echo $gid;
// die();
// $message = ["WARNING" => '', 'SUCCESS' => ''];
if (isset($_POST['login'])) {
	//collect input
	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) || empty($password)) {
		$errors[] = "Username & Password Required!";
		// sweetAlert("Required!", "Username & Password Required!!", "warning");
	}

	$query = "SELECT * FROM ep_users WHERE name = :username AND role = 'customer' LIMIT 1";
	$result = $conn->prepare($query);
	$result->execute(['username' => $username]);

	$fetch_one = $result->fetch(PDO::FETCH_ASSOC);
	if ($fetch_one > 0) {
		if (password_verify($password, $fetch_one['password'])) {
			if (isset($_POST['remember'])) {
				//cookie store name & password
				setcookie("name", $username, time() + 86400);
				setcookie("password", $password, time() + 86400);
			}
			$_SESSION['user_id'] = $fetch_one['u_id'];
			$usertemp = $_SESSION['user_id'];

			// Update cart from guest user to logged in user
			if ($gid) {
				$update_cart = $conn->prepare("UPDATE ep_cart SET u_id = :uid , guest_id = NULL WHERE guest_id = :gid");
				$update_cart->execute([
					'uid' => $usertemp,
					'gid' => $gid
				]);
				// $success[] = "Your Cart is updated Successfully!";
			}

			sweetAlert("Success", "Login Successfull!", "success");
			$success[] = "Login Successfull👍";

			// header("Location:index.php");
		} else {
			// sweetAlert("Warning!", "Invalid Password!!", "warning");
			$errors[] = "Invalid Password!!";
		}
	} else {
		$errors[] = "Invalid Username!!";
		sweetAlert("Warning!", "Invalid Username!!", "warning");
	}
}


?>

<!--================ Start Header Menu Area =================-->

<?php
$page_title = "ePharmaEase - Login";
require_once('header.php') ?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
	<div class="container h-100">
		<div class="blog-banner">
			<div class="text-center">
				<h1>Login</h1>
				<nav aria-label="breadcrumb" class="banner-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Login/Register</li>
						<li class="breadcrumb-item"><a href="logout.php">Log-Out</a></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</section>
<!-- ================ end banner area ================= -->

<!--================Login Box Area =================-->
<!-- <section "> -->
<section class="login_box_area section-margin">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="login_box_img">
					<div class="hover">
						<h4>New to our website?</h4>
						<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
						<a class="button button-account" href="register.php">Create an Account</a>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="login_form_inner">
					<?php if (!empty($errors)) { ?>
						<div class="alert alert-danger" style="margin: 40px;">
							<?php foreach ($errors as $err) { ?>
								<ul>
									<li><?= $err ?></li>
								</ul>
							<?php } ?>
						</div>
					<?php } else if (!empty($success)) { ?>
						<div class="alert alert-success" style="margin: 40px;">
							<?php foreach ($success as $suc) { ?>
								<ul>
									<li><?= $suc ?></li>
								</ul>
							<?php }  ?>
						</div>
					<?php } ?>

					<h3>Log in to enter</h3>
					<form class="row login_form" action="" id="contactForm" method="post">
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" id="username" name="username" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">
						</div>
						<div class="col-md-12 form-group">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
						</div>
						<div class="col-md-12 form-group text-muted">
							<div class="creat_account">
								<input type="checkbox" id="f-option2" name="remember">
								<label for="f-option2">Remember Me</label>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<button type="submit" value="submit" name="login" class="button button-login w-100">Log In</button>
							<a href="forgot_password.php">Forgot Password?</a>
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
<!--================ End footer Area  =================-->