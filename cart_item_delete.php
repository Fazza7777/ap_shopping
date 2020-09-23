<?php
session_start();
require "config/config.php";
$clearId = $_GET['id'];
unset($_SESSION['card'] ['id='.$clearId]);
if(!empty($_SESSION['card'])){
    header('location:cart.php');
}else{
    header('location:index.php');
}
?>