<?php

$server = "localhost";
$username = "root";
$password = "";
$dbname = "online_store_db";

// Create a new mysqli connection
$conn = new mysqli($server, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
    
    $id=$_POST['record'];
    $query="DELETE FROM sellers where sellerID='$id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Seller Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>