<?php
session_start();
unset($_SESSION['card']);
header('Location:index.php');