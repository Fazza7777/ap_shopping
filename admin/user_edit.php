<?php
session_start();
require "../config/config.php";
require "../config/common.php";


if(empty($_SESSION["user_id"] && $_SESSION["logged_in"]) && $_SESSION['role'] != 1){
   header("location:login.php");
}
$stmtCur= $pdo->prepare("SELECT * FROM users WHERE id=".$_GET["id"]);
$stmtCur->execute();
$currentAccount = $stmtCur->fetchAll();

//update account

if($_POST){
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4){
        if(empty($_POST['name'])){
           $nameError = "Name cannot be empty!";
        }
        if(empty($_POST['email'])){
           $emailError = "Email cannot be empty!";
        }
        if(empty($_POST['password'])){
           $passwordError = "Password cannot be empty!";
        }
         if(strlen($_POST['password']) < 4){
            $passwordError = 'Password should be 4 charcters at least';
        }
    }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        if(empty($_POST['role'])){
            $role = '0';
        }else{
            $role = '1';
        }
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id !=$id");
        $stmt->execute(array(':email'=>$email));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user){
            echo "<script>alert('Update email is already exit .');</script>";
        }else{
         $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password',role='$role' WHERE id='$id'");
        $update = $stmt->execute();
         if($update){
             header("location:user_list.php");
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
                    <div class="card-header">
                        <h4 class='text-success'>Edit Account</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="hidden"  name='id'  value="<?php echo $currentAccount[0]['id']; ?>">
                                <input type="text" class="form-control" name='name' require value="<?php echo escape($currentAccount[0]['name']); ?>">
                                <span class="text-danger"><?php echo empty($nameError) ? '' : $nameError ; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name='email' require value="<?php echo escape($currentAccount[0]['email']); ?>">
                                <span class="text-danger"><?php echo empty($emailError) ? '' : $emailError ; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Password</label>
                                <input type="password" class="form-control" name='password' require value="<?php echo escape($currentAccount[0]['password']); ?>">
                                <span class="text-danger"><?php echo empty($passwordError) ? '' : $passwordError ; ?></span>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="role" class="form-check-input" value="<?php echo $currentAccount[0]['role']; ?>" <?php echo $currentAccount[0]['role'] == '1' ? 'checked' : '';?>>
                                <label class="form-check-label" for="exampleCheck1"> Admin</label>
                                </div>

                            <div class="text-right">
                                <a href="index.php" class="btn btn-outline-secondary mr-3">Back</a>
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