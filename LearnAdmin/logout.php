<?php
    session_start();

    //destroy session
    session_unset();
    session_destroy();

    setcookie("email","",time()-3600);
    setcookie("password","",time()-3600);
    header("Location:login.php");
?>