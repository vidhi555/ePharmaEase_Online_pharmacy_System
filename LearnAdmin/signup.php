<?php

require_once("db.php");

require('crud.php');

$errors = [];

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['phone'];
    
    $address = $_POST['address'];
    $gender = $_POST['gender'] ?? '';

    //hash password
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    if (
        empty($name) || empty($email) || empty($password) ||
        empty($mobile)  || empty($address) ||
        empty($gender)
    ) {
        $errors[] = "Please Fill required fields!!!";
        // sweetAlert("Warning", "Please Fill required fields!!!", "warning");
    }
    // check duplicate email
    $dup_email = $conn->prepare("SELECT * FROM ep_users WHERE email = :dup_email AND role = 'admin'");
    $dup_email->execute(['dup_email'=>$email]);
    $fetch_row = $dup_email->fetch(PDO::FETCH_ASSOC);
    if($fetch_row > 0){
        $errors[] = "This Email is Already Exist.Try with another Email!";
    } 

    if(!preg_match('/^[a-zA-Z0-9]{6}$/',$password)){
          $errors[] = "Password must be exactly 6 characters (letters & numbers only)";
    } 
    if(!preg_match("/^\+?[0-9]{10,15}$/", $mobile)){
        $errors[] = "Invalid Mobile number Length.";
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

$page_title = "Sign Up";
require_once('header2.php');
?>

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
            <div class="col-11 col-sm-8 col-md-6 col-lg-4" style="width: auto;border-radius: 20px;background: aliceblue;padding: 37px;box-shadow: 0 8px 25px #303030;">

                <!-- Logo -->
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <a href="login.php"><img class="logo_css" src="assets/images/logo9.png" alt="logo" style="width: 320px;"></a>
                    </div>
                </div>

                <!-- Sign In Form -->
                <h2 class="mb-4 text-dark h4">Sign Up</h2>
                <form method="post" id="register_form">

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
                            <input id="phone" type="tel" name="phone" style="<?= $class ?>" class="form-control" placeholder="00000 00000" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" >

                           
                            <i class="fas fa-mobile-alt input-icon"></i>
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
    <?php require_once("sweetAlert.php");
        require_once("footer.php");
    ?>