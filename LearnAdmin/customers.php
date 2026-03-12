<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$table = "ep_users";

if (isset($_POST['page'])) {

  $search = $_POST['search'] ?? '';

  $limit = 5;
  $page = $_POST['page'];
  $offset = ($page - 1) * $limit;

  $q = $conn->prepare("SELECT * FROM ep_users WHERE role = 'customer' AND (name LIKE :search OR email LIKE :search OR address LIKE :search) LIMIT $offset , $limit");
  $q->execute([
    'search' => '%' . $search . '%'
  ]);
  $data = $q->fetchAll(PDO::FETCH_ASSOC);
  $id = $offset;
  foreach ($data as $p) {
    $id++;
?>
    <tr>
      <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
      <td>
        <?= $id ?>
      </td>
      <td><img src="../ePharma-master/uploads/<?= $p['image'] ?>" width="50px" height="50px" alt="image" style="margin-right: 10px;">
        <?= $p['name'] ?></td>
      <td><?= $p['email'] ?></td>
      <td><?= $p['mobile'] ?></td>
      <td><?= $p['address'] ?></td>
      <td class="text-center">
        <!-- <a href="category.php?c_id=" data-bs-toggle="modal" data-bs-target="#courseEditModal" class="btn btn-sm btn-primary me-2"><i class="fa-regular fa-pen-to-square"></i></a> -->

        <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['u_id'] ?>,'delete_category.php?u_id=')"><i class="fa-solid fa-trash-can"></i></button>
      </td>
    </tr>
<?php

  }
  // Split pagination content
  echo "###pagination###";

  // Pagination
  $count_stmt = $conn->prepare("SELECT * FROM ep_users WHERE role = 'customer' AND (name LIKE :search OR email LIKE :search OR address LIKE :search)");
  $count_stmt->execute([
    'search' => $search . '%'
  ]);
  $total =  $count_stmt->rowCount();
  $pages = ceil($total / $limit);

  if ($pages > 0) {
    for ($i = 1; $i <= $pages; $i++) {
      echo "<li class='page-item'>
              <a href='#' class='page-link' data-page='$i'>$i</a>
              </li>";
    }
  }
  exit;
}



$page_title = "Customer page";
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
        include_once('header.php');
        ?>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <div class="row">
          <div class="col-12">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">customers </li>
              </ol>
            </nav>

            <div class="category-header d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-color-2">Manage Customers</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">

                  <!-- Date Range Button -->
                  <div class="search-wrapper cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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

                    <!-- <tr>
                            <td><input type="checkbox" class="custom-checkbox row-checkbox"></td>
                            <td><?= $id ?></td>
                            <td><img src="../ePharma-master/uploads/<?= $p['image'] ?>" width="50px" height="50px" alt="image" style="margin-right: 10px;">
                            <?= $p['name'] ?></td>
                            <td><?= $p['email'] ?></td>
                            <td><?= $p['mobile'] ?></td>
                            <td><?= $p['address'] ?></td>
                            <td class="text-center">
                              <a href="category.php?c_id=" data-bs-toggle="modal" data-bs-target="#courseEditModal" class="btn btn-sm btn-primary me-2"><i class="fa-regular fa-pen-to-square"></i></a>

                              <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['u_id'] ?>)"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                          </tr> -->

                  </tbody>
                </table>
              </div>
              <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <ul class="pagination" id="pagination">

                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php
      include('footer.php');
      ?>
    </div>
    <script src="pagi.js"></script>