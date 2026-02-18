<?php
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database_name = "db_pharmacy";

    try{
        $conn = new PDO("mysql:host=$server_name;dbname=$database_name",$username,$password,);
        //set error modes
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        die("Connection Failed=>Error:".$e->getMessage());
    }
    session_start();

    
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


//sweet Alert function for message
function sweetAlert($title, $text, $icon = 'info') {
    $_SESSION['swal'] = [
        'title' => $title,
        'text'  => $text,
        'icon'  => $icon
    ];
}

?>