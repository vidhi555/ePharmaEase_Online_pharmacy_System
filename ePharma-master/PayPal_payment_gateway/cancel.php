<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cancel payment</title>
    <!-- Stylesheets -->
    <link rel="icon" href="../img/logo6.ico" type="image/x-icon">
    <link rel="stylesheet" href="../vendors/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../vendors/nice-select/nice-select.css">
    <link rel="stylesheet" href="../vendors/owl-carousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="../vendors/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="../new_css/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/style14.css">
</head>
<body>
    
    <?php 
    require_once("../connection/db.php");
    $order_id =$_GET['o_id'] ?? '';
    $conn->prepare("UPDATE ep_payment SET payment_status = 'Failed' WHERE o_id = :oid")->execute(['oid' => $order_id]);

    ?>
    <div class="error-page">
        <div class="text-center">
            <div class="error-code"></div>
            <h1 class="error-title">
                <i class="ti-alert error-icon"></i>
                Cancel Transaction
            </h1>
            <p class="error-message">Your Payment is failed!!!</p>
            <a href="../cart.php" class="btn btn-primary back-home">Retry Checkout</a>
        </div>
    </div>
</body>
</html>