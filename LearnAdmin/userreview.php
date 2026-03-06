<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
 if(isset($_POST['page'])){

    $data = $_POST['values'];

    $limit = 5;
    $page = $_POST['page'];
    $offset = ($page - 1) * $limit;

   

      $orderby = "ORDER BY review_id DESC";
     if($data == 'low'){
      $orderby = "ORDER BY rate";
     }
     if($data == 'high'){
      $orderby = "ORDER BY rate DESC";
     }

     $query = $conn->prepare("SELECT * FROM ep_review r JOIN ep_products p ON p.p_id = r.p_id JOIN ep_users u ON u.u_id=r.u_id $orderby LIMIT $offset , $limit");
     $query->execute();

     $fetch_data = $query->fetchAll(PDO::FETCH_ASSOC);
     if($fetch_data){
      $id = $offset;
      foreach($fetch_data as $r){
        $id++;
        ?>
                          <tr>
                            <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                            <td><?= $id ?></td>
                            <!-- <td><img src="All_images_uploads/<?= $r['image'] ?>" alt=""></td> -->
                            <td>
                              <div class="d-flex justify-content-start align-items-center">
                                <!-- <img src="./All_images_uploads/<?= $r['image'] ?>" class="tbl-img" alt="img"> -->
                                <span class="ms-2"><?= $r['name'] ?></span>
                              </div>
                            </td>

                            <td><?= $r['title'] ?></td>
                           
                  
                            <!-- <td><?= $r['rate'] ?></td> -->
                            <td>
                              <p style="
    color:#3b82f6;
    padding:2px 8px;
    border-radius:8px;
    font-size:12px;
    margin-left:6px;">
                              <?php
                              $count = $r['rate'];
                              for ($i = 0; $i < $count; $i++) {
                                echo "<i class='fas fa-star rating-stars text-size-13'></i>";
                              }
                              ?> (<?= $r['rate'] ?>)</p>
                            </td>

                            <td class="text-center">
                              <a data-bs-toggle="tooltip" title="View full Review" class="btn btn-sm btn-success mb-2 mb-lg-0 me-0 me-lg-2" href="view_review.php?review_id=<?= $r['review_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>
                              <button onclick="confirmDelete(<?= $r['review_id'] ?>,'delete_review.php?review_id=')" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                          </tr>
        <?php
      }
     }
     exit();
    }
$page_title = "User Review";
require_once("header2.php");  
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
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Review</li>

              </ol>
            </nav>
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
                      
                      <th>Rating</th>


                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody id="result">
                  
                  
                  </tbody>
                </table>
              </div>
              <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <ul class="pagination">
                    <?php 
                       $s = $_POST['search']??'';
                      createPagination("ep_review","",$s,5);
                    ?>
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
    // Pagination + Search

function loadData(page = 1){
   let values = $('#filter_by_rating').val();
  
    $.ajax({
        url: window.location.href,
        method: "POST",
        data: {
          page: page,
          values:values
        },
        success: function(data){
            $("#result").html(data);
        }
    });
}

function loadPagination(){
    $.ajax({
        url: "pagination_category.php",
        success: function(data){
            $("#pagination").html(data);
        }
    });
}

// click pagination
$(document).on("click",".page-btn",function(e){
    e.preventDefault();
    var page = $(this).data("page");
    loadData(page);
});
$(document).ready(function(){
  $(document).on("change","#filter_by_rating",function(){
    var a = $(this).val();
    // alert(a);
    loadData(1);
    loadPagination();
  })
})

// first load
loadData();
loadPagination();


  </script>
  