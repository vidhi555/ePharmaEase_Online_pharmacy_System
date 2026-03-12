<?php
require_once('../connection/db.php');

$order_id = $_GET['o_id'];

// Fetch order total from DB
$stmt = $conn->prepare("SELECT * FROM ep_orders_master WHERE o_id = :oid");
$stmt->execute(['oid' => $order_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$price = number_format($row['total_amount'],2);
?>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="paypalForm">

    <!-- ==================================================== -->
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="business" value="sb-goq8f48956105@business.example.com">
    <input type="hidden" name="item_name" value="Medicine Order #<?= $order_id ?>">
    <input type="hidden" name="amount" value="<?= $price ?>">
    <input type="hidden" name="currency_code" value="USD">
    



    <input type="hidden" name="return"
        value="http://localhost/ePharmaEase_Project/ePharma-master/PayPal_payment_gateway/success.php?o_id=<?= $order_id ?>">

    <input type="hidden" name="cancel_return"
        value="http://localhost/ePharmaEase_Project/ePharma-master/PayPal_payment_gateway/cancel.php?o_id=<?= $order_id ?>">
    <!-- ==================================================== -->

</form>

<script>
    // auto submit
    document.getElementById("paypalForm").submit();
</script>