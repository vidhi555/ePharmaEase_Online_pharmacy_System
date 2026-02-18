<?php
require_once("connection/db.php");
$errors = [];

if (isset($_POST['contact'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    $errors[] = "All Fields are Required!!!";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid Email Format!";
  }

  if (!empty($errors)) {
    sweetAlert("Error!", "Please Try Again!", "error");
  } else {
    $result = $conn->prepare("INSERT INTO `ep_message`(`msg_id`, `name`, `email`, `subject`, `message`) VALUES (null,'$name','$email','$subject','$message')");
    $q = $result->execute();
    if ($q) {
      sweetAlert("Message Sent Successfully!", "", "success");
    } else {
      sweetAlert("Warning", "Something went Wrong!Please Try Again!", "warning");
    }
  }
}

?>
<!--================ Start Header Menu Area =================-->

<?php
$page_title = "ePharmaEase - Contact page";
require_once('header.php') ?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="contact">
  <div class="container h-100">
    <div class="blog-banner">
      <div class="text-center">
        <h1>Contact Us</h1>
        <nav aria-label="breadcrumb" class="banner-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>
<!-- ================ end banner area ================= -->

<!-- ================ contact section start ================= -->
<section class="section-margin--small">
  <div class="container">

    <?php if (!empty($errors)) { ?>
      <div class="alert alert-danger">
        <ul style="list-style: disc;padding-left: 5px;">
          <?php foreach ($errors as $er) { ?>
            <li><?= $er ?></li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>

    <div class="row" style="background: aliceblue;padding: 35px;border-radius: 10px;font-size: medium;font-family: sans-serif;text-transform: capitalize;box-shadow: 0 0 5px #333;">
      <div class="col-md-4 col-lg-3 mb-4 mb-md-0" style="border-right: 1px solid darkgray;line-height: 50px;">
        <div class="media contact-info">
          <span class="contact-info__icon"><i class="ti-home"></i></span>
          <div class="media-body">
            <h3>E-Pharma Solutions Pvt. Ltd.</h3>
            <p>Sunrise Tech Park,
              MG Road, Andheri East,
              Mumbai – 400069, India </p>
            <hr>
          </div>
        </div>
        <div class="media contact-info">
          <span class="contact-info__icon"><i class="ti-mobile"></i></span>
          <div class="media-body">
            <h3><a href="tel:9876543210">+91 98765 43210</a></h3>
            <p>Mon - Fri: 9am to 6pm <br>
              sat - sun: 10am to 2pm</p>
            <hr>
          </div>
        </div>
        <div class="media contact-info">
          <span class="contact-info__icon"><i class="ti-email"></i></span>
          <div class="media-body">
            <h3><a href="mailto:support@colorlib.com">support@epharma.com</a></h3>
            <p>Send us your query anytime!</p>
          </div>
        </div>
      </div>
      <div class="col-md-8 col-lg-9 mb-4 mb-md-0">
        <!-- <div class=""> -->
        <?php if (!empty($msg['SUCCESS'])) { ?>
          <div class="alert alert-primary">
            <p><?= $msg['SUCCESS'] ?></p>
          </div>
        <?php } ?>
        <form action="" class="form-contact contact_form" method="post">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group text-center">
                <img src="img/logo6.png" alt="logo" style="margin-bottom: -42px;width: 100px;height: 80px;">
                <h2 style="color: #1565c0;">We’re Here to Help</h2>
                <p class="text-muted">Get in touch with us for any questions, support, or feedback.</p>

              </div>
              <div class="form-group">
                <input class="form-control" name="name" id="name" type="text" placeholder="Enter Your Name">
              </div>
              <div class="form-group">
                <input class="form-control" name="email" id="email" type="email" placeholder="Enter E-Mail Address">
              </div>
              <div class="form-group">
                <input class="form-control" name="subject" id="subject" type="text" placeholder="Enter Subject">
              </div>


              <div class="form-group">
                <textarea class="form-control different-control w-100" name="message" id="message" cols="30" rows="5" placeholder="Enter Message"></textarea>
              </div>


              <div class="form-group text-center text-md-right mt-3">
                <button type="submit" name="contact" class="button button--active button-contactForm">Send Message</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- ================ contact section end ================= -->



<!--================ Start footer Area  =================-->
<?php require_once('footer.php'); ?>

<!--================ End footer Area  =================-->