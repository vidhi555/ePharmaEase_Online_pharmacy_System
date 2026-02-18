<?php
require_once("db.php");

try {
    $get_token = $_GET['token'] ?? '';
    // echo $get_token;
    $verify_link = $conn->prepare("SELECT * FROM ep_users WHERE token = :token AND expity_token <= NOW()");
    $verify_link->execute([
        'token' => $get_token
    ]);
    $fetch_user_data = $verify_link->fetch(PDO::FETCH_ASSOC);
    if ($fetch_user_data) {
        $uid = $fetch_user_data['u_id'];
        // echo $uid;
    } else {
        echo "user not found";
    }

    if (!$verify_link) {
        sweetAlert("Warning", "Your link is expired!", "warning");
    } else {
        if (isset($_POST['reset'])) {
            $pwd = $_POST['password'];
            $cpwd = $_POST['cpassword'];

            if ($pwd !== $cpwd) {
                sweetAlert("Password not Matched", "", "warning");
            } else {
                $hash_pwd = password_hash($pwd, PASSWORD_DEFAULT);
                $reset_password  = $conn->prepare("UPDATE ep_users SET password = :password, token = NULL, expity_token = NULL WHERE u_id = :id");
                $result = $reset_password->execute([
                    'password' => $hash_pwd,
                    'id' => $uid
                ]);
                if ($result) {
                    header("Location:login.php");
                } else {
                    sweetAlert("Fail", "Something Went Wrong", "warning");
                }
            }
        }
    }
} catch (PDOException $e) {
    echo $e;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
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
                    <h2 class="mb-4 text-dark h4">Reset Password</h2>
                    <form method="post">
                        <!-- password Input -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label text-muted small">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control form-control-lg rounded-3"
                                    id="password" name="password" placeholder="*********">
                                <i class="fas fa-key input-icon"></i> <!-- key repeat -->
                            </div>
                            <label for="cpassword" class="form-label text-muted small">Confirm Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control form-control-lg rounded-3"
                                    id="cpassword" name="cpassword" placeholder="*********">
                                <i class="fas fa-check-circle input-icon"></i><!-- confirmation -->

                            </div>
                        </div>

                        <!-- Forgot Password Button -->
                        <button type="submit" name="reset" class="btn btn-signin btn-lg w-100 rounded-3 mb-4">
                            RESET
                        </button>

                        <!-- Divider -->
                        <!-- <div class="text-center text-muted mb-4 text-size-14">
                            You have an account? <a href="login.php" class="text-primary">Login</a>
                        </div> -->
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