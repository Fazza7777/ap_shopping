<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if(empty($_SESSION["user_id"] && empty($_SESSION["logged_in"])) && $_SESSION['role'] !=1){
   header("location:login.php");
}
if(empty($_POST["search"])){
    if(empty($_GET['pageno'])){
        unset($_COOKIE['search']);
        setcookie('search',null,-1,"/");
    } 
}else{
    setcookie('search',$_POST['search'],time() + (8600 * 30), "/");
}
if(!empty($_GET["pageno"])) $pageno = $_GET["pageno"];
else $pageno = 1;
$numOfrecord = 3;
$offset = ($pageno - 1) * $numOfrecord ;
if(empty($_POST["search"]) && empty($_COOKIE["search"])){
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    $rawUsers = $stmt->fetchAll();
    $total_pages = ceil(count($rawUsers) / $numOfrecord);

    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfrecord ");
    $stmt->execute();
    $users = $stmt->fetchAll();
 }else{
    $searchKey = empty($_POST['search']) ?  $_COOKIE['search'] : $_POST['search'] ;
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    $rawUsers = $stmt->fetchAll();

    $total_pages = ceil(count($rawUsers) / $numOfrecord);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecord ");
    $stmt->execute();
    $users = $stmt->fetchAll();
}
?>

<?php require "header.php"; ?>
     
<div class="content">
    <div class="container-fluid">
    <div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User Account Detail</h3>
        </div>
        <div class="card-body">
            <a href="user_add.php" class="btn btn-success mb-3">Create New User</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($users){
                        $i = 1;
                        foreach($users as $user){ ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo escape($user['name']);?></td>
                            <td><?php echo escape(substr($user['email'],0,50));?></td>
                            <td><?php echo $user['role'] == 1 ? '<span class=\'text-success text-bold \'>admin </span>' : '<span class=\'text-info text-bold \'>user </span>';?></td>
                            <td class='text-center'>
                                <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-primary mr-2">Edit</a>
                                <a href="user_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger " onclick="return confirm('Are you sure delete')">Delete</a>
                            </td>
                        </tr>  

                    <?php
                    $i++;
                            }
                    }
                    ?>
                    
                </tbody>
            </table>
            <nav aria-label="Page navigation  ">
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
        <!-- /.row -->
    </div>
    </div>
</div>

<?php require "footer.php"; ?>