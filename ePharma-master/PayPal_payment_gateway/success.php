<?php
require_once("../connection/db.php");
// print_r($_SESSION);
// $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
// // $uid = $_SESSION['user_id'] ;
// echo $user_id."-";
// die();

$txn_id  = $_GET['tx'] ?? '';
// $status  = $_GET['st'] ?? '';
$amount  = $_GET['amt'] ?? '';
$currency = $_GET['cc'] ?? '';

// echo "\nTransaction: ".$txn_id;
// echo "\sts: ".$status;
// echo "\amt: ".$amount;
// echo "\cuure: ".$currency;
// die();

$order_id = $_GET['o_id'] ?? '';


$updatePayment = $conn->prepare("UPDATE ep_payment SET payment_status = 'paid', transaction_id = :txn_id, currency = :crr , payment_date = NOW()
WHERE o_id = :oid");
$updatePayment->execute([
    'txn_id' => $txn_id,   // PayPal transaction ID
    'crr'=>$currency,
    'oid' => $order_id
]);

// Also update order table
$conn->prepare("UPDATE ep_orders_master SET order_status = 'confirmed', payment_status = 'paid' WHERE o_id = :oid")->execute(['oid' => $order_id]);


// echo $order_id;
// echo $txn_id;
// die();
$check_payment_status = $conn->prepare("SELECT * FROM ep_orders_master WHERE o_id = :oid");
$check_payment_status->execute(['oid'=>$order_id]);
$fetch_payment_status = $check_payment_status->fetch(PDO::FETCH_ASSOC);
$payment_method = $fetch_payment_status['payment_method'];
$uid = $fetch_payment_status['u_id'];

// Only confirmed & Online payment option can update status
if($payment_method == 'paypal'){

    // remove products from cart 
    $clear_cart = $conn->prepare("DELETE FROM ep_cart WHERE u_id = :uid");
    $clear_cart->execute(['uid' => $uid]);

    //Reduce stocks from products
    $query_Stock = $conn->prepare("SELECT * FROM ep_orders_items WHERE o_id = :oid ");
    $query_Stock->execute(['oid' => $order_id]);
    $fetch_stock = $query_Stock->fetchAll(PDO::FETCH_ASSOC);
    $update_stock = $conn->prepare("UPDATE ep_products SET stock = stock - :qty WHERE p_id = :pid AND stock >= :qty");
    foreach ($fetch_stock as $s) {
        $update_stock->execute([
            "qty" => $s['qty'],
            "pid" => $s['p_id']
        ]);
        if ($update_stock->rowCount() == 0) {
            $errors[] = "Insufficient Stock for Product ID:" . $s['p_id'];
        }
    }
}else{
    echo "Order is not confirmed";
}
header("Location:../confirmation.php?o_id=".$order_id);
