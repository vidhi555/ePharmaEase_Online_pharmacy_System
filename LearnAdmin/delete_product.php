<?php
    require_once('db.php');
    require('crud.php');

    $table = "ep_products";
    $id = $_GET['p_id'];
    $condition = "p_id = $id";
    $query = delete_record($table, $condition);
    if(!$query){
        echo "Query Fail";
    }
    header("Location:products.php");
?>