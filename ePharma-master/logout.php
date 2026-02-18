<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location:index.php");
    // require_once("sweetAlert.php");
    // sweetAlert("","“You have been logged out successfully. Stay healthy!”","success");
?>