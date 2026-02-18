<section class="related-product-area section-margin--small mt-0">
	<div class="container">
		<div class="section-intro pb-60px">
			<p>Popular Item in the market</p>
			<h2>Related <span class="section-intro__style">Product</span></h2>
		</div>
		<div class="row ">

			<?php
			try {
				$query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE p_id = $pid");
				$query->execute();
				$product = $query->fetch(PDO::FETCH_ASSOC);
				if ($product) {
					$fetchcid = $product['c_id'];

					$query = $conn->prepare("SELECT * FROM ep_products WHERE c_id = :cid LIMIT 8");
					$query->execute(['cid' => $fetchcid]);

					$fetch_products = $query->fetchAll(PDO::FETCH_ASSOC);
					foreach ($fetch_products as $p) { ?>
						<div class="col-md-6 col-lg-3 col-xl-3 mt-3">
							<div class="single-search-product-wrapper">
								<div class="single-search-product d-flex">
									<a href="single-product.php?p_id=<?= $p['p_id'] ?>"><img src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
									<div class="desc">
										<h6 class="rating"><?php
															for ($i = 0; $i < 5; $i++) {
																echo "<i class='fas fa-star rating-stars text-size-13'></i>";
															}

															?>
										</h6>
										<a href="#" class="title"><?= $p['name'] ?></a>
										<div class="price">Rs.<?= $p['price'] ?></div>
									</div>
								</div>
							</div>
						</div>
			<?php	}
				}
			} catch (PDOException $e) {
				echo "Error:$e";
			}
			?>


			<!-- <div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
				<div class="single-search-product-wrapper">
					<?php
					// try {
					// 	$query = $conn->prepare("SELECT * FROM ep_products WHERE c_id=2");
					// 	$query->execute();
					// 	$fetch_products = $query->fetchAll(PDO::FETCH_ASSOC);
					// 	foreach ($fetch_products as $p) { 
					?>
							<div class="single-search-product d-flex">
								<a href="#"><img src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
								<div class="desc">
									<a href="#" class="title"><?= $p['name'] ?></a>
									<div class="price">Rs.<?= $p['price'] ?></div>
								</div>
							</div>
					<?php
					// }
					// } catch (PDOException $e) {
					// 	echo "Error:$e";
					// }
					?>
				</div>
			</div>

			<div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
				<div class="single-search-product-wrapper">
					<?php
					// try {
					// 	$query = $conn->prepare("SELECT * FROM ep_products WHERE c_id = 3");
					// 	$query->execute();
					// 	$fetch_products = $query->fetchAll(PDO::FETCH_ASSOC);
					// 	foreach ($fetch_products as $p) { 
					?>
							<div class="single-search-product d-flex">
								<a href="#"><img src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
								<div class="desc">
									<a href="#" class="title"><?= $p['name'] ?></a>
									<div class="price">Rs.<?= $p['price'] ?></div>
								</div>
							</div>
					<?php
					// 	}
					// } catch (PDOException $e) {
					// 	echo "Error:$e";
					// }
					?>
				</div>
			</div>

			<div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
				<div class="single-search-product-wrapper">
					<?php
					// try {
					// 	$query = $conn->prepare("SELECT * FROM ep_products WHERE c_id = 4");
					// 	$query->execute();
					// 	$fetch_products = $query->fetchAll(PDO::FETCH_ASSOC);
					// 	foreach ($fetch_products as $p) { 
					?>
							<div class="single-search-product d-flex">
								<a href="#"><img src="../LearnAdmin/upload/<?= $p['image'] ?>" alt=""></a>
								<div class="desc">
									<a href="#" class="title"><?= $p['name'] ?></a>
									<div class="price">Rs.<?= $p['price'] ?></div>
								</div>
							</div>
					<?php
					// 	}
					// } catch (PDOException $e) {
					// 	echo "Error:$e";
					// }
					?>
				</div>
			</div> -->
		</div>
	</div>
</section>