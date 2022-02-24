<?php
	include_once('../db/db.php');
	session_start();
?>
<!-- Header that will be used for all pages -->
<header id="nav-header">
	<a href="../pages/homepage.php">
		<img src="../images/gameGo.png" alt="logo" class="logo">
	</a>
		<form method="GET" action="" class="search-bar-form">
			<!-- set value of the search bar to be what the user input -->
			<input type="text" placeholder="Search..." class="search-bar-box" name="search" required value="<?php if(isset($_GET['search'])) {echo $_GET['search'];} ?>">
			<input type="submit" value="&#xF002;" class="search-bar-button">
		</form>
			<?php
				if(isset($_SESSION["customerID"])) { //If user logs in greet them
					echo "Hi " . $_SESSION["firstName"] . "!";
				}
			?>
			<a href="../pages/homepage.php" class="a-nav"><i class='bx bxs-home' ></i>Home</a>
			<?php
				if(isset($_SESSION["customerID"])) { //If there is logged in change link to logout
					echo "
					<a href='../db/logout.php' class='a-nav'><i class='bx bxs-user'></i>Logout</a>
					";
				} else {
					echo "
					<a href='../pages/login.php' class='a-nav'><i class='bx bxs-user'></i>Login</a>
					";
				}
			?>
			<a href="../pages/ShoppingCart.php" class="a-nav"><i class='bx bxs-cart'></i>Cart</a>
</header>