<?php

require_once("connection/db.php");
require_once('sweetAlert.php');
$uid = $_SESSION['user_id'];
$oid = $_GET['o_id'];
// echo $oid;

try{
    if(isset($oid)){
        // product availability
        $get_products = $conn->prepare("SELECT *
FROM ep_orders_master o
JOIN ep_orders_items oi ON o.o_id = oi.o_id
WHERE oi.o_id = :oid");

$get_products->execute(['oid'=>$oid]);
$fetch_data = $get_products->fetchAll(PDO::FETCH_ASSOC);
foreach($fetch_data as $f){
    $pid = $f['p_id'];
    // echo $pid;

    // check available in product
    $check_product = $conn->prepare("SELECT * FROM ep_products p JOIN ep_orders_items i ON p.p_id=i.p_id WHERE i.p_id = :pid AND stock > 2");
    $check_product->execute(['pid'=>$pid]);
    $fetch_products = $check_product->fetch(PDO::FETCH_ASSOC);
    if($check_product){
        // echo "YEs";
        // Now Insert in cart
        $add_items = $conn->prepare("INSERT INTO `ep_cart`( `u_id`, `p_id`, `pname`, `qty`, `price`, `guest_id`) VALUES (:uid , :pid  , :pname , :qty , :price, :gid)");
        $add_items->execute([
            'uid'=>$uid,
            'pid'=>$pid,
            'pname'=>$fetch_products['name'],
            'qty'=>1,
            'price'=>$fetch_products['price'],
            'gid'=>NULL
        ]);
        sweetAlert("✔ Done","Re-Order Successfull...","success");
        header("Location:cart.php");
    }
}
    }
}catch(PDOException $e){
    echo $e;
}

?>