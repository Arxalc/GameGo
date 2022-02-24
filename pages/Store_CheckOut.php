<?php
	include_once('../db/db.php');
	session_start();
    $cookies = false;
    if (!isset($_SESSION["customerID"])) {
        header('Location: homepage.php');
    }
	if (isset($_COOKIE['userIDcookie'])) { // ?      
            $query = $conn->prepare("SELECT * FROM orderpayment WHERE customerID = ?");
            $query->bind_param("i", $custID);
            $custID = $_COOKIE['userIDcookie'];          
            $query->execute();
            $result = $query->get_result();      
            $numrows = mysqli_num_rows($result);

            
            if ($numrows == 1) { // Email and password found in database               	
	            while($row = mysqli_fetch_assoc($result)) {		            
		            $_SESSION["cardNum"] = $row['cardNum'];
                    $_SESSION["ccvNum"] = $row['ccvNum'];
		            $_SESSION["cardholderName"] = $row['cardholderName'];
		            $_SESSION["state"] = $row['state'];
                    $_SESSION["address"] = $row['address'];
                    $_SESSION["city"] = $row['city'];
                    $_SESSION["zipCode"] = $row['zipCode'];
                    $cookies = true;
  	            }                 
            }      
	}

    if (isset($_POST['comfPayment'])) {
        $query = $conn->prepare("DELETE FROM cart WHERE customerID = ?");
        $query->bind_param("i", $custID);
        $custID = $_SESSION["customerID"];
        $query->execute();
        header('Location: Order_Confirmation.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<!-- This is used to load header from header.html file -->
	<script> 
		$(function(){
		$("#header").load("header.php"); 
		});
    </script> 
    <script type="text/javascript">
        var cookiesDetected = "<?php echo $cookies; ?>";
        if (cookiesDetected == true) {
            $(document).ready(function(){
            var name = echo "<?php echo $_SESSION['cardholderName']; ?>";
            var cardNum = echo "<?php echo $_SESSION['cardNum']; ?>";
            var ccvNum = echo "<?php echo $_SESSION['ccvNum']; ?>";
            var state = echo "<?php echo $_SESSION['state']; ?>";
            var city = echo "<?php echo $_SESSION['city']; ?>";;
            var address = echo "<?php echo $_SESSION['address']; ?>";
            var zipCode = echo "<?php echo $_SESSION['zipCode']; ?>";

            $("#cardName").val(name).prop("readonly", false);
            $("#cardNum").val(cardNum).prop("readonly", false);
            $("#ccvNum").val(ccvNum).prop("readonly", false);
            $("#state").val(state).prop("readonly", false);
            $("#address").val(address).prop("readonly", false);
            $("#city").val(city).prop("readonly", false);
            $("#zipCode").val(zipCode).prop("readonly", false);

            $("#comfName").val(name).prop("readonly", false);
            $("#comfState").val(state).prop("readonly", false);
            $("#comfCity").val(city).prop("readonly", false);
            $("#comfAddress").val(address).prop("readonly", false);
            $("#comfZipCode").val(zipCode).prop("readonly", false);

        });
    };
        
    </script>
    <!-- Copies over information from payment side over to shipping textboxes -->
    <script type="text/javascript">
    $(document).ready(function(){
        $("#copyInfo").on("click", function () {
            var name;
            var state;
            var city;
            var address;
            var zipCode;

            if ($(this).is(":checked")) {
                name = $('[name="cardName"]').val();
                state = $('[name="state"]').val();
                city = $('[name="city"]').val();
                address = $('[name="address"]').val();
                zipCode = $('[name="zipCode"]').val();
                $("#comfName").val(name).prop("readonly", false);
                $("#comfState").val(state).prop("readonly", false);
                $("#comfCity").val(city).prop("readonly", false);
                $("#comfAddress").val(address).prop("readonly", false);
                $("#comfZipCode").val(zipCode).prop("readonly", false);

            } else {
                $("#comfName").val("").prop("readonly", false);
                $("#comfState").val("").prop("readonly", false);
                $("#comfCity").val("").prop("readonly", false);
                $("#comfAddress").val("").prop("readonly", false);
                $("#comfZipCode").val("").prop("readonly", false);
            }
        });
    });
    </script>
        <title>Check out</title>
        <link rel="icon" type="image/png" href="../images/favicon.png"/>
        <style>
        .checkoutMain {
            margin-left: 30px;
        }
        .inputTextCO {
            font-size: 18px;
            width: 280px;
            border: 1px solid black;
        }
        </style>
    </head>
    
    <body> 
    <!-- nav bar inserted with JQuery-->
	<div id="header"></div>
    
<div class="checkoutMain">
<form action="Store_Checkout.php" id="checkoutForm" method="POST" onsubmit="return validatePayment()"> 
    <div class="checkoutSide"> 
	<h2>Payment Method</h2>	
    <br>
    <input type="cardName"  class="inputTextCO" id="cardName" name="cardName" placeholder="Name on card"></input>
    <br>
    <span class="error" id="cardNameErr"></span>
    <br>
  	<input type="text" class="inputTextCO" id="cardNum" name="cardNum" placeholder="Card Number">
    <br>
    <span class="error" id="cardNumErr"></span>
    <br>
  	<input type="text" class="inputTextCO" id="ccvNum" name="ccvNum" placeholder="CCV Number">
    <br>
    <span class="error" id="ccvNumErr"></span>
    <br>
	<label for="expDate">Expiration Date</label>
    <input type="month" id="expDate" class="expDate">
    <br>
    <span class="error" id="expDateErr"></span>
    <br>
  	<input type="text" class="inputTextCO" id="state" name="state" placeholder="State">
    <br>
	<span class="error" id="stateErr"></span>
    <br>
  	<input type="text" class="inputTextCO" id="city" name="city" placeholder="City">
      <br>
      <span class="error" id="cityErr"></span>
      <br>
  	<input type="text" class="inputTextCO" id="address" name="address" placeholder="Address">
      <br>
      <span class="error" id="addressErr"></span>
      <br>
  	<input type="text" class="inputTextCO" id="zipCode" name="zipCode" placeholder="Zip Code">
      <br>
      <span class="error" id="zipCodeErr"></span>
      <br>
	<label>Shipping Speed</label><br>
            <input type="radio" name="standardShip"> <label>FREE Standard Shipping (5-7 business days)</label><br>
            <input type="radio" name="expressShip"> <label>$5.99 Express Shipping (2-3 business days) </label><br><br>          
 
     </div>

	<div class="checkoutSide">
            <h2>Shipping Address</h2>
            <br>
                <input type="text" id="comfName" class="inputTextCO" name="comfName" placeholder="Name">
                <br>
                <span class="error" id="comfNameErr"></span>
                <br>
                <input type="text" id="comfState" class="inputTextCO" name="comfState" placeholder="State">
                <br>
                <span class="error" id="comfStateErr"></span>
                <br>
              
  	            <input type="text" id="comfCity" class="inputTextCO" name="comfCity" placeholder="City">
                  <br>
                  <span class="error" id="comfCityErr"></span>
                  <br>
	    
  	            <input type="text" id="comfAddress" class="inputTextCO" name="comfAddress" placeholder="Address">
                  <br>
                  <span class="error" id="comfAddressErr"></span>
                  <br>
  	            <input type="text" id="comfZipCode" class="inputTextCO" name="comfZipCode" placeholder="Zip Code">
                  <br>
                  <span class="error" id="comfZipCodeErr"></span>
                  <br><br>
                <input type="checkbox" id="copyInfo" name="copyInfo">
                <label for="copyInfo">Same as billing info</label><br><br>
             
			<h3>Total Cost</h3> 
            <h1>$<?php echo $_POST['subtotalHide']; ?> </h1><br>
            <input type="submit" name="comfPayment" value="Confirm Payment" class="button">
    </div>
    </form>
</div>

<script>
        // Validate payment inputs
        function validatePayment() {
            
            var cardNameErr = "";
            var cardNumErr = "";
            var ccvNumErr = "";
            var expDateErr = ""; 
            var stateErr = "";
            var cityErr = "";
            var addressErr = "";
            var zipCodeErr = "";

            var cardName = document.getElementById("cardName").value;
            var cardNum = document.getElementById("cardNum").value;
            var ccvNum = document.getElementById("ccvNum").value;
            var expDate = document.getElementById("expDate").value;
            var state = document.getElementById("state").value;
            var city = document.getElementById("city").value;
            var address = document.getElementById("address").value;
            var zipCode = document.getElementById("zipCode").value;


            // Card Name validation
            if (cardName == "") { // trim
                    document.getElementById("cardNameErr").innerHTML = "Please enter name on card.";
            } else {
                    document.getElementById("cardNameErr").innerHTML = "";
            }
            // Card Number validation
            if (cardNum == "") {
                document.getElementById("cardNumErr").innerHTML = "Please enter card number.";
            } else {
                document.getElementById("cardNumErr").innerHTML = "";
            }
            // CCV Number validation
            if (ccvNum == "") {
                    document.getElementById("ccvNumErr").innerHTML = "Please enter CCV number.";
            } else {
                document.getElementById("ccvNumErr").innerHTML = "";
            }           
            // Expiration Date validation
            if (expDate == "" || expDate == "-------") {
                document.getElementById("expDateErr").innerHTML = "Please enter the card's expiration date.";
            } else {
                document.getElementById("expDateErr").innerHTML = "";
            }
                
            
            // State validation
            if (state == "") { // trim
                    document.getElementById("stateErr").innerHTML = "Please enter the state.";
            } else {
                    document.getElementById("stateErr").innerHTML = "";
            }
            // City validation
            if (city == "") { // trim
                    document.getElementById("cityErr").innerHTML = "Please enter the city.";
            } else {
                    document.getElementById("cityErr").innerHTML = "";
            }
            // Address validation
            if (address == "") { // trim
                    document.getElementById("addressErr").innerHTML = "Please enter the address.";
            } else {
                    document.getElementById("addressErr").innerHTML = "";
            }
 
            if (zipCode == "") { // trim
                document.getElementById("zipCodeErr").innerHTML = "Please enter the zip code.";
            } else {
                document.getElementById("zipCodeErr").innerHTML = "";
            }
            if (cardName == "" || cardNum == "" ||  ccvNum == "" ||  expDate == "" || expDate == "-------" || state == "" || city == "" || address == "" || zipCode == "") {
                
                return false;
            }
            
            // Card Number Validation
            if (/^\d+$/.test(cardNum) == false) {
                document.getElementById("cardNumErr").innerHTML = "Card number must only contain numbers. No other characters.";
                return false;
            } else if (cardNum.length != 15 && cardNum.length != 16) {
                document.getElementById("cardNumErr").innerHTML = "Invalid credit card number.";
                return false;
            } else {
                document.getElementById("passwordErr").innerHTML = "";
            }
            // CCV Number Validation
            if (/^\d+$/.test(cardNum) == false) {
                document.getElementById("ccvNumErr").innerHTML = "CCV number must only contain numbers. No other characters.";
                return false;
            } else if (ccvNum.length != 3) {
                document.getElementById("ccvNumErr").innerHTML = "Invalid CCV number.";
                return false;
            } else {
                document.getElementById("ccvNumErr").innerHTML = "";
            }    
            // Expiration Date Validation
            if (/^\d+$/.test(expDate.substring(0, 4) == false) || /^\d+$/.test(expDate.substring(5, 7) == false)) {
                document.getElementById("expDateErr").innerHTML = "Invalid expiration date.";
                return false;
            } else {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = yyyy + '-' + mm;

                if (expDate < today) { // test
                    document.getElementById("expDateErr").innerHTML = "Card has expired!";
                    return false;
                } else {
                    document.getElementById("expDateErr").innerHTML = "";
                }
            }
             // Zip Code validation
            if (/^\d+$/.test(zipCode) == false) {
                document.getElementById("zipCodeErr").innerHTML = "Zip code must only contain numbers. No other characters.";
                return false;
            } else if (zipCode.length != 5) {
                document.getElementById("zipCodeErr").innerHTML = "Invalid zip code.";
                return false;
            } else {
                document.getElementById("zipCodeErr").innerHTML = "";
            }
            
            
        } // validatePayment

        // Validate shipping inputs
        function validateShipping() {
            var nameErr = "";     
            var stateErr = "";
            var cityErr = "";
            var addressErr = "";
            var zipCodeErr = "";


            var name = document.getElementById("comfName").value;
            var state = document.getElementById("comfState").value;
            var city = document.getElementById("comfCity").value;
            var address = document.getElementById("comfAddress").value;
            var zipCode = document.getElementById("comfZipCode").value;

            // Name validation
            if (name == "") { // trim
                    document.getElementById("comfNameErr").innerHTML = "Please enter name.";
            } else {
                    document.getElementById("comfNameErr").innerHTML = "";
            }         
            // State validation
            if (state == "") { // trim
                    document.getElementById("comfStateErr").innerHTML = "Please enter the state.";
            } else {
                    document.getElementById("comfStateErr").innerHTML = "";
            }
            // City validation
            if (city == "") { // trim
                    document.getElementById("comfCityErr").innerHTML = "Please enter the city.";
            } else {
                    document.getElementById("comfCityErr").innerHTML = "";
            }
            // Address validation
            if (address == "") { // trim
                    document.getElementById("comfAddressErr").innerHTML = "Please enter the address.";
            } else {
                    document.getElementById("comfAddressErr").innerHTML = "";
            }
            // Zip Code validation
            if (zipCode == "") { // trim
                    document.getElementById("comfZipCodeErr").innerHTML = "Please enter the zip code.";
            } else if (/^\d+$/.test(zipCode) == false) {
                document.getElementById("comfZipCodeErr").innerHTML = "Zip code must only contain numbers. No other characters.";
                return false;
            } else if (zipCode.length != 5) {
                document.getElementById("comfZipCodeErr").innerHTML = "Invalid zip code.";
                return false;
            } else {
                document.getElementById("comfZipCodeErr").innerHTML = "";
            }     
            if (name == "" || state == "" || city == "" || address == "" || zipCode == "") {
                return false;
            }
        } // validateShipping

        function validateAll () {
            validatePayment();
            validateShipping();

        } // validateAll

       
    </script>
    </body>
</html>
