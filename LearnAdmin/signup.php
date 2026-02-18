<?php

require_once("db.php");

require('crud.php');

$errors = [];

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $gender = $_POST['gender'] ?? '';

    //hash password
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    if (
        empty($name) || empty($email) || empty($password) ||
        empty($mobile) || empty($dob) || empty($address) ||
        empty($gender)
    ) {
        $errors[] = "Please Fill required fields!!!";
        // sweetAlert("Warning", "Please Fill required fields!!!", "warning");
    } 
    if (strlen($mobile) != 10) {
        $errors[] = "Invalid Mobile number!!";
        // sweetAlert("Warning", "Mobile number must be 10 digit!!", "warning");
    } 
     if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters!";
        // sweetAlert("Warning", "Password must be at least 8 characters!", "warning");
    } 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email Format!";
        // sweetAlert("Warning", "Invalid Email Format!!", "warning");
    }
    if(!empty($errors)){
        sweetAlert("Error!","Something Went Wrong!!!","error");
    } 
    else {

        $table = "ep_users";
        $q = insert($table, [
            "name"      => $name,
            "email"     => $email,
            "password"  => $hash_password,
            "mobile"    => $mobile,
            "dob"       => $dob,
            "address"   => $address,
            "gender"    => $gender,
            "role"      => "admin"
        ]);

        if ($q) {
            header("Location:login.php");
        } else {
            sweetAlert("Warning", "Invalid Email & Password!!!", "warning");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup</title>
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
        <?php if(!empty($errors)){ 
            $class = "color:red";
            ?>
        
        <div class="col-md-6 col-lg-4 alert alert-danger " style=" background-color: #ffdddd;border-left: 6px solid #f44336;padding-left: 20px;margin-left: 420px;">
            <ul>
                <?php foreach($errors as $er){ ?>
                <li><?= $er ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4" style="width: 45%;border-radius: 20px;background: aliceblue;padding: 37px;box-shadow: 0 8px 25px #303030;">

                <!-- Logo -->
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <a href="login.php"><img class="logo_css" src="assets/images/logo9.png" alt="logo" style="width: 320px;"></a>
                    </div>
                </div>

                <!-- Sign In Form -->
                <h2 class="mb-4 text-dark h4">Sign Up</h2>
                <form method="post">

                    <!-- Name Input -->
                    <div class="mb-3 position-relative">
                        <label for="name" class="form-label text-muted small">Name</label>
                        <?php if(!empty($errors)){ ?>
                            <span style="<?= $class ?>">*</span>
                        <?php  } ?>
                        <div class="position-relative">
                            <input type="text" name="name" class="form-control form-control-lg rounded-3"
                                id="name" placeholder="Name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-3 position-relative">
                        <label for="email" class="form-label text-muted small">Email</label>
                        <?php if(!empty($errors)){ ?>
                            <span style="<?= $class ?>">*</span>
                        <?php  } ?>
                        <div class="position-relative">
                            <input type="text" name="email" class="form-control form-control-lg rounded-3"
                                id="email" placeholder="example@outlook.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4 position-relative">
                        <label for="password" class="form-label text-muted small">Password</label>
                        <?php if(!empty($errors)){ ?>
                            <span style="<?= $class ?>">*</span>
                        <?php  } ?>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control form-control-lg rounded-3" placeholder="••••••••" id="password">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>

                    <!-- Mobile Input -->
                    <div class="mb-3 position-relative">
                        <label for="mobile" class="form-label text-muted small">Mobile No.</label>
                        <?php if(!empty($errors)){ ?>
                            <span style="<?= $class ?>">*</span>
                        <?php  } ?>
                        <div class="position-relative">
                            <input type="number" name="mobile" class="form-control form-control-lg rounded-3"
                                id="mobile" placeholder="+91 00000 00000" value="<?= isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : '' ?>">
                            <i class="fas fa-mobile-alt input-icon"></i>
                        </div>
                    </div>

                    <!-- DOB Input -->
                    <div class="mb-3 position-relative">
                        <label for="dob" class="form-label text-muted small">Birth Date</label>
                        <?php if(!empty($errors)){ ?>
                            <span style="<?= $class ?>">*</span>
                        <?php  } ?>
                        <div class="position-relative">
                            <input type="date" name="dob" class="form-control text-muted small form-control-lg rounded-3"
                                id="dob" value="<?= $_POST['dob'] ?? '' ?>">
                            <!-- <i class="fas fa-user input-icon"></i> -->
                        </div>
                    </div>

                    <!-- Address Input -->
                    <div class="mb-3 position-relative">
                        <label for="address" class="form-label text-muted small">Address</label>
                        <?php if(!empty($errors)){ ?>
                            <span style="<?= $class ?>">*</span>
                        <?php  } ?>
                        <div class="position-relative">
                            <!-- <input type="number" class="form-control form-control-lg rounded-3" 
                                       id="mobile" placeholder="+91 00000 00000"> -->
                            <textarea name="address" id="address" class="form-control form-control-lg rounded-3" placeholder="Address"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''  ?></textarea>
                            <i class="fas fa-home input-icon"></i>
                        </div>
                    </div>

                    <!-- Gender Input -->
                    <div class="mb-3 position-relative">
                        <label for="gender" class="form-label text-muted small">Gender</label>
                        <?php if(!empty($errors)){ ?>
                            <span style="<?= $class ?>">*</span>
                        <?php  } ?>

                        <div class="form-check mb-2">
                            <input class="form-check-input" value="Male" type="radio" name="gender" id="flexRadioDefault1">
                            <label class="form-check-label text-muted small" for="flexRadioDefault1">
                                Male
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" value="Female" type="radio" name="gender" id="flexRadioDefault2">
                            <label class="form-check-label text-muted small" for="flexRadioDefault2">
                                Female
                            </label>
                        </div>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit" name="submit" class="btn btn-signin btn-lg w-100 rounded-3 mb-4">
                        Sign Up
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
    <?php require_once("sweetAlert.php") ?>