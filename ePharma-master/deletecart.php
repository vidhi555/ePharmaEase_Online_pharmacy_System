<?php

require_once("connection/db.php");
$cid = $_GET['cart_id'];

try {
    if ($cid) {
        $query = $conn->prepare("DELETE FROM `ep_cart` WHERE cart_id = $cid");
        if ($query->execute()) {
            header("Location:cart.php");
            sweetAlert("Deleted!", "Item Deleted!!!", "info");
        } else {
            echo "Deletion Fail";
        }
    } else {
        echo "Cart id Not found";
    }
} catch (PDOException $e) {
    echo "ERROR:$e";
}
