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

  $filter_statuses = $_POST['filter_status'];
  $searches = $_POST['search'];

  $page = $_POST['page'];

  $limit = 5;
  $offset = ($page - 1) * $limit;
  $where = "WHERE 1";
  if ($searches != '') {
    $where .= " AND (name LIKE :s OR email LIKE :s OR subject LIKE :s) ";
  }
  if ($filter_statuses != '') {
    $where .= " AND (status = :sts)";
  }
  // Query
  $query = $conn->prepare("SELECT * FROM ep_message $where LIMIT $offset,$limit");
  if ($searches != '') {
    $query->bindValue(':s', '%' . $searches . '%');
  }
  if ($filter_statuses != '') {
    $query->bindValue(':sts', $filter_statuses);
  }
  $query->execute();
  $fetch_msg = $query->fetchAll(PDO::FETCH_ASSOC);
  $id = $offset;
  if ($fetch_msg) {
    foreach ($fetch_msg as $msg) {
      $id++;

?>
      <tr>
        <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
        <td><?php if ($msg['status'] == 'New') { ?>
            <span class="new-dot"></span>
          <?php } ?>
          <?= $id ?>
        </td>

        <td><?= $msg['name'] ?></td>
        <td><?= $msg['email'] ?></td>
        <td style="text-wrap: wordwrap;"><?= $msg['subject'] ?></td>
        <td><?= date('d/m/Y', strtotime($msg['message_at'])) ?></td>
        <td><?= date('h:i A', strtotime($msg['message_at'])) ?></td>

        <!-- <td><span class="badge bg-success">Active</span></td> -->
        <td class="text-center">
          <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#EditModal" class="btn btn-sm btn-primary mb-2"><i class="fa-regular fa-pen-to-square"></i></a> -->
          <a class="btn btn-sm btn-warning mb-2 mb-lg-0 me-0 me-lg-2" href="view_mesage.php?msg_id=<?= $msg['msg_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>

          <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $msg['msg_id'] ?>,'delete_message.php?msg_id=')"><i class="fa-solid fa-trash-can"></i></button>

        </td>
      </tr>

<?php
    }
  } else {
    echo "<tr><td colspan = '5'><p class='text-center'>No Records Found!😥</p></td></tr>";
  }
  // Pagination split
  echo "###pagination###";
  $where = "WHERE 1";
  if ($searches != '') {
    $where .= " AND (name LIKE :s OR email LIKE :s OR subject LIKE :s) ";
  }
  if ($filter_statuses != '') {
    $where .= " AND (status = :sts)";
  }
  $query = $conn->prepare("SELECT * FROM ep_message $where ");
  if ($searches != '') {
    $query->bindValue(':s', '%' . $searches . '%');
  }
  if ($filter_statuses != '') {
    $query->bindValue(':sts', $filter_statuses);
  }
  $query->execute();
  $total = $query->rowCount();
  $pages = ceil($total / $limit);
  if ($pages > 0) {
    for ($i = 1; $i <= $pages; $i++) {
      echo "<li class='page-item'>
              <a href='#' class='page-link' data-page='$i'>$i</a>
              </li>";
    }
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
                  <div class="search-wrapper cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="input-group flex-nowrap">
                      <span style="border:none;" class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                      <input style="border:none;" type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search By Anything" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                  </div>
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle">
                    <i class="fa-solid fa-filter"></i>
                    <select id="filterbystatus" class="form-select text-size-sm" style="border: none;">
                      <option value="" selected="selected" disabled>Filter By Status</option>
                      <option value="">All Messages</option>
                      <option value="New">New Messages</option>
                      <option value="replied">Replied Message</option>
                      <!-- <option value="unread">Unread Messages</option> -->
                    </select>
                  </div>
                  <p data-bs-toggle="tooltip" title="Clear Filter">
                    <button id="remove_filter" class="clear-filter-btn">
                      <i class="fa-solid fa-ban"></i>
                    </button>
                  </p>
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
                      <th>Email</th>
                      <th>Title</th>
                      <th>Date</th>
                      <th>Timing</th>

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
  <script src="pagin_search_filter.js"></script>
  <script>
   
    $(document).on("click", "#remove_filter", function () {
    let search = $("#livesearch").val('');
    var filter_status = $("#filterbystatus").val('');
    loadData(1);
});
  </script>