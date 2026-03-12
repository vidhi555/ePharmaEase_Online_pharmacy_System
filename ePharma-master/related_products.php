<!-- ================ recommanded products ================= -->
<section class="section-margin calc-60px" style="background: linear-gradient(135deg, #c9f0f6, #d2e9f9);margin-top:10px;">
	<div class="container">
		<div class="section-intro pb-60px text-center">
			<img data-aos="fade-up" src="img/logo6.png" alt="logo" style="width: 100px;height: 80px;">
			<p>Recommended Health Product</p>
			<h2 style="margin-top: 5px;"><span class="section-intro__style">Explore Similar Products</span></h2>
		</div>
		<div class="owl-carousel owl-theme" id="bestSellerCarousel">
			<?php
			try {
				$query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE p_id = $pid");
				$query->execute();
				$product = $query->fetch(PDO::FETCH_ASSOC);
				if ($product) {
					$fetchcid = $product['c_id'];

					$query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON p.c_id = c.c_id WHERE c.c_id = :cid ORDER BY price DESC LIMIT 8");
					$query->execute(['cid' => $fetchcid]);

					$result = $query->fetchAll(PDO::FETCH_ASSOC);
					foreach ($result as $p) {
						// rate average
						$rate_avg = $conn->prepare("SELECT AVG(rate) as rating_avg FROM ep_review WHERE p_id = :pid");
						$rate_avg->execute(['pid' => $p['p_id']]);
						$fetch_avg = $rate_avg->fetch(PDO::FETCH_ASSOC);

						$available_stock = $p['stock'];
			?>
						<div class="card text-center card-product">
							<form action="" method="post">
								<div class="card-product__img">
									<h6 class="card-product__price">$<?= $p['price'] ?></h6>
									<a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img class="card-img" src="../LearnAdmin/All_images_uploads/<?= $p['image'] ?>" alt=""></a>
									<input type="hidden" name="product_id" value="<?= $p['p_id'] ?>">
									<input type="hidden" name="cart_id" value="<?= $p['p_id'] ?>">
									<ul class="card-product__imgOverlay">
										<?php if ($available_stock < 5) {
											$alert = "<i class='fas fa-exclamation-triangle'></i> Only few items left";
										} elseif ($available_stock <= 0) {
											$alert = "<i class='far fa-exclamation-triangle'></i> Out Of Stock";
										} else {
											$alert = "<i class='ti-shopping-cart'></i>";
										} ?>
										<li><button name="cart"><?= $alert ?></button></li>
									</ul>
								</div>

								<div class="card-body">
									<h4 class="card-product__title"><a href="single-product.php?p_id=<?= $p['p_id'] ?>"><?= $p['name'] ?></a></h4>

									<h6 class="rating"><?php
														for ($i = 0; $i < $fetch_avg['rating_avg']; $i++) {
															echo "<i class='fas fa-star rating-stars text-size-13'></i>";
														}

														?>
									</h6>
									<p style="text-transform: capitalize;"><?= $p['category_name'] ?></p>
								</div>
							</form>
						</div>

			<?php
					}
				} else {
					echo "No Products Available!";
				}
			} catch (PDOException $ex) {
				echo "Error: $ex";
			}
			?>



		</div>
</section>
<!-- ================ recommanded products ================= -->