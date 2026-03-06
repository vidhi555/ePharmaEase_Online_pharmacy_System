<?php
require_once("connection/db.php");
require_once("sweetAlert.php");
try{
    $oid = $_GET['o_id'];
    // echo $oid;
    // die();
    if(!empty($oid)){
        $cancel = $conn->prepare("UPDATE ep_orders_master SET order_status = 'cancelled' WHERE o_id = :oid AND order_status IN ('Placed','confirmed')");
        $result = $cancel->execute(['oid'=>$oid]);
        if($result){
            sweetAlert("Your order has been cancelled successfully.","","success");
        }else{
            sweetAlert("Something Went Wrong!","","warning");
        }
        header("Location:user_profile.php");
    }
}catch(PDOException $e){
    echo $e;
}