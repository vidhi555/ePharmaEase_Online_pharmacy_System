<footer>
	<div class="footer-area footer-only">
		<div class="container">
			<div class="row section_gap">
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="single-footer-widget tp_widgets ">
						<h4 class="footer_title large_title">Our Mission</h4>
						<p>
							At ePharmaEase, our mission is to combine technology and healthcare to deliver a seamless pharmacy experience.<br> We focus on quality, convenience, and trust to support healthier lives.
						</p>

					</div>
				</div>
				<div class="offset-lg-1 col-lg-2 col-md-6 col-sm-6">
					<div class="single-footer-widget tp_widgets">
						<h4 class="footer_title">Quick Links</h4>
						<ul class="list">
							<li><a href="index.php">Home</a></li>
							<li><a href="category.php">Shop</a></li>
							<li><a href="category.php">Product</a></li>
							<!-- <li><a href="#">Brand</a></li> -->
							<li><a href="contact.php">Contact</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2 col-md-6 col-sm-6">
					<div class="single-footer-widget instafeed">
						<h4 class="footer_title">Instagram</h4>
						<ul class="list instafeed d-flex flex-wrap">
							<li><img src="img/gallery/p1.jpg" alt=""></li>
							<li><img src="img/gallery/p2.jpg" alt=""></li>
							<li><img src="img/gallery/p3.jpg" alt=""></li>
							<li><img src="img/gallery/p5.jpg" alt=""></li>
							<li><img src="img/gallery/p6.jpg" alt=""></li>
							<li><img src="img/gallery/p7.jpg" alt=""></li>
						</ul>
					</div>
				</div>
				<div class="offset-lg-1 col-lg-3 col-md-6 col-sm-6">
					<div class="single-footer-widget tp_widgets">
						<h4 class="footer_title">Contact Us</h4>
						<div class="ml-40">
							<p class="sm-head">
								<span class="fa fa-location-arrow"></span>
								Head Office
							</p>
							<p>237, Bhimpore, Surat</p>

							<p class="sm-head">
								<span class="fa fa-phone"></span>
								Phone Number
							</p>
							<p>
								+91 95178 85236 <br>
								+91 14785 25896
							</p>

							<p class="sm-head">
								<span class="fa fa-envelope"></span>
								Email
							</p>
							<p>
								pharma@infoexample.com <br>
								www.epharmaease.com
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="container">
			<div class="row d-flex">
				<p class="col-lg-12 footer-text text-center">
					Copyright &copy; <script>
						document.write(new Date().getFullYear());
					</script> ePharmaEase. All Rights Reserved. Designed & Developed by ePharmaEase Team.<i class="fa fa-heart" aria-hidden="true"></i>

				</p>
			</div>
		</div>
	</div>
</footer>


<?php require_once('sweetAlert.php'); 
?>
<script src="vendors/jquery/jquery-3.2.1.min.js"></script>
<script src="vendors/bootstrap/bootstrap.bundle.min.js"></script>
<script src="vendors/skrollr.min.js"></script>
<script src="vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="vendors/nice-select/jquery.nice-select.min.js"></script>
<script src="vendors/nouislider/nouislider.min.js"></script>
<script src="vendors/jquery.ajaxchimp.min.js"></script>
<script src="vendors/mail-script.js"></script>
<script src="js/main.js"></script>
<script src="new_css/js/bootstrap.min.js"></script>

<!-- Phone number JS Library -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>

<!-- this used for mobile numbers validations -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"></script> 
<script>
document.addEventListener("DOMContentLoaded", function () {

    var input = document.querySelector("#phone");

    var iti = window.intlTelInput(input, {

        initialCountry: "auto",   // it will Auto detect country
        geoIpLookup: function(callback) {
            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("in")); // fallback India
        },

        // onlyCountries: ["in", "us","uk"],  // Restrict to India + USA
        separateDialCode: false,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"
    });

    // Validate + Store Full Number
   document.querySelector("#register_form").addEventListener("submit", function(e) {

    // Check empty
    if (input.value.trim() === "") {
        alert("Phone number is required");
        e.preventDefault();
        return;
    }

    // Validate number length according to country
    if (!iti.isValidNumber()) {
        alert("Please enter a valid phone number according to selected country");
        e.preventDefault();
        return;
    }

    // Store full international number
    input.value = iti.getNumber();
});


});
</script>

<script>
  $(document).ready(function() {

    function load_products(page = 1) {

      let category_id = $('input[name="category"]:checked').val();
      let search = $('#live_search').val();
      let sort = $('#filter_by_status').val();
      let max_price = $('#priceRange').val();

      $.ajax({
        url: "load_products_ajax.php",
        method: "POST",
        data: {
          page: page,
          category_id: category_id,
          search: search,
          sort: sort,
          max_price: max_price
        },
        success: function(data) {
          $("#product-list").html(data);
        }
      });
    }

    // Category change
    $(document).on("change", 'input[name="category"]', function() {
      load_products();
    });

    // Search
    $(document).on("keyup", "#live_search", function() {
      load_products();
    });

    // Sort
    $(document).on("change", "#filter_by_status", function() {
      load_products();
    });

    // Price filter
    $(".filter-btn").on("click", function() {
      load_products();
    });

    // Pagination click
    $(document).on("click", ".page-link", function(e) {
      e.preventDefault();
      let page = $(this).data("page");
      load_products(page);
    });

  });
</script>
</body>

</html>