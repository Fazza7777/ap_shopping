<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if(empty($_SESSION["user_id"] && empty($_SESSION["logged_in"])) && $_SESSION['role'] != 1){
   header("location:login.php");
}

?>

<?php require "header.php"; ?>
     
<div class="content">
<div class="container-fluid">
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header">
<h3 class="card-title">Order Detail</h3>
</div>
<?php
if(!empty($_GET["pageno"])){
    $pageno = $_GET["pageno"];
}else{
    $pageno = 1;
}
$numOfrecord = 3;
$offset = ($pageno - 1) * $numOfrecord ;

$getid =$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=$getid");
$stmt->execute();
$rawResult= $stmt->fetchAll();
$total_pages = ceil(count($rawResult) / $numOfrecord);

$stmt = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=$getid LIMIT $offset,$numOfrecord ");
$stmt->execute();
$result = $stmt->fetchAll();
 
?>
<div class="card-body">
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if($result){
                $i = 1;
                foreach($result as $orderdetail){ ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <?php
                       $pStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$orderdetail['product_id']);
                       $pStmt->execute();
                       $product =  $pStmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <td><?php echo escape($product['name']);?></td>
                   
                    <td><?php echo escape($orderdetail['quantity']);?></td>
                    <td><?php echo escape(date("Y-m-d",strtotime($orderdetail['order_date'])));?></td>
                    <td class='text-center'>
                        <a href="order_list.php" class="btn btn-danger text-white mr-2"> <i class="fa fa-arrow-alt-circle-left"></i> Back</a>
                       
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