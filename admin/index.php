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
<?php
if(!empty($_GET["pageno"])){
    $pageno = $_GET["pageno"];
}else{
    $pageno = 1;
}
$numOfrecord = 1;
$offset = ($pageno - 1) * $numOfrecord ;

if(empty($_POST["search"]) && empty($_COOKIE["search"])){
    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();
    $total_pages = ceil(count($rawResult) / $numOfrecord);

    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfrecord ");
    $stmt->execute();
    $result = $stmt->fetchAll();
 }else{
    $searchKey = empty($_POST['search']) ? $_COOKIE["search"] : $_POST['search'] ;
    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();

    $total_pages = ceil(count($rawResult) / $numOfrecord);

    $stmt = $pdo->prepare("SELECT * FROM products  WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecord ");
    $stmt->execute();
    $result = $stmt->fetchAll();
}
?>
<div class="card-body">
    <a href="product_add.php" class="btn btn-success mb-3">New Product</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>In Stock</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($result){
                $i = 1;
                foreach($result as $product){ ?>
                <?php
                 $catstmt = $pdo->prepare("SELECT * FROM categories  WHERE id=".$product['category_id']);
                 $catstmt->execute();
                 $category = $catstmt->fetchAll();
                    
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo escape($product['name']);?></td>
                    <td><?php echo escape(substr($product['description'],0,30));?></td>
                    <td><?php echo escape($category[0]['name']);?></td>
                    <td><?php echo escape($product['quantity']);?></td>
                    <td><?php echo escape($product['price']);?></td>
                    <td class='text-center'>
                        <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-primary mr-2">Edit</a>
                        <a href="product_delete.php?id=<?php echo $product['id']; ?>" class="btn btn-danger " onclick="return confirm('Are you sure delete')">Delete</a>
                    </td>
                </tr>  

            <?php
            $i++;
                    }
            }
            ?>
            
        </tbody>
    </table>                
    <nav aria-label="Page navigation">
        <ul class="pagination mt-3">
            <li class="page-item "><a class="page-link" href="?pageno=1">First</a></li>
            <li class="page-item <?php echo $pageno <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?php echo $pageno <= 1? '#' : '?pageno='.($pageno-1); ?>">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
            <li class="page-item <?php echo $pageno >= $total_pages ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $pageno >= $total_pages ? '#' : '?pageno='.($pageno+1);  ?>">Next</a>
            </li>
            <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
        </ul>
    </nav>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php require "footer.php"; ?>