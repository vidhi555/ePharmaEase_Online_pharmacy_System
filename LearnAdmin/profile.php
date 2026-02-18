<?php
require_once('db.php');
require('crud.php');
$uid = $_SESSION['admin_id'];
//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$errors = [];
$success = [];
try {
  if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    //Image Validation
    $photo = $_FILES['photo']['name'];
    $tmp_photo = $_FILES['photo']['tmp_name'];
    $ext = pathinfo($photo, PATHINFO_EXTENSION);
    $allowed_type = ['jpg', 'jpeg', 'png', 'webg'];
    $img_name = 'user' . time() . "." . $ext;
    $target = 'uploads/' . basename($img_name);
    if (!move_uploaded_file($tmp_photo, $target)) {
      // sweetAlert("warning","Invalid Image Type.","warning");
      $errors[] = "Image Uploading Failed!";
    }



    if (!empty($errors)) {
      sweetAlert("Error", "Please Try Again!", "error");
    } else {
      $query = "UPDATE ep_users SET `name`= :name,`email`= :email ,`mobile`= :mno,`dob`= :dob,`address`= :address ,`gender`= :gender , image = :photo WHERE u_id= :uid";
      $update_user = $conn->prepare($query);
      $update_user->execute([
        'name' => $name,
        'email' => $email,
        'mno' => $mobile,
        'dob' => $dob,
        'address' => $address,
        'gender' => $gender,
        'photo' => $img_name,
        'uid' => $uid
      ]);
      $success[] = "Update Successfully";
      header("location:profile.php");
      exit;
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
  <title>Admin Profile</title>
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
  <!-- Preloader -->
  <div id="preloader">
    <div class="spinner"></div>
  </div>
  <!-- Main Wrapper -->
  <div id="main-wrapper" class="d-flex">
    <?php
    //Sidebar content
    include('sidebar.php');
    ?>
    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Header -->
      <div class="header d-flex align-items-center justify-content-between">
        <?php
        include('header.php');


        ?>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <div class="row">
          <div class="col-12">
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-size-26 text-color-2">Admin Profile</h3>

              </div>
            </div>
          </div>
        </div>



        <!-- Admin Profile Section -->
        <div class="container-fluid py-4">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">

              <?php if (!empty($success)) { ?>
                <div class="alert alert-danger">
                  <?php foreach ($success as $suc) { ?>
                    <p><?= $suc ?></p>
                  <?php } ?>
                </div>
              <?php } ?>
              <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                  <?php
                  try {

                    // echo $uid;
                    if (isset($_SESSION['admin_id'])) {
                      $query = $conn->prepare("SELECT * FROM ep_users WHERE role = 'admin' AND u_id = :uid");
                      $query->execute(['uid' => $uid]);
                      $fetch_admin = $query->fetch(PDO::FETCH_ASSOC);
                      if ($fetch_admin) {
                  ?>
                        <div class="text-center mb-4">

                          <img src="uploads/<?= $fetch_admin['image'] ?>"
                            class="rounded-circle mb-3 shadow-sm"
                            width="120"
                            height="120"
                            style="border:1px solid lightblue;"
                            alt="Admin Profile" required>




                          <h4 class="fw-bold mb-0"><?= ucfirst($fetch_admin['name']) ?></h4>
                          <p class="text-muted mb-1">Administrator</p>

                          <button class="btn btn-primary btn-sm mt-2"
                            data-bs-toggle="modal"
                            data-bs-target="#editProfileModal">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                          </button>
                        </div>

                        <hr>


                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="text-muted small">Username</label>
                            <p class="fw-semibold mb-0"><?= ucfirst($fetch_admin['name']) ?></p>
                          </div>
                          <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <p class="fw-semibold mb-0"><?= $fetch_admin['email'] ?></p>
                          </div>



                          <div class="col-md-6">
                            <label class="text-muted small">Mobile</label>
                            <p class="fw-semibold mb-0">+91 <?= $fetch_admin['mobile'] ?></p>
                          </div>

                          <div class="col-md-6">
                            <label class="text-muted small">Address</label>
                            <p class="fw-semibold mb-0"><?= $fetch_admin['address'] ?></p>
                          </div>

                          <div class="col-md-6">
                            <label class="text-muted small">Birth Date</label>
                            <p class="fw-semibold mb-0"><?= date("d M ,Y", strtotime($fetch_admin['dob'])) ?></p>
                          </div>

                          <div class="col-md-6">
                            <label class="text-muted small">Gender</label>
                            <p class="fw-semibold mb-0"><?= ucfirst($fetch_admin['gender']) ?></p>
                          </div>
                        </div>

                </div>
              </div>

            </div>
          </div>
        </div>





      </div>
      <!-- Footer -->

    </div>


  </div>
  <?php include('footer.php'); ?>

  <!-- Edit Profile Modal -->
  <div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content rounded-4">

        <?php if (!empty($errors)) { ?>
          <div class="alert alert-danger" style="margin:40px;">
            <?php foreach ($errors as $r) { ?>
              <ul>
                <li><?= $r ?></li>
              </ul>
            <?php } ?>
          </div>
        <?php } ?>
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
          <div class="modal-body">

            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control"
                  name="name" value="<?= $fetch_admin['name'] ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control"
                  name="email" value="<?= $fetch_admin['email'] ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Mobile</label>
                <input type="number" class="form-control"
                  name="mobile" value="<?= $fetch_admin['mobile'] ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" class="form-control"
                  name="address" value="<?= $fetch_admin['address'] ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Birth Date</label>
                <input type="date" class="form-control"
                  name="dob" value="<?= $fetch_admin['dob'] ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" id="gender" value="<?= $fetch_admin['gender'] ?>">
                  <option value="male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>

              <div class="col-md-12">
                <label class="form-label">Update Photo</label>
                <input type="file" class="form-control" name="photo">
              </div>

            </div>

          </div>

          <div class="modal-footer">
            <button type="button"
              class="btn btn-light"
              data-bs-dismiss="modal">
              Cancel
            </button>
            <button type="submit"
              name="update_profile"
              class="btn btn-primary">
              Update Profile
            </button>
          </div>
        </form>
  <?php
                      }
                    }
                  } catch (PDOException $e) {
                    echo $e;
                  }
  ?>
      </div>
    </div>
  </div>
  <style>
    .card img {
      object-fit: cover;
    }

    .card label {
      font-size: 13px;
    }
  </style>