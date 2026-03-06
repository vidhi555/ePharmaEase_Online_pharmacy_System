<?php
require_once('db.php');

$limit = 5;
$q = $conn->query("SELECT COUNT(*) FROM ep_category");
$total = $q->fetchColumn();

$pages = ceil($total/$limit);

for($i=1;$i<=$pages;$i++){
    echo "<li class='page-item'>
            <a href='#' class='page-link page-btn' data-page='$i'>$i</a>
          </li>";
}
?>
