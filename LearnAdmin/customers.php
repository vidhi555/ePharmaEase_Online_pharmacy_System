<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$table = "ep_users";

if (isset($_POST['search_cust'])) {
  $search_cust = $_POST['search_cust'];
  // echo $search_cat;
  // die();
  try {
    $sql = "SELECT * FROM $table WHERE role='customer' AND (name LIKE '{$search_cust}%' OR email LIKE '{$search_cust}%' OR mobile LIKE '{$search_cust}%' OR address LIKE '{$search_cust}%' ) LIMIT 5";
    $res = $conn->prepare($sql);
    if ($res->execute()) {
      $products = $res->fetchAll(PDO::FETCH_ASSOC);
      foreach ($products as $p) {
?>

        <tr>
                            <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                            <td><?= $p['u_id'] ?></td>
                            <td><img src="../ePharma-master/uploads/<?= $p['image'] ?>" width="50px" height="50px" alt="image" style="margin-right: 10px;">
                            <?= $p['name'] ?></td>
                            <td><?= $p['email'] ?></td>
                            <td><?= $p['mobile'] ?></td>
                            <td><?= $p['address'] ?></td>
                            <td class="text-center">
                              <!-- <a href="category.php?c_id=" data-bs-toggle="modal" data-bs-target="#courseEditModal" class="btn btn-sm btn-primary me-2"><i class="fa-regular fa-pen-to-square"></i></a> -->

                              <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['u_id'] ?>)"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                          </tr>
<?php
      }
    }
  } catch (PDOException $e) {
    echo $e;
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customers</title>
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
        text: "Do you really want to delete these row?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect or AJAX call
          window.location.href = "delete_category.php?u_id=" + id;
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
        $page_title = "Customer page";
        include_once('header.php');
        ?>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <div class="row">
          <div class="col-12">
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-color-2">Manage Customers</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">

                  <!-- Date Range Button -->
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="input-group flex-nowrap">
                      <span style="border:none;" class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                      <input style="border:none;" type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search Customers" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                  </div>
                  <!-- Reports Button -->
                  <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#courseCreateModal" class="cursor-pointer ms-4 bg-white bg-primary text-white d-flex align-items-center px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">
                    <i class="fa-solid fa-plus me-3"></i>
                    Add Category
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
                      
                      <th>Customer Name</th>
                      <th>E-Mail</th>
                      <th>Phone</th>
                      <th>Address</th>
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

                      //Load customer Data
                      $q = "SELECT * FROM ep_users WHERE role = 'customer' LIMIT $offset,$limit ";
                      $res = $conn->prepare($q);
                      if ($res->execute()) {
                        $customers = $res->fetchAll(PDO::FETCH_ASSOC);
                        $id = $offset; //print sequential numbers
                        foreach ($customers as $p) {
                          $id++;
                    ?>

                          <tr>
                            <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                            <td><?= $id ?></td>
                            <td><img src="../ePharma-master/uploads/<?= $p['image'] ?>" width="50px" height="50px" alt="image" style="margin-right: 10px;">
                            <?= $p['name'] ?></td>
                            <td><?= $p['email'] ?></td>
                            <td><?= $p['mobile'] ?></td>
                            <td><?= $p['address'] ?></td>
                            <td class="text-center">
                              <!-- <a href="category.php?c_id=" data-bs-toggle="modal" data-bs-target="#courseEditModal" class="btn btn-sm btn-primary me-2"><i class="fa-regular fa-pen-to-square"></i></a> -->

                              <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['u_id'] ?>)"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                          </tr>
                    <?php
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
                    $q = $conn->prepare("SELECT * FROM $table WHERE role = 'customer'");
                    $q->execute();

                    $count = $q->rowCount();
                    if ($count > 0) {
                      $pages = ceil($count / $limit);  //find total pages of all records per limit
                    ?>
                      <li class="page-item">
                        <?php if ($page > 1) { ?>
                          <a class="page-link" href="customers.php?page=<?= $page - 1 ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                        <?php } ?>
                      </li>
                      <?php
                      for ($i = 1; $i <= $pages; $i++) {
                      ?>
                        <li class="page-item"><a class="page-link" href="customers.php?page=<?= $i ?>"><?php echo $i; ?></a></li>
                      <?php
                      } ?>
                      <li class="page-item">
                        <?php if ($page < $pages) { ?>
                          <a class="page-link" href="customers.php?page=<?= $page + 1 ?>" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
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
              <!-- <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="fas fa-ellipsis-h"></i></a></li>
                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                    <li class="page-item"><a class="page-link" href="#">7</a></li>
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                    </li>
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
                    <span>OF 100</span>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php
      include('footer.php');
      ?>
    </div>
    <script>
      //searching customer
      $(document).ready(function() {

        function load_customer() {

          var search_cust = $("#livesearch").val();

          // alert(search_cust);
          $.ajax({
            url: window.location.href,
            method: "POST",
            data: {
              search_cust: search_cust
            },

            success: function(data) {
              $("#result").html(data).show();
            }
          });

        }

        $(document).on("keyup", "#livesearch", function() {
          load_customer();
        });

      });
    </script>