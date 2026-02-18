<?php
require_once('db.php');
require('crud.php');

$table = "ep_review";
$rid = $_GET['review_id'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Review Detail</title>
  <!-- Stylesheets -->
  <link rel="shortcut icon" href="./assets/images/logo6.ico" type="image/x-icon">
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/fontawesome.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/brands.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/solid.min.css" rel="stylesheet">
  <link href="./assets/plugin/quill/quill.snow.css" rel="stylesheet">
  <link href="./assets/css/style4.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function confirmDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete these Review?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect or AJAX call
          window.location.href = "delete_review.php?review_id=" + id;
        }
      });
    }
  </script>
</head>
<style>

</style>

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
                <h3 class="mb-2 text-color-2"><a href="userreview.php">Back</a> > Review Detail</h3>
              </div>

            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <?php
        try {
          $query = $conn->prepare("SELECT * FROM ep_review r JOIN ep_products p ON r.p_id = p.p_id WHERE review_id = :rid");
          $query->execute([':rid' => $rid]);
          $fetch_review = $query->fetch(PDO::FETCH_ASSOC);
        //   $count_review = $query->rowCount();
          if ($fetch_review) {
        ?>

            <div class="mt-4">

              <!-- Main content -->
              <div class="product-detail-card">
                <div class="review-left">
                  <img src="./upload/<?= $fetch_review['image'] ?>" alt="review Image">
                </div>

                <div class="product-right">
                  <div class="product-header">
                    <h3><?= $fetch_review['name'] ?></h3>
                    <button class="icon-btn delete" onclick="confirmDelete(<?= $fetch_review['review_id'] ?>)"><i class="fas fa-trash"></i></button>

                  </div>
                  
                  <p><?php for($i=1;$i<=$fetch_review['rate'];$i++){
                    echo "<i class='fas fa-star rating-stars text-size-18'></i>";
                  } ?></p>
                  <p class="product-desc"><strong>Title:</strong> <?= $fetch_review['title'] ?></p>
                  <p class="product-desc">
                    "<?= $fetch_review['description'] ?? 'No description available.' ?>"
                  </p>
                </div>
              </div>


            </div>
      </div>
      <!-- Footer -->
      <?php include('footer.php'); ?>
    </div>
<?php
          }
        } catch (PDOException $ex) {
          echo $ex;
        }
?>
