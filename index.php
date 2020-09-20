<?php require "header.php"; ?>
<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
require "config/config.php";
require "config/common.php";

if(empty($_POST['search'])){
    if(empty($_GET["pageno"])){
        unset($_COOKIE['search']);
        setcookie('search',null,-1,"/");
        }
}else{
    setcookie('search',$_POST['search'],time() + (8600 * 30), "/");
}

if(!empty($_GET["pageno"])){
    $pageno = $_GET["pageno"];
}else{
    $pageno = 1;
}
$numOfrecord = 3;
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

    $stmt = $pdo->prepare("SELECT * FROM products  WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecord ");
    $stmt->execute();
    $result = $stmt->fetchAll();
}
?>
<div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="sidebar-categories">
                    <div class="head">Browse Categories</div>
                    <ul class="main-categories">
                        <li class="main-nav-list">
                         <?php
                            $catStmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
                             $catStmt->execute();
                             $categories = $catStmt->fetchAll();
                          
                             foreach($categories as $category){ ?>   
                             <a data-toggle="collapse" href="#meatFish" ><spanclass="lnr lnr-arrow-right"></span><?php echo escape($category['name']); ?></a>
                        <?php } ?>
                      </li>

                </div>

            </div>
            <div class="col-xl-9 col-lg-8 col-md-7">
                <!-- Start Filter Bar -->
                <div class="filter-bar d-flex flex-wrap align-items-center">

                    <div class="pagination">
                        <a href="?pageno=1" >First</a>
                        <a class="<?php echo $pageno <= 1 ? 'disabled' : '' ?>" href="<?php echo $pageno <= 1? '#' : '?pageno='.($pageno-1); ?>" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
                        <a href="#" class="active"><?php echo $pageno; ?></a></a>
                        <a class="<?php echo $pageno >= $total_pages ? 'disabled' : ''; ?>" href="<?php echo $pageno >= $total_pages ? '#' : '?pageno='.($pageno+1);  ?>" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                        <a href="?pageno=<?php echo $total_pages; ?>" >Last</a>
                    </div>
                </div>
                <!-- End Filter Bar -->
<!-- Start Best Seller -->
<section class="lattest-product-area pb-40 category-list">
    <div class="row">
       <?php
        // if($products){
           //  foreach($products as $key => $val){?>
            <!-- single product -->
            <div class="col-lg-4 col-md-6">
                <div class="single-product">
                    <img class="img-fluid" src="img/product/p1.jpg" alt="">
                    <div class="product-details">
                        <h6>addidas New Hammer sole for Sports person</h6>
                        <div class="price">
                            <h6>$150.00</h6>
                            <h6 class="l-through">$210.00</h6>
                        </div>
                        <div class="prd-bottom">

                            <a href="" class="social-info">
                                <span class="ti-bag"></span>
                                <p class="hover-text">add to bag</p>
                            </a>

                            <a href="" class="social-info">
                                <span class="lnr lnr-move"></span>
                                <p class="hover-text">view more</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- single product -->
        <?php
          //  }
       //  }
        ?>
    </div>
</section>
<!-- End Best Seller -->

<?php require "footer.php"; ?>