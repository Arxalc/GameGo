<?php
    //connect to db
    $uname = "root";
    $dbpass = "";
    $host = "localhost";
    $db = "webdev_db";

    $conn = mysqli_connect("$host", "$uname", "$dbpass", "$db") or die("DB Connection Err0r");
?>