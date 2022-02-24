<?php 

   // Called to change quantity of each item in shopping cart
   include_once('../db/db.php');
    // ISSUE: Adding a duplicate item to cart adds another row instead of increasing quantity
    if(isset($_POST[$_POST["productID"]."submit"])) {
        // echo "changed qty to". $_POST[$_POST["productID"]."qty"];
        $qty = $_POST[$_POST["productID"]."qty"];
        $id = $_POST["productID"];
        $customerID = $_POST["customerID"];

        // Changes quantity if selected value is greater than 0
        if ($qty > 0) {
            $updateStmt = "UPDATE cart SET quantity=? WHERE productID=? AND customerID=?";
            $updateQuery = $conn->prepare($updateStmt);
            $updateQuery->bind_param('iii', $qty, $id, $customerID);
            $updateQuery->execute();

        // Deletes item from shopping cart if selected value is 0
        } elseif ($qty == 0) {
            $updateStmt = "DELETE FROM cart WHERE productID=? AND customerID=?";
            $updateQuery = $conn->prepare($updateStmt);
            $updateQuery->bind_param('ii', $id, $customerID);
            $updateQuery->execute();
            
        }
    }
    include("ShoppingCart.php");
?>