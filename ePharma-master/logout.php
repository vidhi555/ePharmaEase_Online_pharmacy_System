<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie("name", "" ,time() - 3600);
    setcookie("password", "" ,time() - 3600);
    header("Location:index.php");
    // require_once("sweetAlert.php");
    // sweetAlert("","“You have been logged out successfully. Stay healthy!”","success");
?>