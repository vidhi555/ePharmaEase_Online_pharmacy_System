<?php
require_once('db.php');
require('crud.php');

$cid = $_GET['c_id'];

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$table = "ep_category";
if (isset($_POST['page'])) {

  $search = $_POST['search'] ?? '';

  $limit = 5;
  $page = $_POST['page'];
  $offset = ($page - 1) * $limit;

  $q = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE c.c_id = :cid AND category_name LIKE :search LIMIT $offset , $limit");
  $q->execute([
    'cid' => $cid,
    'search' => $search . '%'
  ]);
  $data = $q->fetchAll(PDO::FETCH_ASSOC);

  foreach ($data as $p) {
?>
    <tr>
      <td><img src="All_images_uploads/<?= $p['image'] ?>" alt="p_img" width="90px" height="90px">
        <?= $p['name'] ?></td>
      <td><?= substr($p['description'], 0, 50) ?>...</td>
      <td><?= $p['price'] ?></td>
      <td><?= $p['stock'] ?></td>
      <td><?= $p['category_name'] ?></td>
      <td><?= date('d/m/Y', strtotime($p['expiry_date'])) ?></td>
      <td><?= $p['status'] ?></td>
      <td class="text-center">
        <a class="btn btn-sm btn-warning mb-2 mb-lg-0 me-0 me-lg-2" href="view_category_page.php?c_id=<?= $p['c_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>
        <button type="button"
          data-bs-toggle="modal"
          data-bs-target="#categoryEditModal"
          class="btn btn-sm btn-primary me-2"
          data-cid="<?= $p['c_id'] ?>"
          data-cname="<?= htmlspecialchars($p['category_name']) ?>"
          data-cdesc="<?= htmlspecialchars($p['description']) ?>"
          data-cimg="<?= $p['cat_image'] ?>"><i class="fa-regular fa-pen-to-square"></i></button>
        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $p['c_id'] ?>)"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
    <?php
  }
  exit;
}



if (isset($_POST['search_cat'])) {
  $search_cat = $_POST['search_cat'];
  // echo $search_cat;
  // die();
  try {
    // $q = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE c.c_id = :cid LIMIT {$offset} , {$limit}");
    $sql = "SELECT * FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id WHERE c.c_id = :cid AND category_name LIKE '{$search_cat}%' LIMIT 5";
    $res = $conn->prepare($sql);
    if ($res->execute(['cid' => $cid])) {
      $products = $res->fetchAll(PDO::FETCH_ASSOC);
      foreach ($products as $p) {
    ?>

        <tr>
          <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
          <td>
            <div class="d-flex justify-content-start align-items-center">
              <img src="./All_images_uploads/<?= $p['image'] ?>" class="tbl-img" alt="img">
              <span class="ms-2"><?= ucfirst($p['name']) ?></span>
            </div>
          </td>
          <!-- <td><img class="tbl-img"  src="All_images_uploads/<?= $p['image'] ?>" alt=""></td> -->
          <td style="width: 1000px;word-wrap: break-word;white-space: normal;"><?= substr($p['description'], 0, 50); ?>...</td>
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



          </td>
        </tr>
      <?php
      }
    }
    if (!$products) {
      echo "<tr><td colspan='4' class='text-center'> Category Not found!!</td></tr>";
    }
  } catch (PDOException $e) {
    echo $e;
  }
  exit;
}

// =======================================
try {
  if (isset($_GET['c_id'])) {
    $query = $conn->prepare("SELECT * FROM ep_category WHERE c_id = :cid");
    $query->execute(['cid' => $cid]);
    $fetch_category = $query->fetch(PDO::FETCH_ASSOC);
    if ($fetch_category) {
      $cat_name = $fetch_category['category_name'];
      ?>


      <?php
      $page_title = "Category wise Products";
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
          include_once('sidebar.php');
          ?>
          <!-- Content Wrapper -->
          <div class="content-wrapper">
            <!-- Header -->
            <div class="header d-flex align-items-center justify-content-between">
              <?php
              require_once('header.php');
              ?>
            </div>
            <!-- Main Content -->
            <div class="main-content">
              <div class="row">
                <div class="col-12">
                  <nav>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                      <li class="breadcrumb-item active">Category</li>
                    </ol>
                  </nav>

                  <div class="category-header d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                    <div class="flex-grow-1">
                      <h3 class="mb-2 text-color-2 text-secondary"><i class="fa-solid fa-briefcase-medical"></i> <?= ucfirst($cat_name) ?> Products</h3>
                    </div>
                    <div class="mt-3 mt-lg-0">
                      <div class="d-flex align-items-center">

                        <!-- Date Range Button -->
                        <div class="search-wrapper cursor-pointer d-flex align-items-center text-color-1 px-3 py-2 text-normal fw-bolder letter-spacing-26">
                          <div class="input-group flex-nowrap">
                            <span style="border:none;" class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                            <input style="border:none;" type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search <?= $cat_name ?> Products.." aria-label="Username" aria-describedby="addon-wrapping">
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

                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody id="result">

                        </tbody>
                      </table>
                    </div>

                    <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                      <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                        <ul class="pagination" id="pagination">


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
                    <span>OF 100</span>
                  </div>
                </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
              function confirmDelete(id) {
                Swal.fire({
                  title: 'Are you sure?',
                  text: "Do you really want to delete these Category?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6'
                }).then((result) => {
                  if (result.isConfirmed) {
                    // Redirect or AJAX call
                    window.location.href = "delete_category.php?c_id=" + id;
                  }
                });
              }
            </script>
            <!-- Footer -->
            <?php
            include('footer.php');
            ?>
          </div>


          <script src="pagi.js"></script>
    <?php
    }
  }
} catch (PDOException $e) {
  header("Location:500.php");
}
    ?>