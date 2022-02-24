
<?php
    session_start(); 
    include_once('../db/db.php'); // Check connection?

    //Signing up destroys session and cookies.
    session_destroy();
    // Delete cookies
    if (isset($_COOKIE['userIDcookie'])) { 
        setcookie("userIDcookie", "", time() - 3600);
    }
    if (isset($_COOKIE['usercookie'])) {
        setcookie("usercookie", "", time() - 3600); 
    }

    $past = time() - 3600;
    foreach ( $_COOKIE as $key => $value )
    {
        setcookie( $key, $value, $past, '/' );
    }

    // define variables and set to empty values
    $dataValid = true;
    $firstNameErr = $lastNameErr = $emailErr = $passwordErr = $confirm_passwordErr = "";
    $email = $password = $firstName = $lastName = $confirm_password = "";

// Formats data into nicer form.
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Checks if data is still in a valid format. 1 if so, 0 if not.
function isValid($data) {
    global $dataValid; // Printing out a false boolean variable will be an empty string
    if (stripslashes($data) != $data || htmlspecialchars($data) != $data) {
        $dataValid = false;
        return 0;
    }  
    return 1;
}

// Checks if string contains whitespace. 1 if true, 0 if false.
function containsWhiteSpace($data) {
    global $dataValid;
    if (preg_match('/\s/',$data)) {
        $dataValid = false;
        return 0;
    }
    return 1;
}


