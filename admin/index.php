<?php
session_start();
require "../config/config.php";

require "../config/common.php";
if(empty($_SESSION["user_id"] && empty($_SESSION["logged_in"])) && $_SESSION['role'] != 1){
   header("location:login.php");
}
if(empty($_POST['search'])){
    if(empty($_GET["pageno"])){
        unset($_COOKIE['search']);
        setcookie('search',null,-1,"/");
        }
}else{
    setcookie('search',$_POST['search'],time() + (8600 * 30), "/");
}

?>

<?php require "header.php"; ?>
     
<div class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Product Listing</h3>
</div>

<div class="card-body">
    <a href="add.php" class="btn btn-success mb-3">New Blog Post</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
    </table>                
    <nav aria-label="Page navigation">
       
    </nav>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php require "footer.php"; ?>