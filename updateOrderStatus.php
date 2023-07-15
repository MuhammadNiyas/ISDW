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
   
    $checkoutID=$_POST['record'];
    //echo $order_id;
    $sql = "SELECT order_status from checkout where checkoutID='$checkoutID'"; 
    $result=$conn-> query($sql);
  //  echo $result;

    $row=$result-> fetch_assoc();
    
   // echo $row["pay_status"];
    
    if($row["order_status"]==0){
         $update = mysqli_query($conn,"UPDATE checkout SET order_status=1 where checkoutID='$checkoutID'");
    }
    else if($row["order_status"]==1){
         $update = mysqli_query($conn,"UPDATE checkout SET order_status=0 where checkoutID='$checkoutID'");
    }
    
        
 
    // if($update){
    //     echo"success";
    // }
    // else{
    //     echo"error";
    // }
    
?>