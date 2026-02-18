<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$table = "ep_products";

//Add New Products
if (isset($_POST['submit'])) {

  //collect user input
  $pname = $_POST['pname'];
  $desc = $_POST['desc'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $category = $_POST['category'];
  $edate = $_POST['edate'];
  $status = $_POST['status'];


  //image upload
  $pimg = $_FILES['pimg']['name'];
  $tempimg = $_FILES['pimg']['tmp_name'];
  $ext = pathinfo($pimg, PATHINFO_EXTENSION);
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];
  $img_name = "product_" . time() . "." . $ext;

  $target = "upload/" . basename($img_name);
  //move_uploaded_file($tempimg,$target);
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];
  if (!in_array(strtolower($ext), $allowed)) {
    $message[] = "Invalid image format";
  }


  if (!move_uploaded_file($tempimg, $target)) {
    $message[] = "Image Fails";
  }

  //expiry date validation
  $today = date('Y-m-d');
  if ($edate < $today) {
    echo "<script>alert('Date must be Greater than today!!!!')</script>";
  }

  //check required fields
  if (
    empty($pname) || empty($desc) || empty($price) ||
    empty($stock) || empty($category) || empty($edate) || empty($pimg)
  ) {
    $message[] = "Please Fill required fields!!!";
  } else {
    try {

      $query = insert($table, [
        "name" => $pname,
        "description" => $desc,
        "stock" => $stock,
        "price" => $price,
        "image" => $img_name,
        "c_id" => $category,
        "expiry_date" => $edate,
        "status" => $status
      ]);


      if ($query) {
        echo "<script>alert('Product Added Succefully!');</script>";
        $message[] = "Product Added Successfully";
      } else {
        $message[] = "Product Not Added!!!";
      }
    } catch (PDOException $e) {
      echo "Error:$e";
    }
  }
}

//pagination
$total_page = $conn->prepare("SELECT * FROM $table WHERE 1");
$total_page->execute();





