<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}


$table = "ep_orders_master";
try {
  if (isset($_POST['edit'])) {

    $o_status = $_POST['o_status'] ?? '';
    $o_id     = $_POST['edit_o_id'];

    //Fetch current order status
    $check = $conn->prepare("SELECT order_status,payment_status,payment_method FROM $table WHERE o_id = :oid");
    $check->execute(['oid' => $o_id]);
    $row = $check->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      sweetAlert("Invalid Order ID", "", "error");
    }

    $currentStatus = $row['order_status'];
    $payment_status = $row['payment_status'];
    $payment_method = $row['payment_method'];

    // if ($currentStatus === ) {
    //   sweetAlert("Order Already Delivered", "", "warning");
    // }
    //  echo $currentStatus;
    // die();

    //Status flow validation
    $allowedFlow = [
      'Placed'    => ['confirmed', 'cancelled'],
      'confirmed' => ['shipped', 'cancelled'],
      'shipped'   => ['delivered'],
      'delivered' => [],
      'cancelled' => []
    ];

    if (!in_array($o_status, $allowedFlow[$currentStatus])) {
      sweetAlert("Invalid Status Transition", "", "error");
    }

    // echo $payment_status;
    // die();

    // Update order status
    if ($o_status == 'confirmed' || $o_status == 'Placed') {
      $payment_status = 'pending';
    }
    if ($o_status == 'delivered') {
      $payment_status = 'paid';
    }
    // show final states
    if ($currentStatus === $o_status) {
      $showmsh = ucfirst($o_status);
      sweetAlert("Update Not Allowed", "Your order has already been $showmsh and cannot be modified.", "warning", "orders.php");
      header("orders.php");
    } else {

      $query = $conn->prepare("UPDATE $table SET order_status = :ord , payment_status = :pym WHERE o_id = :oid");
      $is_update = $query->execute([
        'ord' => $o_status,
        'pym' => $payment_status,
        'oid' => $o_id
      ]);
      if (!$is_update) {
        sweetAlert("Order not Updated!", "Check the Payment is Paid or not!", "warning");
        // if($currentStatus == 'delivered'){
        //   $payment_status = 'paid'
        // }
      } else {
        sweetAlert("Order Status Updated Successfully", "", "success");
      }
    }
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}

