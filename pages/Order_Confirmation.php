<?php
	include_once('../db/db.php');
	session_start();
	
	// Prevents people from accessing other pages until they log in.
	if (!isset($_SESSION["customerID"])) {
		echo '<script>alert("Log in to access cart!")</script>';
        header('Location: homepage.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	<link rel="stylesheet" href="../css/style.css">
	<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>Order Confirmation</title>
	<link rel="icon" type="image/png" href="../images/favicon.png"/>
	<script> 
		$(function(){
		$("#header").load("header.php"); 
		});
    </script> 
	<style>
	h1 {
	font-weight: 900;
	color: green;
}
	</style>
	</head>
	<body>
	<!-- nav bar inserted with JQuery-->
	<div id="header"></div>


	<div class="form-wrapper">
		<div class="container">
			<h1>Your order has been processed!</h1><br><br>
			<h2>We're hard at work delivering your favorite goods to you on time.'</h2><br><br>
			<h3>Questions? Concerns? Contact our support team at GameGoSupport@gmail.com</h3><br><br>
			<a href="homepage.php">
				<button class="button">Return to shopping</button></a>
		</div>
	</div>

	</body>

</html>
