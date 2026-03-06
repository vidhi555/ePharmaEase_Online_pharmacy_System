<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
 if(isset($_POST['page'])){
      $page = $_POST['page'] ? (int)$_POST['page']:1;
      if($page<1) $page=1;
      $limit = 3;
      $offset =($page-1)*$limit;


    //   $orderby = "ORDER BY rate";
    //  if($data == 'low'){
    //   $orderby = "ORDER BY rate";
    //  }
    //  if($data == 'high'){
    //   $orderby = "ORDER BY rate DESC";
    //  }

     $query = $conn->prepare("SELECT * FROM ep_review r JOIN ep_products p ON p.p_id = r.p_id  $offset,$limit");
     $query->execute();

     $fetch_data = $query->fetchAll(PDO::FETCH_ASSOC);
     if($fetch_data){
      foreach($fetch_data as $r){
        ?>
        <tr>
                            <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                            <td><?= $r['review_id'] ?></td>
                            <!-- <td><img src="All_images_uploads/<?= $r['image'] ?>" alt=""></td> -->
                            <td>
                              <div class="d-flex justify-content-start align-items-center">
                                <!-- <img src="./All_images_uploads/<?= $r['image'] ?>" class="tbl-img" alt="img"> -->
                                <span class="ms-2"><?= $r['name'] ?></span>
                              </div>
                            </td>

                            <td><?= $r['title'] ?></td>
                            <td data-bs-toggle="tooltip" title="View full Review"><?= substr($r['description'],0,50) ?>...</td>
                  
                            <!-- <td><?= $r['rate'] ?></td> -->
                            <td>
                              <?php
                              $count = $r['rate'];
                              for ($i = 0; $i < $count; $i++) {
                                echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                              }
                              ?> (<?= $r['rate'] ?>)
                            </td>

                            <td class="text-center">
                              <a class="btn btn-sm btn-success mb-2 mb-lg-0 me-0 me-lg-2" href="view_review.php?review_id=<?= $r['review_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>
                              <button onclick="confirmDelete(<?= $r['review_id'] ?>)" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                          </tr>
<!-- Pagination -->
 <?php 
 $records = $conn->prepare("SELECT COUNT(*) FROM ep_review WHERE 1");
 $records->execute();
 $total_row = $records->rowCount();
 $pagess = ceil($total_row/$limit);
for($i=1;$i<$total_row;$i++){
    echo "<a href='#' class='pagination-link btn btn-sm btn-light m-1'
                data-page='{$i}'>{$i}</a>";
}
 
 ?>



        <?php
      }
     }
     exit();
    }
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Review</title>
  <!-- Stylesheets -->
  <link rel="shortcut icon" href="./assets/images/logo6.ico" type="image/x-icon">
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/fontawesome.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/brands.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/solid.min.css" rel="stylesheet">
  <link href="./assets/plugin/quill/quill.snow.css" rel="stylesheet">
  <link href="./assets/css/style5.css" rel="stylesheet">
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
                <h3 class="mb-2 text-size-26 text-color-2">Users Review Report</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">
                  
               <!-- Date Range Button -->
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle">
                    <i class="fa-solid fa-filter"></i>
                    <select id="filter_by_rating" class="form-select text-size-sm" style="border: none;">
                      <option value="" selected="selected" disabled>Filter By Rating</option>
                      <option value="low">low to High</option>
                      <option value="high">High to Low</option>
        
                    </select>
                  </div>
                  <!-- Reports Button -->
                  <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#CreateModal" class="cursor-pointer ms-4 bg-white bg-primary text-white d-flex align-items-center px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">
                                      <i class="fa-solid fa-plus me-3"></i>
                                      Add Teacher
                                   </a> -->
                </div>
              </div>
            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <div class="mt-4">
          <div class="card shadow-sm border-0">
            <div class="card-body p-0">
              <div class="table-responsive table-rounded-top">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <!-- <th><input type="checkbox" id="select-all" class="custom-checkbox"></th> -->
                      <th>ID</th>

                      <th>Product</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Rating</th>


                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody id="result">
                    <?php
                    try {

                      $limit = 5;
                      if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                      } else {
                        $page = 1;
                      }
                      $offset = ($page - 1) * $limit;

                      $query = $conn->prepare("SELECT p.name , p.image ,r.review_id, r.title , r.description , r.rate FROM ep_review r JOIN ep_products p ON p.p_id = r.p_id LIMIT $offset,$limit");
                      if ($query->execute()) {
                        $messages = $query->fetchAll(PDO::FETCH_ASSOC);
                        $id = $offset; //print sequential numbers
                        foreach ($messages as $msg) {
                          $id++;
                    ?>
                          <tr>
                            <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                            <td><?= $id ?></td>
                            <!-- <td><img src="All_images_uploads/<?= $msg['image'] ?>" alt=""></td> -->
                            <td>
                              <div class="d-flex justify-content-start align-items-center">
                                <!-- <img src="./All_images_uploads/<?= $msg['image'] ?>" class="tbl-img" alt="img"> -->
                                <span class="ms-2"><?= $msg['name'] ?></span>
                              </div>
                            </td>

                            <td><?= $msg['title'] ?></td>
                            <td data-bs-toggle="tooltip" title="View full Review"><?= substr($msg['description'],0,50) ?>...</td>
                  
                            <!-- <td><?= $msg['rate'] ?></td> -->
                            <td>
                              <?php
                              $count = $msg['rate'];
                              for ($i = 0; $i < $count; $i++) {
                                echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                              }
                              ?> (<?= $msg['rate'] ?>)
                            </td>

                            <td class="text-center">
                              <a class="btn btn-sm btn-success mb-2 mb-lg-0 me-0 me-lg-2" href="view_review.php?review_id=<?= $msg['review_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>
                              <button onclick="confirmDelete(<?= $msg['review_id'] ?>)" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                          </tr>
<!-- Pagination -->
 <?php 
 $records = $conn->prepare("SELECT COUNT(*) FROM ep_review WHERE 1");
 $records->execute();
 $total_row = $records->rowCount();
 $pagess = ceil($total_row/$limit);
for($i=1;$i<$total_row;$i++){
    echo "<a href='#' class='pagiantion-link btn btn-sm btn-light m-1'
                data-page='{$i}'>{$i}</a>";
}
                   
                        }
                      }
                    } catch (PDOException $e) {
                      echo $e;
                    }

                    ?>

                  </tbody>
                </table>
              </div>
              <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <ul class="pagination">
                    <?php
                    $q = $conn->prepare("SELECT p.name , p.image ,r.review_id, r.title , r.description , r.rate FROM ep_review r JOIN ep_products p ON p.p_id = r.p_id");
                    $q->execute();

                    $count = $q->rowCount();
                    if ($count > 0) {
                      $pages = ceil($count / $limit);  //find total pages of all records per limit
                    ?>
                      <li class="page-item">
                        <?php if ($page > 1) { ?>
                          <a class="pagiantion-link" href="userreview.php?page=<?= $page - 1 ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                        <?php } ?>
                      </li>
                      <?php
                      for ($i = 1; $i <= $pages; $i++) {
                      ?>
                        <li class="page-item"><a class="pagiantion-link" href="userreview.php?page=<?= $i ?>"><?php echo $i; ?></a></li>
                      <?php
                      } ?>
                      <li class="page-item">
                        <?php if ($page < $pages) { ?>
                          <a class="page-link" href="userreview.php?page=<?= $page + 1 ?>" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                        <?php } ?>
                      </li>

                    <?php } ?>
                  </ul>
                </nav>
                <!-- <div class="d-flex justify-content-end">
                  <div class="page-selector">
                    <span>PAGE</span>
                    <select class="form-select" aria-label="Select page">
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    <span>OF 102</span>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php include('footer.php'); ?>
    </div>


  </div>
  <script>
    $(document).ready(function(){

    // Simple pagination
    function loaded(page = 1){
        let page = $(this).data(".pagiantion-link");

        $.ajax({
            url:window.location.href,
            method:"POST",
            data : {page:page},

            success:function(data){
                $("#result").html(data);
            }
        });
    }

    $(document).on("click",".pagiantion-link",function(e){
        e.preventDefault();
        loaded(1);
    })
    });

  </script>
  