
<?php
require_once('db.php');
require('crud.php');
require_once('sweetAlert.php');

try {
    if (isset($_REQUEST['u_id'])) {
        //delete customer
        $table = "ep_users";
        $id = $_GET['u_id'];
        $condition = "u_id = $id";
        $query = delete_record($table, $condition);
        if (!$query) {
            echo "Query Fail";
        }
        header("Location:customers.php");
    }
    if (isset($_REQUEST['c_id'])) {
        //delete category
        $table = "ep_category";
        $id = $_GET['c_id'];
        $condition = "c_id = $id";
        $query = delete_record($table, $condition);
        if (!$query) {
            echo "Query Fail";
        }
        header("Location:category.php");
    }
    if (isset($_REQUEST['o_id'])) {
        //delete customer
        $table1 = "ep_orders_master";
        $id1 = $_GET['o_id'];
        $condition1 = "o_id = $id1";
        $query1 = delete_record($table1, $condition1);
        if (!$query1) {
            echo "Query Fail";
        }
        $table2 = "ep_orders_items";
        $id2 = $_GET['o_id'];
        $condition2 = "o_id = $id2";
        $query2 = delete_record($table2, $condition2);
        if (!$query2) {
            echo "Query Fail";
        }
        header("Location:orders.php");
    }
} catch (PDOException $e) {
    header("Location:404.php");
}
?>