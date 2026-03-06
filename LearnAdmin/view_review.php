<?php
require_once('db.php');
require('crud.php');

$table = "ep_review";
$rid = $_GET['review_id'];

$page_title = "Review Details";
require_once('header2.php');
?>


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
                <!-- <h3 class="mb-2 text-color-2"><a href="userreview.php">Back</a> > Review Detail</h3> -->
                <nav>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="userreview.php">Review</a></li>
                    <li class="breadcrumb-item active">Review Details</li>
                  </ol>
                </nav>
              </div>

            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <?php
        try {
          $query = $conn->prepare("SELECT r.*, p.image , p.name as pname , u.name , u.email , u.image as user_img FROM ep_review r JOIN ep_products p ON r.p_id = p.p_id JOIN ep_users u ON u.u_id = r.u_id WHERE review_id = :rid");
          $query->execute([':rid' => $rid]);
          $fetch_review = $query->fetch(PDO::FETCH_ASSOC);
          //   $count_review = $query->rowCount();
          if ($fetch_review) {
        ?>

            <div class="mt-4">

              <!-- Main content -->
              <div class="product-detail-card">
                <div class="review-left">
                  <div class="flip-box">
                    <div class="flip-inner">

                      <!-- Front Side (Image) -->
                      <div class="flip-front">
                        <img src="./All_images_uploads/<?= $fetch_review['image'] ?>" alt="review Image">
                      </div>

                      <!-- Back Side (User Info) -->
                      <div class="flip-back">
                        <h6 class="revired_back_header">Reviewed-By:</h6>
                        <img src="../ePharma-master/uploads/<?= $fetch_review['user_img'] ?>" alt="user_image" style="width: 100px;">
                        <h6 style="text-transform: capitalize;"><?= $fetch_review['name'] ?></h6>
                        <p class="small"><?= $fetch_review['email'] ?></p>
                        <!-- <p class="small">
                          <?= date("d M Y", strtotime($fetch_review['created_at'])) ?>
                        </p> -->
                        <!-- <span class="badge bg-success">Verified</span> -->
                      </div>

                     
                    </div>
                  </div>
                </div>

                <div class="product-right">
                  <div class="product-header">
                    <h3><?= $fetch_review['pname'] ?></h3>
                    <button class="icon-btn delete" onclick="confirmDelete(<?= $fetch_review['review_id'] ?>,'delete_review.php?review_id=')"><i class="fas fa-trash"></i></button>

                  </div>

                  <p><?php for ($i = 1; $i <= $fetch_review['rate']; $i++) {
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