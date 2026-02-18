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

    $o_status = $_POST['o_status'];
    $o_id     = $_POST['edit_o_id'];

    // 1️⃣ Fetch current order status
    $check = $conn->prepare("SELECT order_status,payment_status,payment_method FROM $table WHERE o_id = :oid");
    $check->execute(['oid' => $o_id]);
    $row = $check->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      sweetAlert("Invalid Order ID", "", "error");
    }

    $currentStatus = $row['order_status'];
    $payment_status = $row['payment_status'];
    $payment_method = $row['payment_method'];


    // 2️⃣ Lock final states
    if ($currentStatus === 'cancelled') {
      sweetAlert("Order Already Cancelled", "", "warning");
    }

    if ($currentStatus === 'delivered') {
      sweetAlert("Order Already Delivered", "", "warning");
    }

    // 3️⃣ Status flow validation
    $allowedFlow = [
      'placed'    => ['confirmed', 'cancelled'],
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

    // 4️⃣ Update order status
    $query = $conn->prepare(
      "UPDATE $table SET order_status = :ord , payment_status = 'paid' WHERE o_id = :oid"
    );


    $is_update = $query->execute([
      'ord' => $o_status,
      'oid' => $o_id
    ]);
    if (!$is_update) {
      sweetAlert("Order not Updated!", "Check the Payment is Paid or not!", "warning");
    } else {
      sweetAlert("Order Status Updated Successfully", "", "success");
    }
  }
} catch (PDOException $e) {
  echo $e->getMessage();
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

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function confirmDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete these Product?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect or AJAX call
          window.location.href = "delete_category.php?o_id=" + id;
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
        include('header.php');
        ?>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <div class="row">
          <div class="col-12">
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-size-26 text-color-2">Total Orders</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">
                  <!-- Date Range Button -->
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle">
                    <i class="fa-solid fa-filter"></i>
                    <select id="filterbystatus" class="form-select text-size-sm" style="border: none;">
                      <option value="" selected="selected" disabled>Filter By Order Status</option>
                      <option value="placed">Order placed</option>
                      <option value="delivered">Delivered</option>
                      <option value="cancel">Cancel</option>
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
                      <th>OrderID</th>
                      <th>UserID</th>
                      <th>Order date</th>
                      <th> Customer Name</th>
                      <th>Mobile</th>
                      <th>Address</th>
                      <th>Total Amount</th>
                      <th>Payment</th>
                      <th>Payment Status</th>
                      <th>Order Status</th>

                      <th><i class="fas fa-ellipsis-h"></i></th>
                    </tr>
                  </thead>
                  <tbody id="result">
                    <?php
                    try {
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

                      $q = $conn->prepare("SELECT * FROM ep_orders_master LIMIT {$offset} , {$limit}");
                      $q->execute();
                      $fetch_orders = $q->fetchAll(PDO::FETCH_ASSOC);
                      if ($q->rowCount() > 0) {
                        foreach ($fetch_orders as $order) {
                    ?>
                          <tr>
                            <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                            <td><?= $order['o_id'] ?></td>
                            <td><?= $order['u_id'] ?></td>
                            <td><?= $order['oder_date'] ?></td>
                            <td><?= $order['fname'];
                                $order['lname'] ?></td>
                            <td><?= $order['mobile'] ?></td>
                            <td><?= $order['city'] . ", " . $order['district'] . ", " . $order['country'] . ", " . $order['zip'] ?></td>
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
                              <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $order['o_id'] ?>)"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                          </tr>
                    <?php
                        }
                      } else {
                        echo "<tr><td colspan='10'><p style='text-align: center;'>No Orders available😥</p></td></tr>";
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
                    $q = $conn->prepare("SELECT * FROM ep_orders_master");
                    $q->execute();
                    $count = $q->fetchColumn();
                    // echo $count;
                    if ($count > 0) {
                      $pages = ceil($count / $limit);  //find total pages of all records per limit
                    ?>
                      <li class="page-item">
                        <?php if ($page >= 1) { ?>
                          <a class="page-link" href="orders.php?page=<?= $page - 1 ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                        <?php } ?>
                      </li>
                      <?php
                      for ($i = 1; $i <= $pages; $i++) {
                      ?>
                        <li class="page-item"><a class="page-link" href="orders.php?page=<?= $i ?>"><?php echo $i; ?></a></li>
                      <?php
                      } ?>
                      <li class="page-item">
                        <?php if ($page < $pages) { ?>
                          <a class="page-link" href="orders.php?page=<?= $page + 1 ?>" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                        <?php } ?>
                      </li>

                    <?php } ?>
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
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <?php include('footer.php');
      require_once('sweetAlert.php'); ?>

    </div>
  </div>

  <script>
    $(document).ready(function() {
      function filter_status() {
        let orderstatus = $("#filterbystatus").val();
        // alert(orderstatus); 
        $.ajax({
          url: "order_filter_status.php",
          method: "POST",
          data: {
            orderstatus: orderstatus
          },

          success: function(data) {
            $("#result").html(data).show();
          }
        });
      }

      $(document).on("change", "#filterbystatus", function() {
        filter_status();
      });

    })
  </script>

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
                <option value="placed">Placed</option>
                <option value="confirmed">Confirmed</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <p id="para" class="text-muted"></p>

            <div class="col-12 mt-5">
              <button type="submit" name="edit" class="btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">Update</button>
            </div>
          </form>


        </div>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('EditModal').addEventListener('show.bs.modal', function(event) {

      let btn = event.relatedTarget;

      document.getElementById('edit_status').value = btn.getAttribute('data-status');

      // document.getElementById('edit_pay_status').value = btn.getAttribute('data-pay-status');
      document.getElementById('edit_o_id').value = btn.getAttribute('data-o_id');
      document.getElementById('edit_name').innerHTML = btn.getAttribute('data-orderid');

      // write a script for check order delivered or not
      let select = document.getElementById('edit_status');
      let current_status = btn.getAttribute('data-status');
      select.value = current_status;
      if (current_status === "delivered") {
        document.getElementById("para").innerHTML = "This Order is already Delivered!";

        // alert('yes');  
        for (let i = 0; i < select.options.length; i++) {
          select.options[i].disabled = true;
        }
      } else {
        document.getElementById("para").innerHTML = "";

      }
      // alert(current_status);
    });
  </script>