//update products
if (isset($_POST['edit'])) {
  $id = $_POST['p_id'];
  //collect user input
  $pname = $_POST['pname'];
  $desc = $_POST['desc'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $category = $_POST['category'];
  $edate = $_POST['edate'];
  $status = $_POST['status'];

  $data = [
    "name" => $pname,
    "description" => $desc,
    "stock" => $stock,
    "price" => $price,
    "c_id" => $category,
    "expiry_date" => $edate,
    "status" => $status
  ];
  //check image
  if (!empty($_FILES['pimg']['name'])) {

    $pimg = $_FILES['pimg']['name'];
    $tempimg = $_FILES['pimg']['tmp_name'];
    $ext = pathinfo($pimg, PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $img_name = "product_" . time() . "." . $ext;

    $target = "upload/" . basename($img_name);
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array(strtolower($ext), $allowed)) {
      $message[] = "Invalid image format";
    }
    move_uploaded_file($img_name, $target);
    
  }



  //  if(!empty($_FILES['pimg']['name'])){
  //   //new image upload
  //   $pimg = $_FILES['pimg']['name'];
  //   $tempimg = $_FILES['pimg']['tmp_name'];
  //   $ext = pathinfo($pimg , PATHINFO_EXTENSION);



  //   $allowed = ['jpg','jpeg','png','webp'];
  //   if(in_array(strtolower($ext), $allowed)){
  //       $img_name = "editproduct_".time().".".$ext;
  //       $target = "upload/".basename($pimg);
  //       move_uploaded_file($tempimg,$target);
  //       $data["image"]= $pimg;
  //   } 
  //  }


  $condition = "p_id = $id";
  $query = update_record($table, $data, $condition);
  if (!$query) {
    echo "Updation Failed!!!";
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products</title>
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
                <h3 class="mb-2 text-size-26 text-color-2">Products</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">
                  <!-- Date Range Button -->

                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- <i class="fa-solid fa-filter me-3"></i>
                                      Filter by
                                    <i class="fa-solid fa-chevron-right ms-3 text-size-sm"></i>
                                    <ul class="dropdown-menu">
                                      <li><a class="dropdown-item"  href="#">Active</a></li>
                                      <li><a class="dropdown-item" href="#">Inactive</a></li>
                                   </ul> -->
                    <select class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26" name="filter" id="">
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
          <?php
          // if(isset($_POST['search'])){
          //   $search = $_POST['search'];

          // $q = $conn->prepare("SELECT * FROM ep_products WHERE name = :search");
          // $q->execute(['search'=>$search]);
          //   $fetch1 = $q->fetchAll(PDO::FETCH_ASSOC);
          //   foreach($fetch1 as $row){

          //   }
          // }
          ?>
          <div class="col-6">
            <div class="d-flex align-items-center">

              <div class="d-none d-md-block d-lg-block">
                <form action="test1.php" method="post">
                  <div class="input-group flex-nowrap">
                    <span class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                    <input type="text" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search Product Name" aria-label="Username" aria-describedby="addon-wrapping">

                    <!-- Small Search Button -->
                    <button name="searchbtn" type="submit" class="btn btn-sm btn-outline-secondary ms-2">
                      <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-4">
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
                  <tbody>

                    <?php



                    //pagination - display products accordingly
                    $limit = 5;
                    if (isset($_GET['page'])) {
                      //if you click on pagination button - product.php?page = 2
                      $page = $_GET['page'];
                    } else {
                      //default it redirect first button(Diplay first 5 records)
                      $page = 1;
                    }
                    $offset = ($page - 1) * $limit;
                    //Search by customer
                    if (isset($_POST['searchbtn'])) {
                      $search = $_POST['search'];

                      $res = $conn->prepare("SELECT * FROM ep_products WHERE name LIKE %$search%");
                      $res->execute();
                    } else {
                      $q = "SELECT p.p_id, c.category_name, p.name, p.description,
                                  p.stock, p.price, p.image, p.expiry_date, p.status
                                  FROM ep_products p
                                  JOIN ep_category c ON p.c_id = c.c_id
                                  
                                  LIMIT {$offset} , {$limit}
                                  ";
                      $res = $conn->prepare($q);
                      $res->execute();
                    }

                    $products = $res->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($products as $p) {
                    ?>
                      <tr>
                        <td><input type="checkbox" class="custom-checkbox row-checkbox"></td>
                        <td>
                          <div class="d-flex justify-content-start align-items-center">
                            <img src="./upload/<?= $p['image'] ?>" class="tbl-img" alt="img">
                            <span class="ms-2"><?= $p['name'] ?></span>
                          </div>
                        </td>
                        <!-- <td><img class="tbl-img"  src="upload/<?= $p['image'] ?>" alt=""></td> -->
                        <td><?= $p['description'] ?></td>
                        <td>₹<?= $p['price'] ?></td>
                        <td><?= $p['stock'] ?></td>
                        <td>
                          <?= $p['category_name']  ?>
                        </td>
                        <td><?= $p['expiry_date'] ?></td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td class="text-center">

                          <!-- <a href="edit.php?p_id=<?= $p['p_id'] ?>" name="update" data-bs-toggle="modal" data-bs-target="#EditModal" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"><i class="fa-regular fa-pen-to-square"></i></a> -->

                          <button type="button"
                            class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"
                            data-bs-toggle="modal"
                            data-bs-target="#EditModal"

                            data-id="<?= $p['p_id'] ?>"
                            data-name="<?= htmlspecialchars($p['name']) ?>"
                            data-desc="<?= htmlspecialchars($p['description']) ?>"
                            data-price="<?= $p['price'] ?>"
                            data-stock="<?= $p['stock'] ?>"
                            data-expiry="<?= $p['expiry_date'] ?>"
                            data-status="<?= $p['status'] ?>"
                            data-category="<?= $p['category_name']  ?>"
                            data-img="<?= $p['image'] ?>">
                            <i class="fa-regular fa-pen-to-square"></i>
                          </button>

                          <!-- <a href="edit.php?p_id=<?= $p['p_id'] ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"><i class="fa-regular fa-pen-to-square"></i></a> -->
                          <a href="delete_product.php?p_id=<?= $p['p_id'] ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></a>

                        </td>
                      </tr>
                    <?php
                    }


                    ?>

                  </tbody>
                </table>
              </div>

              <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <ul class="pagination">
                    <?php
                    $q = $conn->prepare("SELECT p.p_id, c.category_name, p.name, p.description,
                                        p.stock, p.price, p.image, p.expiry_date, p.status
                                        FROM ep_products p
                                        JOIN ep_category c ON p.c_id = c.c_id
                                        ORDER BY p.price DESC");
                    $q->execute();
                    $count = $q->fetchColumn();
                    // echo $count;
                    if ($count > 0) {
                      $pages = ceil($count / $limit);  //find total pages of all records per limit
                    ?>
                      <li class="page-item">
                        <?php if ($page > 1) { ?>
                          <a class="page-link" href="products.php?page=<?= $page - 1 ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                        <?php } ?>
                      </li>
                      <?php
                      for ($i = 1; $i <= $pages; $i++) {
                      ?>
                        <li class="page-item"><a class="page-link" href="products.php?page=<?= $i ?>"><?php echo $i; ?></a></li>
                      <?php
                      } ?>
                      <li class="page-item">
                        <?php if ($page < $pages) { ?>
                          <a class="page-link" href="products.php?page=<?= $page + 1 ?>" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                        <?php } ?>
                      </li>

                    <?php } ?>
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
              <div class="col-12">
                <label for="Product_desc" class="form-label text-color-2 text-normal">Description</label>
                <input type="text" name="desc" class="form-control" id="Product_desc" placeholder="Enter Description">
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
              <div class="col-12">
                <label for="Product_desc" class="form-label text-color-2 text-normal">Description</label>
                <input type="text" name="desc" class="form-control" id="edit_desc" placeholder="Enter Description">
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
                <label for="edit_category" class="form-label text-color-2 text-normal">Category</label>
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
        document.getElementById('edit_img').value = btn.getAttribute('data-img');

      });
    </script>


    <!-- Scripts -->
    <script src="./assets/js/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/plugin/chart/chart.js"></script>
    <script src="./assets/plugin/quill/quill.js"></script>
    <script src="./assets/js/chart.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>

</html>