<?php
require_once("db.php");
    try{
        function pagination_buttons($table , $condition , $limit, $offset){
            global $conn;
            $query = "SELECT * FROM $table WHERE 1";
            $pagianate = $conn->prepare($query);
            $pagianate->execute();
            $count = $pagianate->rowCount();
            
        }
    }catch(PDOException $e){
        echo $e;
    }
?>
<div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
    <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
            </li>
        </ul>
    </nav>
    <div class="d-flex justify-content-end">
        <div class="page-selector">
            <span>PAGE</span>
            <select class="form-select" aria-label="Select page">
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <span>OF 100</span>
        </div>
    </div>
</div>