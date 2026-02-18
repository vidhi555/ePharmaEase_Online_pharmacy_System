<?php

require_once('db.php');
require('crud.php');

$errors = [];

if (isset($_POST['submit'])) {
    //collect user input
    $email = $_POST['email'];
    $password = $_POST['password'];
    $table = "ep_users";

    //check empty fields
    if (empty($email) || empty($password)) {
        sweetAlert("Warning", "Email and Password Required!!!", "warning");
    } else {
        //login
        $sql = "SELECT * FROM $table WHERE email=:email LIMIT 1";
        $res = $conn->prepare($sql);
        $res->execute(["email" => $email]);

        $user = $res->fetch(PDO::FETCH_ASSOC);
        // echo password_verify($password, $user['password'])?"true":"false";
        // die();
        if ($user) {
            $pwd = $user['password'];
            // var_dump(password_verify($password, $pwd));
            // exit;

            if (password_verify($password, $pwd)) {
                $_SESSION['user'] = $user['name'];  //store Username in sesssion
                $_SESSION['admin_id'] = $user['u_id'];  //store Username in sesssion

                if (isset($_POST['remember'])) {
                    //cookie store email & password
                    setcookie("email", $email, time() + 86400);
                    setcookie("password", $password, time() + 86400);
                }
                // echo "<script>sweetAlert('Success','Login Successfull!!!','success');</script>";
                //redirect to index page
                header("Location:index.php");
            } else {
                sweetAlert("Warning!", "Password Not Matched!!", "error");
            }
        } else {
            // echo "Invalid email and password";
            sweetAlert("Warning!", "Invalid email!", "warning");
        }
        // if ($user) {
        //     if (password_verify($password, $user['password'])) {

        //         $_SESSION['user'] = $user['name'];
        //         header("Location:index.php");
        //         exit;

        //     } else {
        //         sweetAlert("Error", "Password is incorrect!", "error");
        //     }
        // } else {
        //     sweetAlert("Error", "Email not found!", "error");
        // }
    }
}

//store cookie
$email_cookie = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$password_cookie = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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
            <div class="col-11 col-sm-8 col-md-8 col-lg-4" style="border-radius: 20px;background: aliceblue;padding: 37px;box-shadow: 0 8px 25px #303030;">

                <!-- Logo -->
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <a href="login.php"><img src="assets/images/logo9.png" alt="logo" style="width: 320px;"></a>
                    </div>
                </div>

                <!-- Sign In Form -->
                <h2 class="mb-4 text-dark h4">Sign In</h2>
                <form method="post">
                    <!-- Email Input -->
                    <div class="mb-3 position-relative">
                        <label for="email" class="form-label text-muted small">Email</label>
                        <div class="position-relative">
                            <input type="email" name="email" class="form-control form-control-lg rounded-3"
                                id="email" placeholder="example@outlook.com" value="<?php echo $email_cookie; ?>">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4 position-relative">
                        <label for="password" class="form-label text-muted small">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control form-control-lg rounded-3"
                                id="password" placeholder="••••••••" value="<?php echo $password_cookie; ?>">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12 col-lg-6 mb-2 mb-lg-0">
                            <div class="form-check ps-0">
                                <input type="checkbox" class="custom-checkbox" name="remember" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 text-lg-end">
                            <a href="forgot-password.php" class="text-primary">Forgot password</a>
                        </div>
                    </div>


                    <!-- Sign In Button -->
                    <button type="submit" name="submit" class="btn btn-signin btn-lg w-100 rounded-3 mb-4">
                        Sign In
                    </button>

                    <!-- Divider -->
                    <div class="text-center text-muted mb-4 text-size-14">
                        Don't have an account yet? <a href="signup.php" class="text-primary">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <?php require_once('sweetAlert.php'); ?>