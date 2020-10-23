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
<h3 class="card-title">Order Listing</h3>
</div>
<?php
if(!empty($_GET["pageno"])){
    $pageno = $_GET["pageno"];
}else{
    $pageno = 1;
}
$numOfrecord = 3;
$offset = ($pageno - 1) * $numOfrecord ;

$stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
$stmt->execute();
$rawResult= $stmt->fetchAll();
$total_pages = ceil(count($rawResult) / $numOfrecord);

$stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfrecord ");
$stmt->execute();
$result = $stmt->fetchAll();
 
?>
<div class="card-body">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>User</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if($result){
                $i = 1;
                foreach($result as $order){ ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <?php
                       $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$order['user_id']);
                       $userStmt->execute();
                       $user =  $userStmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <td><?php echo escape($user['name']);?></td>
                   
                    <td><?php echo escape($order['total_price']);?></td>
                    <td><?php echo escape(date("Y-m-d",strtotime($order['order_date'])));?></td>
                    <td class='text-center'>
                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-info text-white mr-2"> <i class="fa fa-eye"></i> View</a>
                       
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