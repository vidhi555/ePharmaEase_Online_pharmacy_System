<?php
require_once('connection/db.php');
require_once('session.php');

$oid = $_SESSION['last_order_id'] ;
?>


<!--================ Start Header Menu Area =================-->
<?php
$page_title = "ePharmaEase - Confirmation page";
require_once("header.php");
?>
<!--================ End Header Menu Area =================-->

<!-- ================ start banner area ================= -->
<section class="blog-banner-area fade-up" id="category">
  <div class="container h-100">
    <div class="blog-banner">
      <div class="text-center">
        <h1>Order Confirmation</h1>
        <nav aria-label="breadcrumb" class="banner-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shop Category</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>
<!-- ================ end banner area ================= -->


<?php
try {
  //check session
  if (!empty($user_id)) {
    //display order details of specific order id and logged user
    // $oid = $conn->lastInsertId();

    $query = $conn->prepare("SELECT * FROM ep_orders_items i JOIN ep_orders_master m ON i.o_id = m.o_id JOIN ep_products p ON p.p_id = i.p_id WHERE m.u_id = :uid and m.o_id = :oid");
    $res = $query->execute([
      'uid' => $user_id,
      'oid' => $oid
    ]);
    $fetch_orders = $query->fetch(PDO::FETCH_ASSOC);
    if ($fetch_orders) { ?>


      <!--================Order Details Area =================-->
      <section class="order_details section-margin--small">
        <div class="container">
          <h1 class="text-center billing-alert">Thank you. Your order has been received.</h1>
          <div class="row mb-5">
            <div class="col-md-6 col-xl-4 mb-4 mb-xl-0">
              <div class="confirmation-card">
                <h3 class="billing-title">Order Information</h3>
                <table class="order-rable">
                  <tr>
                    <td>Order ID</td>
                    <td>: <?= $fetch_orders['o_id'] ?></td>
                  </tr>
                  <tr>
                    <td>Date</td>
                    <td>: <?= $fetch_orders['oder_date'] ?></td>
                  </tr>
                  <tr>
                    <td>Total</td>
                    <td>: ₹<?= $fetch_orders['total_amount'] ?></td>
                  </tr>
                  <tr>
                    <td>Payment method</td>
                    <td>: <?= $fetch_orders['payment_method'] == 'COD' ? "Cash on Delivery" : "Online Payment" ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="col-md-6 col-xl-4 mb-4 mb-xl-0">
              <div class="confirmation-card">
                <h3 class="billing-title">Billing Address</h3>
                <table class="order-rable">
                  <tr>
                    <td>District</td>
                    <td>: <?= $fetch_orders['district'] ?></td>
                  </tr>
                  <tr>
                    <td>City</td>
                    <td>: <?= $fetch_orders['city'] ?></td>
                  </tr>
                  <tr>
                    <td>Country</td>
                    <td>: <?= $fetch_orders['country'] ?></td>
                  </tr>
                  <tr>
                    <td>Postcode</td>
                    <td>: <?= $fetch_orders['zip'] ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="col-md-6 col-xl-4 mb-4 mb-xl-0">
              <div class="confirmation-card">
                <h3 class="billing-title">Shipping Address</h3>
                <table class="order-rable">
                  <tr>
                    <td>Street</td>
                    <td>: <?= $fetch_orders['address'] ?></td>
                  </tr>
                  <tr>
                    <td>City</td>
                    <td>: <?= $fetch_orders['city'] ?></td>
                  </tr>
                  <tr>
                    <td>Country</td>
                    <td>: <?= $fetch_orders['country'] ?></td>
                  </tr>
                  <tr>
                    <td>Postcode</td>
                    <td>: <?= $fetch_orders['zip'] ?></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <div class="order_details_table">
            <h2>Order Details</h2>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (!empty($user_id)) {
                    //display order details of specific order id and logged user
                    //  $oid = $_SESSION['last_order_id'];
                    // $query = $conn->prepare("SELECT name,i_id, o_id, i.p_id, u_id, i.qty, i.price FROM ep_orders_items i JOIN ep_products p ON i.p_id = p.p_id WHERE u_id = :uid  AND o_id = :oid");
                    // $res = $query->execute([
                    //   'uid'=>$user_id,
                    //   'oid'=>$oid
                    // ]);
                    $query = $conn->prepare("SELECT * FROM ep_orders_items i JOIN ep_orders_master m ON i.o_id = m.o_id JOIN ep_products p ON p.p_id = i.p_id WHERE m.u_id = :uid and m.o_id = :oid");
                    $res = $query->execute([
                      'uid' => $user_id,
                      'oid' => $oid
                    ]);
                    $fetch_orders = $query->fetchAll(PDO::FETCH_ASSOC);
                    $grand = 0;
                    foreach ($fetch_orders as $f) {
                      $total = $f['price'] * $f['qty'];
                      $grand += $total;
                  ?>
                      <tr>
                        <td>
                          <p><?= $f['name'] ?></p>
                        </td>
                        <td>
                          <h5>x <?= $f['qty'] ?></h5>
                        </td>
                        <td>
                          <p>₹<?= $f['price'] ?></p>
                        </td>
                      </tr>
                    <?php } ?>
                    <tr>
                      <td>
                        <h4>Subtotal</h4>
                      </td>
                      <td>
                        <h5></h5>
                      </td>
                      <td>
                        <p>₹<?= $grand ?></p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <h4>Shipping</h4>
                      </td>
                      <td>
                        <h5></h5>
                      </td>
                      <td>
                        <p>Flat rate: <?= $grand < 1000 ? '₹50' : 'free' ?></p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <h4><b>Total</b></h4>
                      </td>
                      <td>
                        <h5></h5>
                      </td>
                      <td>
                        <h4><b>₹<?= $f['total_amount'] ?></b></h4>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
      <!--================End Order Details Area =================-->


      <!--================ Start footer Area  =================-->
      <?php require_once("footer.php"); ?>
      <!--================ End footer Area  =================-->


<?php   }
  }
} catch (PDOException $e) {
  echo $e;
  header("Location:404.php");
}
?>