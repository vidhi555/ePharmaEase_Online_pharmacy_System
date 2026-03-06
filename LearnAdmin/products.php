<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$table = "ep_products";

// Search + Status Filter (AJAX)
// Search + Status Filter (AJAX ONLY)
// if (
//   (isset($_POST['input']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']))
//   || (isset($_POST['filter_status']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']))
// ) {
if (isset($_POST['page'])) {

  $input  = $_POST['search'] ?? '';
  $fstatus = $_POST['filter_status'] ?? '';

  $limit = 5;
  $page = $_POST['page'];
  $offset = ($page - 1) * $limit;

  // echo $status;
  // die();
  $where = "WHERE 1 ";

  if ($input != '') {
    $where .= " AND (name LIKE :input OR category_name LIKE :input) ";
  }

  if ($fstatus != '') {
    // map dropdown values to DB values

    $where .= " AND (status = :sttus) ";
  }
  $q = $conn->prepare("SELECT p.* , c.category_name FROM ep_products p JOIN ep_category c ON p.c_id = c.c_id $where LIMIT $offset , $limit");
  if ($input != '') {
    $q->bindValue(':input', $input . '%');
  }
  if ($fstatus != '') {
    $q->bindValue(':sttus', $fstatus);
  }
  $q->execute();
  $data = $q->fetchAll(PDO::FETCH_ASSOC);



  foreach ($data as $p) {
    // // getimages
    // $get_image  = $conn->prepare("SELECT * FROM ep_image_gallery WHERE p_id = :pid");
    // $get_image->execute(['pid' => $p['p_id']]);
    // $fetch_image = $get_image->fetchAll(PDO::FETCH_ASSOC);
    // foreach ($fetch_image as $fi) {
    //   echo $fi['image_name'];
    //   die();
    // }
    
?>
    <tr class="text-center">
      <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
      <td>
        <div class="d-flex justify-content-start align-items-center">
          <img src="./All_images_uploads/<?= $p['image'] ?>" class="tbl-img" alt="img">
          <span class="ms-2"><?= ucfirst($p['name']) ?></span>
        </div>
      </td>
      <!-- <td><img class="tbl-img"  src="All_images_uploads/<?= $p['image'] ?>" alt=""></td> -->
      <td style="width: 1000px;word-wrap: break-word;white-space: normal;"><?= substr($p['description'], 0, 90); ?>...</td>
      <td>$<?= $p['price'] ?></td>
      <td><?php
          if ($p['stock'] <= 5) {
            echo "<span id='badge_stock' class='badge bg-danger'>" . $p['stock'] . "</span>";
          } elseif ($p['stock'] <= 10) {
            echo "<span class='badge bg-warning'>" . $p['stock'] . "</span>";
          } else {
            echo $p['stock'];
          }
          ?></td>
      <td>
        <?= $p['category_name']  ?>
      </td>
      <td><?= date("d/m/Y", strtotime($p['expiry_date'])) ?></td>
      <td><?= $p['status'] == "Active" ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-warning'>In-active</span>" ?></td>
      <td class="text-center">

        <!-- <a href="edit.php?p_id=<?= $p['p_id'] ?>" name="update" data-bs-toggle="modal" data-bs-target="#EditModal" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"><i class="fa-regular fa-pen-to-square"></i></a> -->
        <a class="btn btn-sm btn-warning mb-2 mb-lg-0 me-0 me-lg-2" href="view_product_detail.php?p_id=<?= $p['p_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>

        
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
          data-category="<?= $p['c_id']  ?>"
          data-img="<?= $p['image'] ?>">
          <i class="fa-regular fa-pen-to-square"></i>
        </button>
        <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['p_id'] ?>,'delete_product.php?p_id=')"><i class="fa-solid fa-trash-can"></i></button>
        <!-- <a href="delete_product.php?p_id=<?= $p['p_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash-can"></i></a> -->

      </td>
    </tr>
<?php
  }
  exit;
}
// ==============================================================================
$errors = [];

