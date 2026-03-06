<?php
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "db_pharmacy";

try {
    $conn = new PDO("mysql:host=$server_name;dbname=$database_name", $username, $password,);
    //set error modes
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed=>Error:" . $e->getMessage());
}
session_start();


//sweet Alert function for message
function sweetAlert($title, $text, $icon = 'info',$redirect = null)
{
    $_SESSION['swal'] = [
        'title' => $title,
        'text'  => $text,
        'icon'  => $icon,
        'redirect'=>$redirect
    ];
}


//Pagination buttton reusable function
function createPagination($table, $column = '', $search = '', $limit = 5)
{
    global $conn;
    
    if ($search != '' && $column != '') {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM $table WHERE $column LIKE :search");
        $stmt->execute([':search' => $search . '%']);
    } else {
        $stmt = $conn->query("SELECT COUNT(*) FROM $table");
    }

    $total = $stmt->fetchColumn();
    $pages = ceil($total / $limit);

    for ($i = 1; $i <= $pages; $i++) {
        echo "<li class='page-item'>
                <a href='#' class='page-link page-btn' data-page='$i'>$i</a>
              </li>";
    }
}