if (isset($_POST['page'])) {
  $data = $_POST['values'];
  $search = $_POST['search'];

  $limit = 5;
  $page = $_POST['page'];
  $offset = ($page - 1) * $limit;


  $where = "WHERE 1";

  if ($data != '') {
    $where .= " AND order_status = :sts";
  }
  if($search != ''){
    $where .= ' AND (fname LIKE :search OR o_id LIKE :search) ';
  }

  $query = $conn->prepare("SELECT * FROM ep_orders_master $where ORDER BY oder_date DESC LIMIT $offset , $limit ");

  if ($data != '') {
    $query->bindValue(':sts', $data);
  }
   if($search != ''){
    $query->bindValue(':search',$search.'%');
  }
  $query->execute();

  $fetch_data = $query->fetchAll(PDO::FETCH_ASSOC);
  if ($fetch_data) {
    $id = $offset;
    foreach ($fetch_data as $order) {
      $id++;
?>
      <tr>
        <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
        <td><?= $id ?></td>
        <td>#<?= $order['o_id'] ?></td>
        <td><?= $order['u_id'] ?></td>
        <td><?= date('d/m/Y', strtotime($order['oder_date'])) ?></td>
        <td><?= date('d/m/Y', strtotime($order['expected_date'])) ?></td>
        <td><?= $order['fname'];
            $order['lname'] ?></td>
        <td><?= $order['mobile'] ?></td>
        <td><?= $order['city'] . ", "  . $order['country'] . ", " . $order['zip'] ?></td>
        <td>₹<?= $order['total_amount'] ?></td>
        <td><?= $order['payment_method'] ?></td>
        <td><?php if ($order['payment_status'] == 'paid') { ?>
            <span class="badge bg-success">Paid</span>
          <?php } elseif ($order['payment_status'] == 'pending') { ?>
            <span class="badge bg-primary">Pending</span>
          <?php }  ?>
        </td>

        <td><?= $order['order_status'] ?></td>



        <td class="text-center">
          <?php
          $disable = "";
          if ($order['payment_status'] == 'pending' || $order['order_status'] == 'delivered') {
            $disable = "disabled btn-disabled";
          }
          ?>
          <a href="#" data-bs-toggle="modal"
            data-bs-target="#EditModal"
            data-status=<?= $order['order_status'] ?>
            data-orderid=<?= $order['o_id'] ?>
            data-o_id=<?= $order['o_id'] ?>
            data-pay-status=<?= $order['payment_status'] ?>
            class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2 "><i class="fa-regular fa-pen-to-square"></i></a>
          <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $order['o_id'] ?>,'delete_category.php?o_id=')"><i class="fa-solid fa-trash-can"></i></button>
        </td>
      </tr>


<?php
    }
  }
  exit();
}
$page_title = "Orders";
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
                <li class="breadcrumb-item active">Orders</li>
              </ol>
            </nav>
            <div class="category-header d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-size-26 text-color-2">Total Orders</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">
                  <!-- Date Range Button -->
                   
                   <div class="search-wrapper cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="input-group flex-nowrap">
                      <span style="border:none;" class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                      <input style="border:none;" type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search Orders by Order-id & Customer Name" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                  </div>
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle">
                    <i class="fa-solid fa-filter"></i>
                    <select id="filterbystatus" class="form-select text-size-sm" style="border: none;">
                      <option value="" selected="selected" disabled>Filter By Order Status</option>
                      <option value="Placed">Order placed</option>
                      <option value="delivered">Delivered</option>
                      <option value="confirmed">Confirmed</option>
                      <option value="cancelled">Cancel</option>
                    </select>
                  </div>

                  <!-- Reports Button -->
                  <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#CreateModal" class="cursor-pointer ms-4 bg-white bg-primary text-white d-flex align-items-center px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">
                                      <i class="fa-solid fa-plus me-3"></i>
                                      Add Staff
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
              <div class="table-responsive table-rounded-top text-center">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <!-- <th><input type="checkbox" id="select-all" class="custom-checkbox"></th> -->
                      <th>Sr.No.</th>
                      <th>OrderID</th>
                      <th>UserID</th>
                      <th>Order date</th>
                      <th>Expected Delivery Date</th>
                      <th> Customer Name</th>
                      <th>Mobile</th>
                      <th>Address</th>
                      <th>Total Amount</th>
                      <th>Payment</th>
                      <th>Payment Status</th>
                      <th>Order Status</th>

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
                    $s = $_POST['search'] ?? '';
                    createPagination("ep_orders_master", "", $s, 5);
                    ?>
                  </ul>
                </nav>
              </div>

              <!-- <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <ul class="pagination"> -->
              <?php
              // $q = $conn->prepare("SELECT * FROM ep_orders_master");
              // $q->execute();
              // $count = $q->fetchColumn();
              // // echo $count;
              // if ($count > 0) {
              //   $pages = ceil($count / $limit);  //find total pages of all records per limit
              ?>
              <!-- <li class="page-item">
                        <?php if ($page > 1) { ?>
                          <a class="page-link" aria-label="Previous" data-page=""><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                        <?php } ?>
                      </li> -->
              <?php
              // for ($i = 1; $i <= $pages; $i++) {
              ?>
              <!-- <li class="page-item"><a class="pagination-link" data-page="<?= $i ?>"><?php echo $i; ?></a></li> -->
              <?php
              // } 
              ?>
              <!-- <li class="page-item">
                        <?php if ($page < $pages) { ?>
                          <a class="pagination-link" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                        <?php } ?>
                      </li> -->

              <?php
              // } 
              ?>
              <!-- <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="fas fa-ellipsis-h"></i></a></li>
                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                    <li class="page-item"><a class="page-link" href="#">7</a></li>
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                    </li> -->
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
              <!-- </div>
            </div> -->
            </div>
          </div>
        </div>

        <!-- Footer -->
        <?php include('footer.php');
        require_once('sweetAlert.php'); ?>

      </div>
    </div>


    <!--Edit  Modal -->
    <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">
          <div class="modal-body p-4 position-relative">
            <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            <h2 class="h5 text-color-2 py-2">Update Order Status</h2>
            <form class="row g-3" method="post">
              <input type="hidden" name="edit_o_id" id="edit_o_id">

              <div class="mb-12">
                <label for="" class="form-label text-color-2 text-normal">Order ID:</label>
                <label for="Product_desc" id='edit_name'></label>
              </div>
              <!-- <div class="col-12">
              <label for="status" class="form-label text-color-2 text-normal">Payment Status</label>
              <select name="pay_status" id="edit_pay_status" class="form-select text-normal">
                <option value="" selected="selected" disabled>Choose Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
              
              </select>
            </div> -->
              <div class="col-12">
                <label for="status" class="form-label text-color-2 text-normal">Order Status</label>
                <select name="o_status" id="edit_status" class="form-select text-normal">
                  <option value="" selected="selected" disabled>Choose Status</option>
                  <option value="Placed">Placed</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="delivered">Delivered</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>

              <p id="para" class="text-muted"></p>

              <div class="col-12 mt-5">
                <button type="submit" name="edit" class="<?= $disable ?> btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">Update</button>
              </div>
            </form>


          </div>
        </div>
      </div>
    </div>
    <script>
     document.getElementById('EditModal').addEventListener('show.bs.modal', function(event) {

    let btn = event.relatedTarget;
    
    let select = document.getElementById('edit_status');
    let current_status = btn.getAttribute('data-status');

    document.getElementById('edit_o_id').value = btn.getAttribute('data-o_id');
    document.getElementById('edit_name').innerHTML = btn.getAttribute('data-orderid');

    select.value = current_status;

    // First enable all options
    for (let i = 0; i < select.options.length; i++) {
        select.options[i].disabled = false;
    }

    // Then check if delivered
    if (current_status.toLowerCase() === "delivered") {

        document.getElementById("para").innerHTML = "<span class='text-danger fw-bold'>This Order is already Delivered!</span>";
        // Disable all options
        for (let i = 0; i < select.options.length; i++) {
            select.options[i].disabled = true;
        }
    } else {
        document.getElementById("para").innerHTML = "";
    }

});
</script>


    <script>
      // Pagination + Search

      function loadData(page = 1) {
        let values = $('#filterbystatus').val();
        let search = $("#livesearch").val();

        $.ajax({
          url: window.location.href,
          method: "POST",
          data: {
            page: page,
            values: values,
            search:search
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
      $(document).on('keyup',"#livesearch",function(){
        var s = $(this).val();
        loadData(1);
        loadPagination();
      })
      $(document).ready(function() {
        $(document).on("change", "#filterbystatus", function() {
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