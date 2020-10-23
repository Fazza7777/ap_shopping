<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if(empty($_SESSION["user_id"] && empty($_SESSION["logged_in"])) && $_SESSION['role'] != 1){
   header("location:login.php");
}
$royalCount = 700000;

$stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE total_price >:royalcount");
$stmt->execute(array(':royalcount'=>$royalCount));
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
<h3 class="card-title">Royal Customer</h3>
</div>
<div class="card-body">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Name</th>
                <th>Total Price</th>
                <th>Order Date</th>
            
            </tr>
        </thead>
        <tbody>
        <?php
            if($result){
                $i = 1;
                foreach($result as $value){ ?>
                <?php
                 $userstmt = $pdo->prepare("SELECT * FROM users  WHERE id=".$value['user_id']);
                 $userstmt->execute();
                 $user = $userstmt->fetchAll();
                    
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo escape($user[0]['name']);?></td>
                    <td><?php echo escape($value['total_price']);?></td>
                    <td><?php echo escape(date("Y-m-d",strtotime($value['order_date'])));?></td>
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