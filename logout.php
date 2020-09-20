<?php
session_start();
unset($_SESSION["user_id"]);
unset($_COOKIE['search']);
setcookie('search',null,-1,"/");
header("location:login.php");