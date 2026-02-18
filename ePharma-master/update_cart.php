<!-- 
require_once("connection/db.php");
require_once('session.php');
$cid = $_GET['cart_id'];

try {

    $select_data = $conn->prepare("SELECT * FROM ep_cart WHERE cart_id = $cid AND u_id = :uid");
    $select_data->execute(['uid'=>$user_id]);
    $fetch_data = $select_data->fetch(PDO::FETCH_ASSOC);
    $qty = $fetch_data['qty'];
    // $price = $fetch_data['price'];

    $update = $conn->prepare("UPDATE ep_cart SET qty = :qty WHERE cart_id = :cid ");
    if ($update->execute([
        'qty'=>$qty,
        'cid'=>$cid
    ])) {
        header("Location:cart.php");
    } else {
        echo "Updation Fail";
    }
} catch (PDOException $e) {
    echo "ERROR:$e";
} -->
<?php

if (isset($_POST['update_cart'])) {

    $cart_ids = $_POST['cart_id'];
    $qtys     = $_POST['qty'];

    try {
        for ($i = 0; $i < count($cart_ids); $i++) {

            $update = $conn->prepare(
                "UPDATE ep_cart 
                 SET qty = :qty 
                 WHERE cart_id = :cart_id AND u_id = :uid"
            );

            $update->execute([
                'qty'     => $qtys[$i],
                'cart_id'=> $cart_ids[$i],
                'uid'     => $user_id
            ]);
        }

        $_SESSION['warning'] = "Cart updated successfully";

        header("Location: cart.php");
        exit;

    } catch (PDOException $e) {
        echo "ERROR: " . $e->getMessage();
    }
}
