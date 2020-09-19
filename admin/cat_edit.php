<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if(empty($_SESSION["user_id"] && $_SESSION["logged_in"])){
   header("location:login.php");
}
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);
if($_POST){
   
   if(empty($_POST['name']) || empty($_POST['description'])){
     if(empty($_POST['name'])){
         $nameError = "Name cannot be null";
     }
     if(empty($_POST['description'])){
        $descError = "Description cannot be null";
    }

   }else{
        $id = $_POST['id'];
        $name = $_POST["name"];
        $description = $_POST["description"];
         $update = date('Y:m:d h:i:s',time());
        $stmt = $pdo->prepare("UPDATE categories SET name='$name',description='$description',updated_at='$update' WHERE id=$id");
        $result = $stmt->execute();
         if($result){
            // echo "<script>alert('Insert Success !');window.location.href='index.php';</script>";
            header("location:category.php");
         }
    
   }
}


?>
<?php require "header.php"; ?>
<div class="content">
<div class="container-fluid">
    <!-- Content Header (Page header) -->
<div class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">  
</div>
</div>
    <!-- /.row -->
</div>
</div>
<div class="content">
<div class="container-fluid">
<div class="row">
<div class="col-10 offset-1">
<div class="card">
    <div class="card-body">
        <h4 class='text-success'>Edit Category</h4>
        <form action="" method="post" >
        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
            <div class="form-group">
            <input type="hidden" class="form-control" name='id' value="<?php echo $category['id']; ?>">
                <label for="title">Name</label>
                <input type="text" class="form-control" name='name' require  value="<?php echo escape($category['name']); ?>">
                <span class='text-danger'><?php echo empty($nameError) ? '' : $nameError; ?></span>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description"require cols="30" rows="5" class="form-control"> <?php echo escape($category['description']); ?></textarea>
                <span class='text-danger'><?php echo empty($descError) ? '' : $descError; ?></span>
            </div>
            
            <div class="text-right">
                <a href="category.php" class="btn btn-outline-secondary mr-3">Back</a>
                <input type="submit" class='btn btn-primary' value="SUBMIT">
            </div>
        </form>
    </div>
</div>
</div>
</div>
    <!-- /.row -->
</div>
</div>
</div>
</div>
</div>
<?php require "footer.php"; ?>