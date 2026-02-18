<?php
require_once("connection/db.php");
require_once('insert_cart_logic.php');
//title
$page_title = "ePharmaEase - About Us";
require_once('header.php');

?>

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>About Us</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">About-us</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ================ end banner area ================= -->

<!--================about us Area =================-->
<section class="section-margin about-section">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 mb-4 mb-lg-0 slide-right">
                <div class="about_image">
                    <img class="img-fluid rounded" src="img/home/enhance_aakw5bno4hln8a2w2g3e.png" alt="About ePharmaEase">
                </div>
            </div>

            <div class="col-lg-6 slide-left">
                <div class="about_contents">
                    <h1>Welcome to <span>ePharmaEase</span></h1>

                    <p>
                        At <strong>ePharmaEase</strong>, we believe healthcare should be simple, reliable,
                        and accessible for everyone. Our platform connects you with genuine medicines,
                        trusted brands, and essential healthcare products — all delivered right to your doorstep.
                    </p>

                    <p>
                        We focus on quality, safety, and convenience. Every product is carefully sourced,
                        securely packed, and delivered on time, ensuring peace of mind for you and your family.
                    </p>

                    <p>
                        Whether you need daily medicines or wellness essentials, ePharmaEase is here to
                        make your healthcare journey easier, faster, and stress-free.
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>

<!--================End about us Area =================-->
<section class="section-margin testimonial-section">
    <div class="container">

        <div class="testimonial-wrapper">

            <!-- LEFT CONTENT -->
            <div class="testimonial-left">
                <h2>Our Happy Customers</h2>
                <p>
                    Our happy customers are a reflection of our commitment to quality and care. At ePharmaEase, we ensure reliable service, genuine medicines, and timely delivery, making healthcare more accessible and convenient for everyone.
                </p>
            </div>

            <!-- RIGHT CAROUSEL -->
            <div class="testimonial-carousel">

                <?php
                try {
                    $rev = $conn->prepare("SELECT * FROM ep_review r JOIN ep_users u ON r.u_id = u.u_id");
                    $rev->execute();
                    $fetch_rev = $rev->fetchAll(PDO::FETCH_ASSOC);
                    $count = 0;
                    foreach ($fetch_rev as $r) {
                        $activeClass = ($count === 0) ? 'active' : '';
                ?>
                        <div class="testimonial-slide <?= $activeClass ?>">
                            <span class="quote">“</span>
                            <p><?= $r['description'] ?></p>

                            <div class="stars">
                                <?php
                                for ($i = 1; $i <= $r['rate']; $i++) {
                                    echo "<i class='fas fa-star rating-stars'></i>";
                                }
                                ?>
                            </div>

                            <div class="author">
                                <h5><?= $r['name'] ?></h5>
                                <img src="uploads/<?= $r['image'] ?>" alt="use_img" class="review_img">
                            </div>
                        </div>
                <?php
                        $count++;
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
                ?>
                <!-- Slider Buttons -->
                <button class="slider-btn prev">&#10094;</button>
                <button class="slider-btn next">&#10095;</button>
            </div>

        </div>
    </div>
</section>
<script>
    const slides = document.querySelectorAll('.testimonial-slide');
    const prevBtn = document.querySelector('.prev');    //get first element
    const nextBtn = document.querySelector('.next');
    let index = 0;
    let total_slide = slides.length;
    // alert(total_slide);

    function showSlide(i) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[i].classList.add('active');
    }

    nextBtn.addEventListener('click', () => {
        index = (index + 1) % total_slide;   
        showSlide(index);
    });

    prevBtn.addEventListener('click', () => {
        index = (index - 1 + total_slide) % total_slide;
        showSlide(index);
    });

    // Auto slide
    setInterval(() => {
        index = (index + 1) % total_slide;  //next slide show after 4 sec
        showSlide(index);
    }, 4000);
    
</script>



<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>
<?php require_once('sweetAlert.php'); ?>
<!--================ End footer Area  =================-->


<style>
    /* Slider Buttons */
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        color: #1557b0;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .slider-btn.prev {
        left: -20px;
    }

    .slider-btn.next {
        right: -20px;
    }

    .slider-btn:hover {
        background: #0d6efd;
        color: #fff;
    }
</style>