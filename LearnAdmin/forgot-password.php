<?php
require_once("db.php");
//For sending mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("PHPMailer/src/Exception.php");
require_once("PHPMailer/src/SMTP.php");
require_once("PHPMailer/src/PHPMailer.php");

//forgot password
if (isset($_POST['forgot'])) {
    $v_email = $_POST['email'];

    $verify_email = $conn->prepare("SELECT * FROM ep_users WHERE email = :em AND role = 'admin'");
    $verify_email->execute([
        'em' => $v_email
    ]);
    $fetch_record = $verify_email->fetch(PDO::FETCH_ASSOC);

    if (!$fetch_record) {
        sweetAlert("Email Not Found!", "", "warning");
    } else {
        $mail = new PHPMailer(true);
        try {
            //mail is found


            $mail->isSMTP();    //enables smtp mode
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;     //set authentication true
            $mail->Username = "pvidhi782@gmail.com";
            $mail->Password = "ibcialcohyfmmvll"; //Must be app password
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom("pvidhi782@gmail.com", "ePharmaEase");
            $mail->addAddress($v_email);

            //Generate token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime("+30 minutes"));

            //update token & expiry time of admin
            $update_user = $conn->prepare("UPDATE ep_users SET token=:token ,expity_token = :expiry_time WHERE email = :v_email");
            $update_user->execute([
                'token' => $token,
                'expiry_time' => $expiry,
                'v_email' => $v_email
            ]);

            $reset_link = "http://localhost/ePharmaEase_Project/LearnAdmin/reset_password.php?token=$token";

            $message = "Hello Administrator,

We received a request to reset the password for your ePharmaEase account.

Please click the link below to reset your password:
{{$reset_link}}

This link is valid for 30 minutes only.
If you did not request a password reset, please ignore this email.

For security reasons, do not share this link with anyone.
Regards,
ePharmaEase Team
Your trusted online pharmacy";

            $mail->Subject = "Reset Your ePharmaEase Account Password";
            $mail->Body = $message;

            $mail->send();

            sweetAlert("Mail sent Successfully!", "Please Check Your Gmail!", "success");
        } catch (Exception $e) {
            echo $e;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    <!-- Stylesheets -->
    <link rel="shortcut icon" href="./assets/images/logo6.ico" type="image/x-icon">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/icons/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="./assets/icons/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="./assets/icons/fontawesome/css/solid.min.css" rel="stylesheet">
    <link href="./assets/plugin/quill/quill.snow.css" rel="stylesheet">
    <link href="./assets/css/style4.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4" style="border-radius: 20px;background: aliceblue;padding: 30px;box-shadow: 0 8px 25px #303030;">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="login.php"><img src="assets/images/logo9.png" style="width: 345px;" alt="logo"></a>
                        </div>
                    </div>

                    <!-- Forgot Password Form -->
                    <h2 class="mb-4 text-dark h4">Forgot Password</h2>
                    <form method="post">
                        <!-- Email Input -->
                        <div class="mb-3 position-relative">
                            <label for="email" class="form-label text-muted small">Email</label>
                            <div class="position-relative">
                                <input type="email" class="form-control form-control-lg rounded-3"
                                    id="email" name="email" placeholder="example@gmail.com">
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                        </div>

                        <!-- Forgot Password Button -->
                        <button type="submit" name="forgot" class="btn btn-signin btn-lg w-100 rounded-3 mb-4">
                            Verify Email
                        </button>

                        <!-- Divider -->
                        <div class="text-center text-muted mb-4 text-size-14">
                            You have an account? <a href="login.php" class="text-primary">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/js/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/plugin/chart/chart.js"></script>
    <script src="./assets/plugin/quill/quill.js"></script>
    <script src="./assets/js/chart.js"></script>
    <script src="./assets/js/main.js"></script>
    <?php require_once("sweetAlert.php"); ?>
</body>

</html>