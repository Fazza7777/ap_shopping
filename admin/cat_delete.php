<?php
require "../config/config.php";
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM categories WHERE id=$id");
if($stmt->execute()) header('location:category.php');
