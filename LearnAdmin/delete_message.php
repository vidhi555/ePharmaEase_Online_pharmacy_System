
<?php
require_once('db.php');
require('crud.php');

$table = "ep_message";
$id = $_GET['msg_id'];
$condition = "msg_id = $id";
$query = delete_record($table, $condition);
if (!$query) {
    echo "Query Fail";
}
header("Location:message_report.php");
?>