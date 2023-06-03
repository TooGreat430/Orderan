<?php

    session_start();

    if(isset($_SESSION["userid"]) AND $_SESSION["userid"]==true AND $_SESSION["RoleID"]==2){
        header("location: Customer.php");
        exit;
    }else if(isset($_SESSION["userid"]) AND $_SESSION["userid"]==true AND $_SESSION["RoleID"]==3){
        header("location: Tenant.php");
        exit;
    }else if(isset($_SESSION["userid"]) AND $_SESSION["userid"]==true AND $_SESSION["RoleID"]==4){
        header("location: Admin.php");
        exit;
    }else if(isset($_SESSION["userid"]) AND $_SESSION["userid"]==true AND $_SESSION["RoleID"]==5){
        header("location: Pickupper.php");
        exit;
    }

?>