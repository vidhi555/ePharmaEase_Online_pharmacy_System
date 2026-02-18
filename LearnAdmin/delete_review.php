<?php
    require_once('db.php');
    require('crud.php');

    $table = "ep_review";
    $id = $_GET['review_id'];
    $condition = "review_id = $id";
    $query = delete_record($table, $condition);
    if(!$query){
        echo "Query Fail";
    }
    header("Location:userreview.php");
?>
