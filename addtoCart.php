<?php
session_start();
require "config/config.php";
if($_POST){
    $id = $_POST['id'];
    $qty = $_POST['qty'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if( !($qty > $result->quantity) ){
        if(  isset($_SESSION['card']['id='.$id]) ){
            $_SESSION['card']['id='.$id] += $qty;
          }else{
            $_SESSION['card']['id='.$id] = $qty;
          }  
          header('Location:index.php');
    }else{
      echo "<script>alert('Not enough instock');window.location.href='product_detail.php?category_id=$id';
      </script>";
    }
}

?>