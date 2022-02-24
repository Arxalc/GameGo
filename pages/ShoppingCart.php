<?php

    include_once('../db/db.php');
    session_start();
    if(isset($_SESSION['email'])) {
        // echo "Hello ". $_SESSION['firstName'];
    } else {
        // echo "No user logged in.";
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
        <title>GameGo Shopping Cart</title>
        <link rel="icon" type="image/png" href="../images/favicon.png"/>

        <!-- This is used to load header from header.html file -->
        <script> 
            $(function(){
            $("#header").load("header.php"); 
            });
        </script> 
    </head>
    <body class="cart">

    <!-- nav bar inserted with JQuery -->
	<div id="header"></div>

    <div class="cart-body">
            <div class="cart-container">
                <?php
                    if( isset($_SESSION['email'])) {
                        $email = $_SESSION['email'];
                        //echo $email;
                        $sql = "SELECT * FROM customers WHERE email=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $stmtResult = $stmt->get_result();
                        $user = $stmtResult->fetch_assoc();
                        //echo $user["customerID"];
                        $customerID = $user["customerID"];
                    } else {
                        echo "Please log in to access cart.";
                        $customerID = -1;
                    }

                    // retrieves cart associated with customer
                    $query = "SELECT * FROM products INNER JOIN cart on products.productID = cart.productID WHERE customerID=?";
                    $result = $conn->prepare($query);
                    if(!$result) {
                        echo "Sorry, something went wrong.";
                    }
                    $result->bind_param("s", $customerID);
                    $result->execute();
                    $queryResult = $result->get_result();

                    // data for cart summary
                    $subtotal = 0;
                    $shippingTotal = 0;
                    $totalItems = 0;
                    $isStandardShipping = false;
                    $isExpeditedShipping = false;

                    if (mysqli_num_rows($queryResult) > 0) {
                        while($row = $queryResult->fetch_assoc()) {
                            // fetches data from cart database
                            $subtotal += $row["subTotal"] * $row["quantity"];
                            $totalItems += $row["quantity"];

                            //checks shipping options
                            if ($row["shipping"] == 'S') {
                                $isStandardShipping = true;
                            } elseif ($row["shipping"] == 'E') {
                                $isExpeditedShipping = true;
                            }
                ?>
                            <!-- Item card for information for each item in cart-->
                            <div class="cart-card">
                                <div class="cart-item-title">
                                    <h2 class="cart-card-name"><?php echo $row["productName"] ?></h2><br>
                                    <img class="cart-card-image" src="<?php echo $row["productImage"]; ?>">
                                </div>
                                <br>
                                <!-- Shipping options -->
                                <div class="cart-item-shipping">
                                    <form name="shipping" method="post" action="changeCartShipping.php">
                                        <h3>Shipping Options</h3><br>
                                        <input type="radio" id="standard" name=<?php echo $row["productID"]."shipping"?> value="S" <?php if($row["shipping"] == 'S'){ echo "checked"; }?>>
                                        <label for="standard">Standard ($3.99, Arrives in 7-9 days)</label><br><br>
                                        <input type="radio" id="expedited" name=<?php echo $row["productID"]."shipping"?> value="E" <?php if($row["shipping"] == 'E'){ echo "checked"; }?>>
                                        <label for="expedited">Expedited ($7.99, Arrives in 2-3 days)</label><br><br>
                                        <input type="radio" id="pickup" name=<?php echo $row["productID"]."shipping"?> value="P" <?php if($row["shipping"] == 'P'){ echo "checked"; }?>>
                                        <label for="Pick_Up">Pick Up (FREE, Ready in 2 hours)</label><br>
                                        <input type="hidden" name="productID" id="productID" value=<?php echo $row['productID']; ?>>
                                        <input type="hidden" name="customerID" id="customerID" value=<?php echo $row['customerID']; ?>>
                                        <input type="submit" class="cart-submit" name=<?php echo $row["productID"]."submitShipping"?> value="Select Shipping">
                                    </form>
                                </div>

                                <!-- Item cost and quantity -->
                                <div class="cart-cost-card">
                                    <h3 class="cart-card-cost">$<?php echo $row["productPrice"]?></h3><br><br>
                                    <form name="quantity" method="post" action="changeCartQty.php">
                                        <select name=<?php echo $row["productID"]."qty"?> id="quantity">
                                            <option value="0">Qty 0 (Remove)</option>
                                            <option value="1" <?php if($row["quantity"] == 1){ echo "selected"; }?> >Qty 1</option>
                                            <option value="2" <?php if($row["quantity"] == 2){ echo "selected"; }?> >Qty 2</option>
                                            <option value="3" <?php if($row["quantity"] == 3){ echo "selected"; }?> >Qty 3</option>
                                            <option value="4" <?php if($row["quantity"] == 4){ echo "selected"; }?> >Qty 4</option>
                                            <option value="5" <?php if($row["quantity"] == 5){ echo "selected"; }?> >Qty 5</option>
                                            <option value="6" <?php if($row["quantity"] == 6){ echo "selected"; }?> >Qty 6</option>
                                            <option value="7" <?php if($row["quantity"] == 7){ echo "selected"; }?> >Qty 7</option>
                                            <option value="8" <?php if($row["quantity"] == 8){ echo "selected"; }?> >Qty 8</option>
                                            <option value="9" <?php if($row["quantity"] == 9){ echo "selected"; }?> >Qty 9</option>
                                            <option value="10" <?php if($row["quantity"] == 10){ echo "selected"; }?> >Qty 10</option>
                                        </select>
                                        <input type="hidden" name="productID" id="productID" value=<?php echo $row['productID']; ?>>
                                        <input type="hidden" name="customerID" id="customerID" value=<?php echo $row['customerID']; ?>>
                                        <input type="submit" class="cart-submit" name=<?php echo $row["productID"]."submit"?> value="Select Quantity">
                                    </form>
                                </div>
                            </div>
                <?php
                        }
                        // Calculates total cost for shipping
                        if ($isStandardShipping) {$shippingTotal += 3.99;}
                        if ($isExpeditedShipping) {$shippingTotal += 7.99;}
                    }
                ?>
            </div>
            <!-- Cart Summary containing total number of items, shipping total, and subtotal -->
                <div class="cart-summary">
                    <h2>Order Summary</h2><br>
                    <h3>Number of items: <?php echo $totalItems ?></h3><br>
                    <h3>Shipping: $<?php echo $shippingTotal ?></h3><br>
                    <h3>Subtotal: $<?php echo ($subtotal + $shippingTotal)?></h3><br>
                    <form method="POST" action="../pages/Store_CheckOut.php">
                    <input type="hidden" name="subtotalHide" value="<?php echo ($subtotal + $shippingTotal)?>">
                    <input type="submit" value="Proceed to Checkout" class="button">
                    </a>
                </div>
            </div>

    </body>
</html>
