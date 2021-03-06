<?php
require "config/config.php";

?>
<?php require "header.php"; ?>
<?php

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
$numOfrecord = 6;
$offset = ($pageno - 1) * $numOfrecord ;

if(empty($_POST["search"]) && empty($_COOKIE["search"])){
    if(!(empty($_GET['id']))){
            $getId = $_GET['id'];   
            $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=$getId  ORDER BY id DESC");
            $stmt->execute();
            $rawResult = $stmt->fetchAll();
        
            $total_pages = ceil(count($rawResult) / $numOfrecord);
        
            $stmt = $pdo->prepare("SELECT * FROM products  WHERE category_id=$getId AND quantity>0 LIMIT $offset,$numOfrecord ");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }else{
          
            $stmt = $pdo->prepare("SELECT * FROM products WHERE quantity>0 ORDER BY id DESC");
            $stmt->execute();
            $rawResult = $stmt->fetchAll();
        
            $total_pages = ceil(count($rawResult) / $numOfrecord);
        
            $stmt = $pdo->prepare("SELECT * FROM products WHERE quantity>0 ORDER BY id DESC LIMIT $offset,$numOfrecord ");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
}else{
    $searchKey = empty($_POST['search']) ? $_COOKIE["search"] : $_POST['search'] ;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%'   ORDER BY id DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();

    $total_pages = ceil(count($rawResult) / $numOfrecord);

    $stmt = $pdo->prepare("SELECT * FROM products  WHERE name LIKE '%$searchKey%' AND quantity>0 ORDER BY id DESC LIMIT $offset,$numOfrecord ");
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
                             <a  href="index.php?id=<?php echo escape($category['id']); ?>" ><spanclass="lnr lnr-arrow-right"></span><?php echo escape($category['name']); ?></a>
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
            if($result){
            foreach($result as $key => $product){?>
            <!-- single product -->
            <div class="col-lg-4 col-md-6">
                <div class="single-product">
                   <a href="product_detail.php?category_id=<?php echo escape($product['id']); ?>"> <img  height=230 src="admin/images/<?php echo escape($product['image']); ?>" alt="img not found"></a>
                    <div class="product-details">
                        <h6><?php echo escape($product['name']); ?></h6>
                        <div class="price">
                            <h6><?php echo escape($product['price']); ?></h6>
                          
                        </div>
                        <div class="prd-bottom">
                          <form action="addtoCart.php" method='post'>     
                              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                              <input type="hidden" value='<?php echo escape($product['id']); ?>' name='id'>
                              <input type="hidden" value='1' name='qty'>
                            <div class="social-info">
                                <button type="submit" style='display:contents;'class="social-info"> 
                                    <span class="ti-bag"></span><p style='left:20px;' class="hover-text">add to bag</p>
                                </button>
                            </div>
                            <a href="product_detail.php?category_id=<?php echo escape($product['id']); ?>" class="social-info">
                                <span class="lnr lnr-move"></span>
                                <p class="hover-text">view more</p>
                            </a>
                          </form>
                          
                        </div>
                    </div>
                </div>
            </div>
            <!-- single product -->
        <?php
           }
        }
        ?>
    </div>
</section>
<!-- End Best Seller -->

<?php require "footer.php"; ?>