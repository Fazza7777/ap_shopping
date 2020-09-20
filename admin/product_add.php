<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if(empty($_SESSION["user_id"] && $_SESSION["logged_in"])){
   header("location:login.php");
}


if($_POST){
   
   if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category_id']) || empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image']) || !(is_numeric($_POST['quantity'])) || !(is_numeric($_POST['price']))){
     if(empty($_POST['name'])){
         $nameError = "Name cannot be null";
     }
     if(empty($_POST['description'])){
        $descError = "Description cannot be null";
    }
    if(empty($_POST['category_id'])){
        $catError = "Category cannot be null";
    }
    if(empty($_POST['quantity'])){
        $qtyError = "Quantity cannot be null";
    }elseif( !(is_numeric($_POST['quantity']))){
        $qtyError = "Quantity cannot be text";
    }
    if(empty($_POST['price'])){
        $priceError = "Price cannot be null";
    }elseif(!(is_numeric($_POST['price']))){
        $priceError = "Price cannot be text";
    }

    if(empty($_FILES['image'])){
        $imageError = "Image cannot be null";
    }

}else{
       
        $file = "images/".$_FILES["image"]['name'];      
        $imgType = pathinfo($file,PATHINFO_EXTENSION);

        if($imgType != 'jpg' && $imgType != 'png' && $imgType !='jpeg'){
            echo "<script>alert('image should be png,jpg,jpeg');</script>";
        }else{
            $name = $_POST["name"];
            $description = $_POST["description"];
            $cat_id = $_POST["category_id"];
            $quantity = $_POST["quantity"];
            $price = $_POST["price"];
            $tmpName = $_FILES['image']['tmp_name'];
            $image =$_FILES["image"]['name'];

            move_uploaded_file($tmpName,$file);

            $time = date('Y:m:d h:i:s',time());
            $stmt = $pdo->prepare("INSERT INTO products (name,description,category_id,quantity,price,image,created_at) VALUES (:name,:description,:category_id,:quantity,:price,:image,:created_at)");
           $result = $stmt->execute(
                array(':name'=> $name,':description'=>$description,':category_id'=> $cat_id ,':quantity'=>$quantity,':price'=>$price,':image'=>$image,':created_at'=>$time)
            );
             if($result){
                // echo "<script>alert('Insert Success !');window.location.href='index.php';</script>";
                header("location:index.php");
             }
        
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
                                    <form action="product_add.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                                        <div class="form-group">
                                            <label for="title">Name</label>
                                            <input type="text" class="form-control" name='name' require>
                                            <span class='text-danger'><?php echo empty($nameError) ? '' : $nameError; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description"require cols="30" rows="5" class="form-control"></textarea>
                                            <span class='text-danger'><?php echo empty($descError) ? '' : $descError; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Category</label>
                                            <select name="category_id"  class="form-control">
                                               <option value="">Select Category</option>
                                                <?php 
                                                    $catstmt = $pdo->prepare("SELECT * FROM categories");
                                                    $catstmt->execute();
                                                    $category = $catstmt->fetchAll();
                                                    foreach($category as $data){
                                                ?>
                                                <option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class='text-danger'><?php echo empty($catError) ? '' : $catError; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Quantity</label>
                                            <input type="number" class="form-control" name='quantity' require>
                                            <span class='text-danger'><?php echo empty($qtyError) ? '' : $qtyError; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Price</label>
                                            <input type="number" class="form-control" name='price' require>
                                            <span class='text-danger'><?php echo empty($priceError) ? '' : $priceError; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="file">Image</label>                     
                                            <input type="file" class="form-control-file" name='image' require>
                                            <span class='text-danger'><?php echo empty($imageError) ? '' : $imageError; ?></span>
                                        </div>
                                        <div class="mt-5">
                                            
                                            <input type="submit" class='btn btn-primary' value="SUBMIT">
                                            <a href="index.php" class="btn btn-outline-secondary mr-3">Back</a>

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