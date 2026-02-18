<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Orders</title>
  <!-- Stylesheets -->
  <link rel="shortcut icon" href="./assets/images/logo6.ico" type="image/x-icon">
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/fontawesome.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/brands.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/solid.min.css" rel="stylesheet">
  <link href="./assets/plugin/quill/quill.snow.css" rel="stylesheet">
  <link href="./assets/css/style4.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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
      <div class="main-content">
        <div class="row">
          <div class="col-12">
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-size-26 text-color-2">Products</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">
                  <!-- Date Range Button -->

                  <div class="input-group flex-nowrap">
                    <span class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                    <input type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search Products" aria-label="Username" aria-describedby="addon-wrapping">
                  </div>
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle" style="margin-left: 20px;" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- <i class="fa-solid fa-filter me-3"></i>
                                      Filter by
                                    <i class="fa-solid fa-chevron-right ms-3 text-size-sm"></i>
                                    <ul class="dropdown-menu">
                                      <li><a class="dropdown-item"  href="#">Active</a></li>
                                      <li><a class="dropdown-item" href="#">Inactive</a></li>
                                   </ul> -->
                    <select class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26"
                      name="filter"
                      id="filterbystatus">
                      <option value="" selected="selected">Filter by</option>
                      <option value="active"><a href="products.php?status='active'">Active</a></option>
                      <option value="inactive"> <a href="products.php?status='inactive'">In-Active</option>
                    </select>
                  </div>
                  <!-- Reports Button -->
                  <a href="#" data-bs-toggle="modal" data-bs-target="#CreateModal" class="cursor-pointer ms-4 bg-white bg-primary text-white d-flex align-items-center px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">
                    <i class="fa-solid fa-plus me-3"></i>
                    Add Products
                  </a>
                </div>
              </div>
            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <!-- <div id="result"></div> -->
        <div class="card shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive table-rounded-top">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="select-all" class="custom-checkbox"></th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                  </tr>
                </thead>
                <tbody id="result">

                </tbody>

              </table>
            </div>

            <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
              <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                <ul class="pagination">

                  <!-- <li class="page-item active"><a class="page-link" href="#">1</a></li>
                              <li class="page-item"><a class="page-link" href="#">2</a></li>
                              <li class="page-item"><a class="page-link" href="#"><i class="fas fa-ellipsis-h"></i></a></li>
                              <li class="page-item"><a class="page-link" href="#">6</a></li>
                              <li class="page-item"><a class="page-link" href="#">7</a></li>
                              <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                              </li> -->
                </ul>
              </nav>
              <div class="d-flex justify-content-end">
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer -->
    <?php include('footer.php'); ?>
  </div>

  <!--Create  Modal -->
  <div class="modal fade" id="CreateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content rounded-0">
        <div class="modal-body p-4 position-relative">
          <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
          <h2 class="h5 text-color-2 py-2">Create Product</h2>
          <form class="row g-3" method="post" enctype="multipart/form-data">
            <div class="col-12">
              <label for="Product_name" class="form-label text-color-2 text-normal">Product Name</label>
              <input type="text" name="pname" class="form-control" id="Product_name" placeholder="Enter product name">
            </div>
            <div class="mb-3">
              <label for="Product_desc" class="form-label text-color-2 text-normal">Description</label>
              <textarea name="desc" class="form-control" id="Product_desc" rows="3" placeholder="Enter Description"></textarea>
            </div>
            <div class="col-6">
              <label for="price" class="form-label text-color-2 text-normal">Price</label>
              <input type="number" name="price" class="form-control" id="price" placeholder="Enter Price">
            </div>
            <div class="col-6">
              <label for="stock" class="form-label text-color-2 text-normal">Stock</label>
              <input type="number" name="stock" class="form-control" id="stock" placeholder="Enter Stock">
            </div>

            <div class="col-6">
              <label for="edate" class="form-label text-color-2 text-normal">Expiry Date</label>
              <input type="date" name="edate" class="form-control" id="edate" min="<?= date('Y-m-d') ?>" placeholder="mm/dd/yyyy">
            </div>
            <div class="col-6">
              <label for="status" class="form-label text-color-2 text-normal">Status</label>
              <select id="status" name="status" class="form-select text-normal">
                <option value="">Choose Status</option>
                <option value="Active">Active</option>
                <option value="In-Active">In-Active</option>
              </select>
            </div>

            <div class="col-12">
              <label for="UserEducation" class="form-label text-color-2 text-normal">Category</label>
              <select id="UserEducation" name="category" class="form-select text-normal">
                <option value="" selected="selected" disabled>Choose Category</option>
                <?php
                $q = "SELECT * FROM ep_category";
                $res = $conn->prepare($q);

                if ($res->execute()) {
                  $fetch_row = $res->fetchAll(PDO::FETCH_ASSOC);
                  // echo $fetch_row['category_name'];
                  foreach ($fetch_row as $r) {
                    echo "<option value='{$r['c_id']}'>" . $r['category_name'] . "</option>";
                  }
                } else {
                  echo "Fail";
                }
                ?>

              </select>
            </div>

            <div class="col-12">
              <label class="form-label text-color-2 text-normal">Product Image</label>
              <input type="file" name="pimg" class="form-control">
              <div class="file-input-container max-w-100">
                <!-- <input type="file" id="fileInput" name="pimg" class="file-input">
                        <label for="fileInput" class="file-label">
                          <span class="file-name">Choose file</span>
                          <span class="file-button">Browse</span>
                        </label> -->


              </div>
            </div>

            <div class="col-12 mt-5">
              <button type="submit" name="submit" class="btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">ADD Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!--Edit  Modal -->
  <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content rounded-0">
        <div class="modal-body p-4 position-relative">
          <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
          <h2 class="h5 text-color-2 py-2">Edit Student</h2>
          <form class="row g-3" method="post">
            <input type="hidden" name="p_id" id="edit_p_id">

            <div class="col-12">
              <label for="Product_name" class="form-label text-color-2 text-normal">Product Name</label>
              <input type="text" name="pname" class="form-control" id="edit_pname" placeholder="Enter product name">
            </div>
            <!-- <div class="col-12">
                <label for="Product_desc" class="form-label text-color-2 text-normal">Description</label>
                <input type="text" name="desc" class="form-control" id="edit_desc" placeholder="Enter Description">
              </div> -->
            <div class="mb-3">
              <label for="Product_desc" class="form-label text-color-2 text-normal">Description</label>
              <textarea name="desc" class="form-control" id="edit_desc" rows="3" placeholder="Enter Description"></textarea>
            </div>
            <div class="col-6">
              <label for="price" class="form-label text-color-2 text-normal">Price</label>
              <input type="number" name="price" class="form-control" id="edit_price" placeholder="Enter Price">
            </div>
            <div class="col-6">
              <label for="stock" class="form-label text-color-2 text-normal">Stock</label>
              <input type="number" name="stock" class="form-control" id="edit_stock" placeholder="Enter Stock">
            </div>

            <div class="col-6">
              <label for="edate" class="form-label text-color-2 text-normal">Expiry Date</label>
              <input type="date" name="edate" class="form-control" min="<?= date('Y-m-d') ?>" id="edit_edate" placeholder="mm/dd/yyyy">
            </div>
            <div class="col-6">
              <label for="status" class="form-label text-color-2 text-normal">Status</label>
              <select name="status" id="edit_status" class="form-select text-normal">
                <option value="">Choose Status</option>
                <option value="Active">Active</option>
                <option value="In-Active">In-Active</option>
              </select>
            </div>

            <div class="col-12">
              <label for="edit_category0" class="form-label text-color-2 text-normal">Category</label>
              <select id="edit_category" name="category" class="form-select text-normal">
                <option value="" selected="selected" disabled>Choose Category</option>
                <?php
                $q = "SELECT * FROM ep_category";
                $res = $conn->prepare($q);

                if ($res->execute()) {
                  $fetch_row = $res->fetchAll(PDO::FETCH_ASSOC);
                  // echo $fetch_row['category_name'];
                  foreach ($fetch_row as $r) {
                    echo "<option value='{$r['c_id']}'>" . $r['category_name'] . "</option>";
                  }
                } else {
                  echo "Fail";
                }
                ?>

              </select>
            </div>

            <div class="col-12">
              <label class="form-label text-color-2 text-normal">Product Image</label>
              <input type="file" name="pimg" id="edit_img" class="form-control">
              <div class="file-input-container max-w-100">
                <!-- <input type="file" id="fileInput" name="pimg" class="file-input">
                        <label for="fileInput" class="file-label">
                          <span class="file-name">Choose file</span>
                          <span class="file-button">Browse</span>
                        </label> -->


              </div>
            </div>

            <div class="col-12 mt-5">
              <button type="submit" name="edit" class="btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">Update Product</button>
            </div>
          </form>


        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('EditModal').addEventListener('show.bs.modal', function(event) {

      let btn = event.relatedTarget;

      document.getElementById('edit_p_id').value = btn.getAttribute('data-id');
      document.getElementById('edit_pname').value = btn.getAttribute('data-name');
      document.getElementById('edit_desc').value = btn.getAttribute('data-desc');
      document.getElementById('edit_price').value = btn.getAttribute('data-price');
      document.getElementById('edit_stock').value = btn.getAttribute('data-stock');
      document.getElementById('edit_edate').value = btn.getAttribute('data-expiry');
      document.getElementById('edit_status').value = btn.getAttribute('data-status');
      // document.getElementById('edit_img').value = btn.getAttribute('data-img');
      document.getElementById('edit_category').value = btn.getAttribute('data-category');


    });
  </script>



  <!-- Scripts -->
  <script>
    // $(document).on("keyup", "#livesearch", function() {
    //     var input = $(this).val();
    //     // alert(input);
    //     if (input != "") {
    //         $.ajax({
    //             url: "search_data.php",
    //             method: "POST",
    //             data: {
    //                 input: input
    //             },

    //             success: function(data) {
    //                 $("#result").html(data).show();
    //             }
    //         });
    //     } else {
    //         $("#result").hide().html("");
    //     }
    // })
    $(document).ready(function() {
      function load_products(page = 1) {
        var status = $('#filterbystatus').val();
        var search = $('#livesearch').val();

        $.ajax({
          url: "search_data.php",
          method: "POST",
          data: {
            search: search,
            status: status,
            page: page
          },

          success: function(data) {
            $("#result").html(data);
          }
        });
      }

      //default 
      load_products();
      //when status apply
      $(document).on("change", "#filterbystatus", function() {
        load_products();
      });

      //when search
      $(document).on("keyup", "#livesearch", function() {
        load_products();
      });

      //pagination
      $(document).on("click", ".page-link", function(e) {
        e.preventDefault();
        var page = $(this).data("page");
        load_products(page);

      })


    });
  </script>
  <script src="./assets/js/jquery-3.6.0.min.js"></script>
  <script src="./assets/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/plugin/chart/chart.js"></script>
  <script src="./assets/plugin/quill/quill.js"></script>
  <script src="./assets/js/chart.js"></script>
  <script src="./assets/js/main.js"></script>
</body>

</html>