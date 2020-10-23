<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if(empty($_SESSION["user_id"] && empty($_SESSION["logged_in"])) && $_SESSION['role'] != 1){
   header("location:login.php");
}
$bestQuantity = 8;

$stmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id HAVING SUM(quantity)>:count ORDER BY id DESC");
$stmt->execute(array(':count'=>$bestQuantity));
$result = $stmt->fetchAll();

// echo '<pre>';
// print_r($result);exit();
?>

<?php require "header.php"; ?>
     
<div class="content">
<div class="container-fluid">
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header">
<h3 class="card-title">Best Seller</h3>
</div>
<div class="card-body">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Name</th>
                <th>Brand</th>
                <th> Price</th>
                <th>Product ID</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if($result){
                $i = 1;
                foreach($result as $value){ ?>
                <?php
                 $userstmt = $pdo->prepare("SELECT * FROM products  WHERE id=".$value['product_id']);
                 $userstmt->execute();
                 $user = $userstmt->fetchAll();
                    
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo escape($user[0]['name']);?></td>
                    <td><?php echo escape($user[0]['description']);?></td>
                    <td><?php echo escape($user[0]['price']) ;?></td>
                    <td><?php echo escape($value['product_id']);?></td>
                </tr>  

            <?php
            $i++;
                    }
            }
            ?>
        </tbody>
    </table>                
   
    </div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php require "footer.php"; ?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
    $('#d-table').DataTable();
} );
</script>