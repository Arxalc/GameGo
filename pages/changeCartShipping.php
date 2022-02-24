<?php 

    // Called to change shipping option in cart table
    include_once('../db/db.php');
                                    
    if(isset($_POST[$_POST["productID"]."submitShipping"])) {
        
        $shipping = $_POST[$_POST["productID"]."shipping"];
        $customerID = $_POST["customerID"];
        $id = $_POST["productID"];
        if ($shipping == 'S') {
            $updateStmt = "UPDATE cart SET shipping=? WHERE productID=? AND customerID=?";
            $updateQuery = $conn->prepare($updateStmt);
            $updateQuery->bind_param('sii', $shipping, $id, $customerID);
            $updateQuery->execute();

        } elseif ($shipping == 'E') {
            $updateStmt = "UPDATE cart SET shipping=? WHERE productID=? AND customerID=?";
            $updateQuery = $conn->prepare($updateStmt);
            $updateQuery->bind_param('sii', $shipping, $id, $customerID);
            $updateQuery->execute();
        } elseif ($shipping == 'P') {
            $updateStmt = "UPDATE cart SET shipping=? WHERE productID=? AND customerID=?";
            $updateQuery = $conn->prepare($updateStmt);
            $updateQuery->bind_param('sii', $shipping, $id, $customerID);
            $updateQuery->execute();
        }
    }
    include("ShoppingCart.php");
?>