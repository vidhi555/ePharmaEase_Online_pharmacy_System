<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

// pagination
if (isset($_POST['page'])) {
  $page = $_POST['page'];
  $limit = 5;
  $offset = ($page - 1) * $limit;

  $query = $conn->prepare("SELECT * FROM ep_message LIMIT $offset,$limit");
  $query->execute();
  $fetch_msg = $query->fetchAll(PDO::FETCH_ASSOC);
  $id = $offset;
  foreach ($fetch_msg as $msg) {
    $id++;

?>
    <tr>
      <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->

      <td><?= $id ?></td>
      <td><?= $msg['name'] ?></td>
      <td><?= $msg['email'] ?></td>
      <td style="text-wrap: wordwrap;"><?= $msg['subject'] ?></td>

      <!-- <td><span class="badge bg-success">Active</span></td> -->
      <td class="text-center">
        <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#EditModal" class="btn btn-sm btn-primary mb-2"><i class="fa-regular fa-pen-to-square"></i></a> -->
        <a class="btn btn-sm btn-warning mb-2 mb-lg-0 me-0 me-lg-2" href="view_mesage.php?msg_id=<?= $msg['msg_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>

        <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $msg['msg_id'] ?>,'delete_message.php?msg_id=')"><i class="fa-solid fa-trash-can"></i></button>

      </td>
    </tr>

<?php
  }
  exit();
}

$page_title = "Customer Message";
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
                <li class="breadcrumb-item active">Message</li>
              </ol>
            </nav>
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-size-26 text-color-2">Customer Inquiries</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">
                  <!-- Date Range Button -->
                  <!-- <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-filter me-3"></i>
                    Filter by
                    <i class="fa-solid fa-chevron-right ms-3 text-size-sm"></i>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Active</a></li>
                      <li><a class="dropdown-item" href="#">Inactive</a></li>
                    </ul>
                  </div> -->
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
                      <th>User Name</th>
                      <th>User Email</th>
                      <th>Title</th>


                      <!-- <th>Status</th> -->
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
                    <?php $search = $_POST['search'] ?? ''; ?>
                    <?= createPagination('ep_message', '', $search, 5); ?>
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
    function loadData(page = 1) {
      $.ajax({
        url: window.location.href,
        type: "POST",
        data: {
          page: page
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

    // first load
    loadData();
    loadPagination();
  </script>