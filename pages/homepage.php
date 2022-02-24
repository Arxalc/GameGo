<?php
	include_once('../db/db.php');
	session_start();
	
	/* Assign session variables if cookie set...*/
	if (isset($_COOKIE["userIDcookie"])) {  
			$query = $conn->prepare("SELECT * FROM customers WHERE customerID = ?");
			$query->bind_param("i", $custIDParam);
			$custIDParam = $_COOKIE['userIDcookie'];
			$query->execute();
				$result = $query->get_result();      
            
				$row = mysqli_fetch_assoc($result);		            
				$_SESSION["email"] = $row['email'];
				$_SESSION["password"] = $row['password'];
				$_SESSION["firstName"] = $row['firstName'];
				$_SESSION["lastName"] = $row['lastName'];
				$_SESSION["customerID"] = $row['customerID']; 
	}
	if (isset($_POST['gotoLogin.x']) && $_POST['gotoLogin_x'] !='' ) { // Suppose to redirect to login page 
		header('Location: login.php');
	}

		// Checks if user is logged in when trying to add to cart
		if (isset($_POST['productID'])) { 
			if (isset($_SESSION['customerID'])) {
				// continue
			} else if (isset($_COOKIE['userIDcookie'])) {
				// continue
			} else { // Not logged in
				header('Location: login.php');
			}

		$query = $conn->prepare("INSERT INTO `cart` (`customerID`, `productID`, `subTotal`, `quantity`) 
		VALUES (?, ?, ?, ?)");
		$query->bind_param("iidi", $custID, $prodID, $prodPrice, $quant);
      		$custIDParam = $_SESSION['customerID'];
		$custID = $_SESSION['customerID'];
		$prodID = $_POST['productID'];
		$prodPrice = $_POST['productPrice'];
		$quant = 1; // Change quantity.
		$query->execute(); 
		echo '<script>alert("Added to cart!")</script>';
	}

?>

<!DOCTYPE html>
<html lang="en">

<!-- TODO: Change this file name to index.php once everything is done -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
	<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>GameGo Homepage</title>
	<link rel="icon" type="image/png" href="../images/favicon.png"/>

	<!-- This is used to load header from header.html file -->
	<script> 
		$(function(){
		$("#header").load("header.php"); 
		});
    </script> 

</head>

<body>
	<!-- nav bar inserted with JQuery -->
	<div id="header"></div>
	
	<!-- console filter -->
	<form class = "filter-form" action="" method="GET"> 
			<h4> Consoles </h4>
				<?php
				$query = "SELECT * FROM brands";
				$result = $conn->query($query);
				while($row = mysqli_fetch_assoc($result)) {
					$checked = [];
					if(isset($_GET['brands'])) {
						$checked = $_GET['brands'];
					}
				?>   	
						<div id ="checkbox-container" name="checkbox-container" class="checkbox-container">
								<label for="gamebox">
									<input type="checkbox" name="brands[]" class="filter-cb" id="gamebox" value="<?= $row['brandID'];?>"
									<?php if(in_array($row['brandID'], $checked)) { echo "checked";} ?>
									/><?= $row['brandName']; ?>
								</label>
						</div>
				<?php                     
				}
				?>
			<input type="submit" value="Search" class="cart-button">
	</form>
    
    <!-- Product listings -->
    <div class = "product-container" id="result">
        <?php
		if(isset($_GET['brands'])) { //display console filter results
			
			$filtersearch = [];
			$filtersearch = $_GET['brands'];
			foreach($filtersearch as $brow) {
				$searchQuery = "SELECT * FROM products LEFT JOIN brands ON products.brandID = brands.brandID WHERE brands.brandID = $brow";
				$searchRun = mysqli_query($conn, $searchQuery);

				if(mysqli_num_rows($searchRun) > 0) {
					foreach($searchRun as $prod) :
						?>
							<div class="product-card">
							<h4 class="product-name"><?= $prod["productName"]; ?></h4>
							<div class="product-img"> 
								<img src="<?= $prod["productImage"];?>" alt="<?= $prod["productImageAlt"];?>" height="250">
							</div>
							<h4 class="product-price">$<?= $prod["productPrice"]; ?></h4>
							<form method="POST" action="homepage.php">
								<input type="hidden" name="productID" value="<?=$prod["productID"]; ?>" /> 
								<input type="hidden" name="productPrice" value="<?=$prod["productPrice"]; ?>" />
								<input type="submit" value="Add to Cart" class="cart-button">
							</form>
						</div>
						<?php
					endforeach;
				} else {
					echo "No games found.";
				}
			}


		} elseif(isset($_GET['search'])) { //display search results 
				$filtersearch = "%{$_GET['search']}%";
				$searchQuery = "SELECT * FROM products WHERE productName LIKE ?";
                //prepare and bind filtersearch query
				$stmt = $conn->prepare($searchQuery);
                $stmt->bind_param("s",$filtersearch);
                $stmt->execute();
                $queryResult = $stmt->get_result();
				$numrow = mysqli_num_rows($queryResult);

				if(mysqli_num_rows($queryResult) > 0) {
					echo $numrow. " results for '" .$_GET['search']. "'.";
					while($prod = mysqli_fetch_assoc($queryResult)) {
						?>
						<div class="product-card">
							<h4 class="product-name"><?= $prod["productName"]; ?></h4>
							<div class="product-img"> 
								<img src="<?= $prod["productImage"];?>" alt="<?= $prod["productImageAlt"];?>" height="250">
							</div>
							<h4 class="product-price">$<?= $prod["productPrice"]; ?></h4>
							<form method="POST" action="homepage.php">
								<input type="hidden" name="productID" value="<?=$prod["productID"]; ?>" /> 
								<input type="hidden" name="productPrice" value="<?=$prod["productPrice"]; ?>" />
								<input type="submit" value="Add to Cart" class="cart-button">
							</form>
						</div>
					<?php
					}
				} else {
					echo "There are no search results for '" .$_GET['search']. "'.";
				}
			} else { //display all products
				$query = "SELECT * FROM products ORDER BY productID ASC";
				$result = $conn->query($query);
					while($games = mysqli_fetch_assoc($result)) {
						?>
							<div class="product-card">
								<h4 class="product-name"><?= $games["productName"]; ?></h4>
								<div class="product-img"> 
									<img src="<?= $games["productImage"];?>" alt="<?= $games["productImageAlt"];?>" height="250">
								</div>
								<h4 class="product-price">$<?= $games["productPrice"]; ?></h4>
								<form method="POST" action="homepage.php">
									<input type="hidden" name="productID" value="<?=$games["productID"]; ?>" /> 
									<input type="hidden" name="productPrice" value="<?=$games["productPrice"]; ?>" />
									<input type="submit" value="Add to Cart" class="cart-button">
								</form>
							</div>
						<?php
					}
			}
		mysqli_close($conn);
        ?>
    </div>
</body>
</html>
