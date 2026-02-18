<?php
require_once("./connection/db.php");
$page_title = "ePharmaEase - Track Order";
require_once("header.php");
?>
<!--================ Start Header Menu Area =================-->

<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
	<div class="container h-100">
		<div class="blog-banner">
			<div class="text-center">
				<h1>Order Tracking</h1>
				<nav aria-label="breadcrumb" class="banner-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Order Tracking</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</section>
<!-- ================ end banner area ================= -->


<!--================Tracking Box Area =================-->
<section class="tracking_box_area section-margin--small">
	<div class="container">
		<div class="tracking_box_inner">
			<p>To track your order please enter your Order ID in the box below and press the "Track" button. This
				was given to you on your receipt and in the confirmation email you should have received.</p>
			<form class="row tracking_form" method="post" novalidate="novalidate">
				<div class="col-md-12 form-group">
					<input type="text" class="form-control" id="order" name="order_id" placeholder="Order ID"
						onfocus="this.placeholder = ''" onblur="this.placeholder = 'Order ID'">
				</div>
				<div class="col-md-12 form-group">
					<input type="email" class="form-control" id="email" name="email"
						placeholder="Billing Email Address" onfocus="this.placeholder = ''"
						onblur="this.placeholder = 'Billing Email Address'">
				</div>
				<div class="col-md-12 form-group">
					<button name="track_order" type="submit" value="submit" class="button button-tracking">Track Order</button>
				</div>
			</form>
		</div>
	</div>
</section>
<!--================End Tracking Box Area =================-->
<?php
try {
	if (isset($_POST['track_order'])) {
		$oid = $_POST['order_id'];
		$email = $_POST['email'];

		$query = $conn->prepare("SELECT * FROM ep_orders_master WHERE o_id = :oid AND email = :em");
		$query->execute([
			'oid' => $oid,
			'em' => $email
		]);
		if (empty($oid) || empty($email)) {
			sweetAlert("Warning", "Order ID & Billing Email is Required!", "warning");
		} else {
			$fetch_orders = $query->fetchAll(PDO::FETCH_ASSOC);
			if ($fetch_orders) {
				foreach ($fetch_orders as $o) {
?>
					<!-- ===============Show Tracking Orders================= -->
					<section class="tracking_box_area section-margin--small">
						<div class="container">
							<!-- write order tracking code -->
							<div class="order-track-card fade-up">
								<div class="order-track-header">
									<h3>📦 Order Status</h3>
									<span class="order-id">Order ID: <strong><?= $o['o_id'] ?></strong></span>
								</div>

								<!-- STATUS STEPPER -->
								<!-- <div class="order-steps">
				<div class="step completed">
					<span class="circle">✔</span>
					<p>Order Placed</p>
				</div>
				<div class="step completed">
					<span class="circle">✔</span>
					<p>Packed</p>
				</div>
				<div class="step active">
					<span class="circle">🚚</span>
					<p>Shipped</p>
				</div>
				<div class="step">
					<span class="circle">⏳</span>
					<p>Delivered</p>
				</div>
			</div> -->
								<div class="order-steps">
									<?php
									try {
										$order_process = ['Placed', 'confirmed', 'shipped', 'delivered'];
										$start = $o['order_status'];

										$current_index = array_search($start, $order_process);
									} catch (PDOException $e) {
										echo "$e";
									}
									?>
									<div class="step <?= $current_index >= 0 ? 'completed' : '' ?>">
										<span class="circle">✔</span>
										<p>Order Placed</p>
									</div>
									<div class="step <?= $current_index >= 1  ? 'completed' : '' ?>">
										<span class="circle">✔</span>
										<p>Packed</p>
									</div>
									<div class="step <?= $current_index >= 2  ? 'completed' : '' ?>">
										<span class="circle">🚚</span>
										<p>Shipped</p>
									</div>
									<div class="step <?= $current_index >= 3  ? 'completed' : '' ?>">
										<span class="circle">⏳</span>
										<p>Delivered</p>
									</div>
								</div>

								<!-- ORDER DETAILS -->
								<div class="order-details">
									<div class="detail-box">
										<h5>Customer</h5>
										<p><?= $o['fname'] . " " . $o['lname'] ?></p>
									</div>
									<div class="detail-box">
										<h5>Email</h5>
										<p><?= $o['email'] ?></p>
									</div>
									<div class="detail-box">
										<h5>Total Amount</h5>
										<p>₹<?= $o['total_amount'] ?></p>
									</div>
									<div class="detail-box">
										<h5>Expected Delivery</h5>
										<p><?= date('d/m/Y', strtotime("+3 days")) ?></p>
									</div>
								</div>
							</div>
						</div>
					</section>

					<!-- ===============Show Tracking Orders================= -->

<?php
				}
			} else {
				echo "<div class='alert alert-warning text-center mt-4'>
    <strong>Order not found!</strong><br>
    Please check your Order ID and Billing Email.
</div>
";
			}
		}
	}
} catch (PDOException $e) {
	echo $e;
}
?>



<!--================ Start footer Area  =================-->
<?php require_once('footer.php') ?>
<!--================ End footer Area  =================-->