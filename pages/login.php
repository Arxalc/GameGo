<?php
session_start();
include_once('../db/db.php');

//Auto login if cookie detected
if (isset($_COOKIE['userIDcookie'])) {
    header('Location: homepage.php');
}

//Assumes variables are valid from when going through signup validation.
// define variables and set to empty values
$emailErr = $passwordErr = "";
$email = $password = $rememberMe = "";
if(isset($_POST['login'])) { 
        //validation
        if(($_POST['email'] != "") && ($_POST['password'] != "")) { // Both fields aren't empty
            // Prepared statement
            // Check database connection - Die?
            $query = $conn->prepare("SELECT * FROM customers WHERE email = ? AND password = ?");
            $query->bind_param("ss", $email, $password);
            $email = $_POST['email'];
            $password = $_POST['password'];
            $query->execute();
            $result = $query->get_result();      
            $numrows = mysqli_num_rows($result);

            if ($numrows == 1) { // Email and password found in database
                $checkRemember = isset($_POST['rememberMe']);
		        if ($checkRemember == true || $checkRemember == 1) {
                    // Delete current cookies
                    setcookie("usercookie", "", time() - 3600); 
                    setcookie("userIDcookie", "", time() - 3600);
                     while($row = mysqli_fetch_assoc($result)) {
                         setcookie("usercookie", '' .$row['firstName'] .' ' .$row['lastName'], time() + (68400 * 7), "/"); /* Expires after 7 days */
                         setcookie("userIDcookie", '' .$row['customerID'], time() + (68400 * 7), "/");
                     }
		        } // check cookie		
	            while($row = mysqli_fetch_assoc($result)) {		            
		            $_SESSION["email"] = $row['email'];
                    $_SESSION["password"] = $row['password'];
		            $_SESSION["firstName"] = $row['firstName'];
		            $_SESSION["lastName"] = $row['lastName'];
                    $_SESSION["customerID"] = $row['customerID'];
  	            }
		        header('Location: homepage.php');
            } else {
                $emailErr = "Username or password is incorrect.";
            }
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login and Signup Page</title>
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
    
<style>
</style>
</head>

<body>

 <!-- nav bar inserted with JQuery -->
	<div id="header"></div>

    <div class="form-wrapper">
 
        <form class="input-wrapper" name="login" class="login" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validation()">
            <h1>Welcome to GameGo</h1>
            <h2 style="color:gray; font-size: 1.125rem;">Sign in to your GameGo account</h2>
            <div class = "inputTextContainer">
            <br><br>
            <input type="text" class="inputText" id="email" name="email" placeholder="Email"></input>
            <span class="error" id="emailErr"><?php echo $emailErr ?></span>
            <br><br>          
            <br>
            <input type="password" class="inputText" id="password" name="password" placeholder="Password"></input>
            <span class="error" id="passwordErr"></span>
            <br><br>
            </div>
            <br>
            <input type="checkbox" id="rememberMe" name="rememberMe">   
            <label for="rememberMe">Remember me</label><br>                   
            <input type="submit" name="login" value="Login" class="button"> </input>     
            <p>or</p>
            <a href="signup.php"><button type="button" class="button">Create Account</button></a>
        </form>
        

    </div>
    <script>
        function validation() {
            var emailErr = "";
            var passwordErr = "";

            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;

            if (email == "") {
                    document.getElementById("emailErr").innerHTML = "Please enter your email.";
            } else {
                    document.getElementById("emailErr").innerHTML = "";
            }
            if (password == "") {
                    document.getElementById("passwordErr").innerHTML = "Please enter your password.";
            } else {
                    document.getElementById("passwordErr").innerHTML = "";
            }
            if (email == "" || password == "") {
                return false;
            }
            // Query validation handled by php.
        }
    </script>
</body>
</html>
