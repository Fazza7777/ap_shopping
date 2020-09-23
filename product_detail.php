<?php
require "config/config.php";

  $cat_id = $_GET['category_id'];
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$cat_id");
  $stmt->execute();
  $result = $stmt->fetchAll();

?>

<?php require "header.php"; ?>

	<!--================Single Product Area =================-->
	<div class="product_image_area mb-4 pt-0">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
				<img height=330 src="admin/images/<?php echo escape($result[0]['image']);?>" alt="">
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3><?php echo escape($result[0]['name']);?></h3>
						<h2><?php echo escape($result[0]['price']) ;?></h2>
						<ul class="list">
							
							<li><a href="#"><span>Availibility</span> : In Stock</a></li>
						</ul>
						<p><?php echo escape($result[0]['description']);?></p>
						<form action="addtoCart.php" method='post'>
						<input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
						<input type="hidden" name='id' value='<?php echo escape($result[0]['id']);?>'>
							<div class="product_count">
								<label for="qty">Quantity:</label>
								<input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
								<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
								class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
								<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
								class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
							</div>
							<div class="card_area d-flex align-items-center">
								<button  class="primary-btn border-0" href="#">Add to Cart</button>
								<a class="primary-btn" href="index.php">Back</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--================End Single Product Area =================-->


<?php require "footer.php"; ?>