if(isset($_POST['signup'])) {
    // validation
    // Email validation
    $statement = $conn->prepare("SELECT * FROM customers WHERE email= ?");
    $statement->bind_param("s", $email);

    //getting data
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $confirm_password = $_POST['confirm_password'];
    $statement->execute();
    $result = $statement->get_result();      
    $numrows = mysqli_num_rows($result);

    if (empty($email)) {
        $emailErr = "Email is required.";
    } else if (isValid($email) == 0 || containsWhiteSpace($email) == 0) { 
        $emailErr = "Invalid characters. Try again.";
    } else if ($numrows != 0) {
        $emailErr = "Email already in use."; // Javascript validation() function alone doesn't check this. PHP will.
    } else {
        $email = test_input($_POST["email"]);
    }
    // First Name validation
    if (empty($firstName)) {
        $firstNameErr = "First name is required.";
    } else if (isValid($firstName) == 0 || containsWhiteSpace($firstName) == 0) {
        $firstNameErr = "Invalid characters or formatting. Try again.";
    } else {
        $firstName = test_input($_POST["firstName"]);
    }
    // Last Name validation
    if (empty($lastName)) {
        $lastNameErr = "Last name is required.";
    } else if (isValid($lastName) == 0 || containsWhiteSpace($lastName) == 0) {
        $lastNameErr = "Invalid characters. Try again.";
    } else {
        $lastName = test_input($_POST["lastName"]);
    }
    // Password validation
    if (empty($password)) {
        $passwordErr = "Password is required.";
    }
    // Confirm password validation
    if (empty($confirm_password)) {
        $confirm_passwordErr = "Password confirmation is required";
    } else if ($password != $confirm_password) {
        $confirm_passwordErr = "Passwords do not match.";
    } else {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
    }

            // If no errors, create account.
            if ($firstName != '' && $lastName != '' && $email != '' && $password != '' && $confirm_password != '') {
                if ($firstNameErr == '' && $lastNameErr == '' && $emailErr == '' && $passwordErr == '' && $confirm_passwordErr == '') { 
                    //password validation
                    if ($_POST["password"] === $_POST["confirm_password"]) {
                        $sql = $conn->prepare("INSERT INTO customers(email, password, firstName, lastName) VALUES(?, ?, ?, ?)");
                        $sql->bind_param("ssss", $email2, $password2, $firstName2, $lastName2);
                        $email2 = $email;
                        $password2 = $password;
                        $firstName2 = $firstName;
                        $lastName2 = $lastName;
                        $sql->execute();
                        $result = $sql->get_result(); 
                        //$qry = mysqli_query($conn, $sql) or die("Data insert error");
                        if ($result == false) {
                            echo '<script>alert("Something went wrong!")</script>';
                        } else {
                        // Creating Session variables
                            $sql3 = $conn->prepare("SELECT * WHERE (email, password, firstName, lastName) VALUES(?, ?, ?, ?)");
                            $sql3->bind_param("ssss", $email3, $password3, $firstName3, $lastName3);
                            $email3 = $email2;
                            $password3 = $password2;
                            $firstName3 = $firstName2;
                            $lastName3 = $lastName2;
                            $sql3->execute();
                            $result3 = $sql3->get_result(); 
                            while($row = mysqli_fetch_assoc($result3)) {
                                $_SESSION["email"] = $email3;
                                $_SESSION["password"] = $password3;
                                $_SESSION["firstName"] = $firstName3;
                                $_SESSION["lastName"] = $lastName3;
                                $_SESSION["customerID"] = $row['customerID'];
                            }
                        }
                    }
                    // Check cookie
                    $checkRemember = isset($_POST['rememberMe']);
                    if ($checkRemember == true || $checkRemember == 1) {
                        setcookie("usercookie", "", time() - 3600); // Deletes current cookie
                        setcookie("userIDcookie", "", time() - 3600);
                        while($row = mysqli_fetch_assoc($result)) {
			                setcookie("usercookie", "".$firstName . " " .$lastName, time() + (68400 * 7), "/"); /* Expires after 7 days */
		                    setcookie("userIDcookie", '' .$row['customerID'], time() + (68400 * 7), "/");
                        }
                    } 
                    header('Location: homepage.php');
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
        <title>Sign Up</title>
        <link rel="icon" type="image/png" href="../images/favicon.png"/>
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
            <form name="signup" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validation()">
                <h1>Become a GameGo member today</h1>
		        <h2 style="color:gray; font-size: 1.125rem;">Registration information</h2>

                    <div class = "inputTextContainer">
                    <br><br>
                    <input type="text" class="inputText" id="firstName" name="firstName" placeholder="First Name">
                    <span class="error" id="firstNameErr"></span><span class="error"><?php echo $firstNameErr;?></span>
                    <br><br><br>
                    <input type="text" class="inputText" id="lastName" name="lastName" placeholder="Last Name">
                    <span class="error" id="lastNameErr"></span><span class="error"><?php echo $lastNameErr;?></span>
                    <br><br><br>
                    <input type="text" class="inputText" id="email" name="email" placeholder="Email"></input>
                    <span class="error" id="emailErr"></span><span class="error"><?php echo $emailErr;?></span>
                    <br><br><br>
                    <input type="password" class="inputText" id="password" name="password" placeholder="Password"></input></input>
                    <span class="error" id="passwordErr"></span><span class="error"><?php echo $passwordErr;?></span>
                    <br><br><br>
                    <input type="password" class="inputText" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                    </input><span class="error" id="confirm_passwordErr"></span><span class="error"><?php echo $confirm_passwordErr;?></span>
                    <br><br>
                    </div>
                    <br>
                    <input type="checkbox" id="rememberMe" name="rememberMe">   
                    <label for="rememberMe">Keep me signed in</label><br>  
                    <input type="submit" name="signup" value="Submit" class="button"></input>
                    <p>or</p>
            <a href="login.php"><button type="button" class="button">Sign in</button></a>
            </form>
            
    </div>
    <script>
        function validation() {
            var firstNameErr = "";
            var lastNameErr = "";
            var emailErr = "";
            var passwordErr = "";
            var confirm_passwordErr = "";

            var firstName = document.getElementById("firstName").value;
            var lastName = document.getElementById("lastName").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;

            if (firstName == "") {
                document.getElementById("firstNameErr").innerHTML = "Please enter your first name.";
            } else {
                document.getElementById("firstNameErr").innerHTML = "";
            }
            if (lastName == "") {
                document.getElementById("lastNameErr").innerHTML = "Please enter your last name.";
            } else {
                document.getElementById("lastNameErr").innerHTML = "";
            }
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
            if (confirm_password == "") {
                document.getElementById("confirm_passwordErr").innerHTML = "Please re-enter your password.";
            } else {
                document.getElementById("confirm_passwordErr").innerHTML = "";
            }
            // A required field was left blank.
            if (firstName == "" || lastName == "" || email == "" || password == "" || confirm_password == "") {
                return false; // Needed for this
            }
            // Must be an email... (contains @)
            if (email.includes("@") || email.includes(" ")) {
                document.getElementById("emailErr").innerHTML = "";
            } else {
                document.getElementById("emailErr").innerHTML = "Must be a proper email.";
                return false;
            }
            // Password less than 10 characters
            if (password.length < 10) {
                document.getElementById("passwordErr").innerHTML = "Password must be 10 characters long.";
                return false;
            } else {
                document.getElementById("passwordErr").innerHTML = "";
            }
            // Password doesn't contain a special character
            var specialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
            if (specialChar.test(password)) {
                document.getElementById("passwordErr").innerHTML = "";
            } else {
                document.getElementById("passwordErr").innerHTML = "Password must contain at least 1 special character.";
                return false;
            }
            // Password doesn't contain a number
            if (/\d/.test(password)) {
                document.getElementById("passwordErr").innerHTML = "";
            } else {
                document.getElementById("passwordErr").innerHTML = "Password must contain at least 1 number.";
                return false;
            }
            // Passwords don't match
            if (password != confirm_password) {
                document.getElementById("confirm_passwordErr").innerHTML = "Passwords do not match.";
                return false;
            } else {
                document.getElementById("confirm_passwordErr").innerHTML = "";
            }


        }
    </script>
</body>
</html>