//Add New Products
if (isset($_POST['submit'])) {

  //collect user input
  $pname = $_POST['pname'];
  $desc = $_POST['desc'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $category = $_POST['category'] ?? '';
  $edate = $_POST['edate'];
  $status = $_POST['status'];


  //image upload
  // $pimg = $_FILES['pimg']['name'];
  // $tempimg = $_FILES['pimg']['tmp_name'];
  // $ext = pathinfo($pimg, PATHINFO_EXTENSION);
  // $allowed = ['jpg', 'jpeg', 'png', 'webp'];
  // $img_name = "product_" . time() . "." . $ext;

  // $target = "All_images_uploads/" . basename($img_name);
  // //move_uploaded_file($tempimg,$target);
  // if (!in_array(strtolower($ext), $allowed)) {
  //   $errors[] = "Invalid image format!!";
  //   // sweetAlert("Warning!", "Invalid image format!!", "warning");
  //   // exit;
  // }

  // if (!move_uploaded_file($tempimg, $target)) {
  //   $errors[] = "Image upload failed!!!";
  //   // sweetAlert("Warning!", "Image upload failed!!", "warning");
  //   // exit;
  // }



  //expiry date validation
  $today = date('Y-m-d');
  if ($edate < $today) {
    $errors[] = "Date Must be Greater than Today!!";
    // sweetAlert("Warning!", "Date Must be Greater than Today!!", "warning");
    // exit;
  }

  //Stock validation
  if ($stock <= 0) {
    $errors[] = "Stock Must be Greater than 0!!";
  }

  //check required fields
  if (
    empty($pname) || empty($desc) || empty($price) ||
    empty($stock) || empty($category) || empty($edate) || empty($status)
  ) {
    $errors[] = "Please Fill required fields!!!!";
    // sweetAlert("Warning!", "Please Fill required fields!!!", "warning");
  }
  try {
    if (!empty($errors)) {
      sweetAlert("Error", "Please Try Again!", "error");
    } else {
      $query = insert($table, [
        "name" => $pname,
        "description" => $desc,
        "stock" => $stock,
        "price" => $price,

        "c_id" => $category,
        "expiry_date" => $edate,
        "status" => $status
      ]);
      // sweetAlert("Success!", "Product Added Successfully", "success");


      // $last_inserted_pid = $conn->lastInsertId();


      // $number = $last_inserted_pid;
      // $num = 0;
      // // echo $number;
      // // die();
      // foreach ($_FILES['pimg']['name'] as $key => $val) {
      //   $num++;

      //   if (!empty($_FILES['pimg']['name'][$key])) {
      //     $pimg = $_FILES['pimg']['name'][$key];
      //     $tempimg = $_FILES['pimg']['tmp_name'][$key];

      //     $ext = pathinfo($pimg, PATHINFO_EXTENSION);
      //     $allowed = ['jpg', 'jpeg', 'png', 'webp'];
      //     $img_name = "products_" . $number . "_" . $num . "." . $ext;

      //     $target = "All_images_uploads/" . basename($img_name);
      //     if (!move_uploaded_file($tempimg, $target)) {
      //       $errors[] = "Image upload failed!!!";
      //     }
      //     // move_uploaded_file($tempimg,$target);
      //     if (!in_array(strtolower($ext), $allowed)) {
      //       $errors[] = "Invalid image format!!";
      //       // sweetAlert("Warning!", "Invalid image format!!", "warning");
      //       // exit;
      //     }
      //     if ($pimg >= 2 * 1024 * 1024) {
      //       $errors[] = "Image Size is too large";
      //     }
      //     if (!empty($errors)) {
      //       sweetAlert("Image has Error!", "Please check!And try Again!", "warning");
      //     } else {
      //       $insert_image = $conn->prepare("INSERT INTO ep_image_gallery(`image_name`,`p_id`) VALUES (:img_name, :pid)");
      //       $insert_image->execute([
      //         'img_name' => $img_name,
      //         'pid' => $last_inserted_pid
      //       ]);
      //       sweetAlert("Success!", "Product Added Successfully", "success");
      //     }
      //   }
      // }
      $last_inserted_pid = $conn->lastInsertId();

$num = 0;

foreach ($_FILES['pimg']['name'] as $key => $val) {

    if (!empty($_FILES['pimg']['name'][$key])) {

        $num++;

        $pimg = $_FILES['pimg']['name'][$key];
        $tempimg = $_FILES['pimg']['tmp_name'][$key];
        $size = $_FILES['pimg']['size'][$key];

        $ext = strtolower(pathinfo($pimg, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        //extension validation
        if (!in_array($ext, $allowed)) {
            sweetAlert("Warning!", "Invalid image format!", "warning");
            continue;
        }

        //size validation 
        if ($size > 2 * 1024 * 1024) {
            sweetAlert("Warning!", "Image size must be less than 2MB!", "warning");
            continue;
        }

        $img_name = "products_" . $last_inserted_pid . "_" . $num . "." . $ext;

        $target = "All_images_uploads/" . $img_name;

        if (move_uploaded_file($tempimg, $target)) {

         // FIRST IMAGE → MAIN IMAGE
            if($num == 1){

                $conn->prepare("
                UPDATE ep_products 
                SET image=:img 
                WHERE p_id=:pid
                ")->execute([
                    'img'=>$img_name,
                    'pid'=>$last_inserted_pid
                ]);

            }
            $insert_image = $conn->prepare("
                INSERT INTO ep_image_gallery(image_name,p_id)
                VALUES(:img_name,:pid)
            ");

            $insert_image->execute([
                'img_name' => $img_name,
                'pid' => $last_inserted_pid
            ]);

        } else {
            sweetAlert("Error!", "Image upload failed!", "error");
        }

    }
}

sweetAlert("Success!", "Product Added Successfully", "success");
    }
  } catch (PDOException $e) {
    sweetAlert("Error!", "$e", "error");
  }
}

require_once('edit.php');

$page_title = "Products";
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
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Product</li>
              </ol>
            </nav>
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-size-26 text-color-2"><i class="fas fa-pills"></i> Latest Products</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle" style="margin-left: 20px;margin-right: 5px;" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="input-group " style="border:none;">
                      <span style="border:none;" class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                      <input style="border:none;" type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search Products" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                  </div>
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle">
                    <i class="fa-solid fa-filter"></i>
                    <select id="filterbystatus" class="form-select text-size-sm" style="border: none;">
                      <option value="" selected="selected" disabled>Filter By</option>
                      <option value="Active">Active</option>
                      <option value="In-Active">Inactive</option>
                    </select>
                  </div>

                  <!-- Reports Button -->
                  <a href="#" id="add_prod" data-bs-toggle="modal" data-bs-target="#CreateModal" class="cursor-pointer ms-4 bg-white bg-primary text-white d-flex align-items-center px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">
                    <i class="fa-solid fa-plus me-3"></i>
                    Add Products
                  </a>
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
                    <tr class="text-center">
                      <!-- <th><input type="checkbox" id="select-all" class="custom-checkbox"></th> -->

                      <th>Product Name</th>
                      <th>Description</th>
                      <th>Price</th>
                      <th>Stock</th>
                      <th>Category</th>
                      <th>Expiry Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="result">


                    <!-- <button type="button"
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
                              data-category="<?= $p['c_id']  ?>"
                              data-img="<?= $p['image'] ?>">
                              <i class="fa-regular fa-pen-to-square"></i>
                            </button> -->

                  </tbody>
                </table>
              </div>

              <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <!-- <ul class="pagination" id="pagination">
                   
                  </ul> -->
                  <ul class="pagination">
                    <?php
                    $s = $_POST['search'] ?? '';
                    createPagination("ep_products", "name", $s, 5);
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

    <!--Create  Modal -->
    <div class="modal fade" id="CreateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">

          <div class="modal-body p-4 position-relative">
            <!-- Show Errors -->
            <?php if (!empty($errors)) { ?>
              <div class="alert alert-danger">
                <?php foreach ($errors as $er) { ?>
                  <ul>
                    <li style="margin: 0 10px;color: darkred;text-transform: capitalize;"><?= $er ?></li>
                  </ul>
                <?php } ?>
              </div>
            <?php  }
            ?>
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
                <input type="number" name="price" step="0.01" min="0.00" class="form-control" id="price" placeholder="Enter Price">
              </div>
              <div class="col-6">
                <label for="stock" class="form-label text-color-2 text-normal">Stock</label>
                <input type="number" name="stock" min="0" class="form-control" id="stock" placeholder="Enter Stock">
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
                <input type="file" name="pimg[]" multiple class="form-control">
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
            <?php if (!empty($error)) { ?>
              <div class="alert alert-danger" style="margin: 0 10px;">
                <ul>
                  <?php foreach ($error as $er) { ?>
                    <li><?= $er ?></li>
                  <?php } ?>
                </ul>
              </div>
            <?php } ?>
            <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            <h2 class="h5 text-color-2 py-2">Edit Product</h2>

            <form class="row g-3" method="post" enctype="multipart/form-data">
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
                <input type="number" name="price" step="0.01" min="0.00" class="form-control" id="edit_price" placeholder="Enter Price">
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
              <input type="hidden" name="old_img" id="old_image" class="form-control">
              <div class="col-12">
                <label class="form-label text-color-2 text-normal">Product Image</label>
                <?php 
                  //  $get_image  = $conn->prepare("SELECT * FROM ep_image_gallery WHERE p_id = :pid");
                  //   $get_image->execute(['pid' => $p['p_id']]);
                  //   $fetch_image = $get_image->fetchAll(PDO::FETCH_ASSOC);
                  //   foreach ($fetch_image as $fi) {
                  //     echo $fi['image_name'];
                  //     die();
                  //   }
                ?>
                <img src="" alt="product" id="edit_img_prev" style="height: 100px;width: 100px;border: 1px solid lightblue;">
                <input type="file" name="pimg[]" class="form-control" multiple>

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
        let old_img = btn.getAttribute('data-img');
        // alert(old_img);
        document.getElementById('edit_p_id').value = btn.getAttribute('data-id');
        document.getElementById('edit_pname').value = btn.getAttribute('data-name');
        document.getElementById('edit_desc').value = btn.getAttribute('data-desc');
        document.getElementById('edit_price').value = btn.getAttribute('data-price');
        document.getElementById('edit_stock').value = btn.getAttribute('data-stock');
        document.getElementById('edit_edate').value = btn.getAttribute('data-expiry');
        document.getElementById('edit_status').value = btn.getAttribute('data-status');
        document.getElementById('old_image').value = btn.getAttribute('data-img');
        document.getElementById('edit_category').value = btn.getAttribute('data-category');

        // set image
        document.getElementById('edit_img_prev').src = "All_images_uploads/" + old_img;

      });
    </script>
    <script>
      function loadData(page = 1) {
        let search = document.getElementById("livesearch").value;
        var filter_status = document.getElementById("filterbystatus").value;

        $.ajax({
          url: window.location.href,
          type: "POST",
          data: {
            page: page,
            search: search,
            filter_status: filter_status
          },
          success: function(data) {
            $("#result").html(data);
          }
        });
      }

      function loadPagination() {
        $.ajax({
          url: "pagination_category.php",
          success: function(data) {
            $("#pagination").html(data);
          }
        });
      }

      // click pagination
      $(document).on("click", ".page-btn", function(e) {
        e.preventDefault();
        var page = $(this).data("page");
        loadData(page);
      });
      $(document).ready(function() {
        $(document).on("keyup", "#livesearch", function() {
          var a = $(this).val();
          // alert(a);
          loadData(1);
          loadPagination();
        });
      });
      $(document).on("change", "#filterbystatus", function() {
        var b = $(this).val();
        // alert(b);
        loadData(1);
        loadPagination();
      });

      // first load
      loadData();
      loadPagination();
    </script>