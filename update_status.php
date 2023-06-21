<?php
// Establish database connection
$con = mysqli_connect("localhost", "root", "", "online_store_db");

// Check if the connection was successful
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Get the order ID and status from the AJAX request
$orderID = $_POST['order-id'];
$status = $_POST['status'];

// Update the status in the database
$query = "UPDATE checkout SET status = '$status' WHERE orderID = '$orderID'";
if (mysqli_query($con, $query)) {
    echo "Status updated successfully";
} else {
    echo "Error updating status: " . mysqli_error($con);
}

mysqli_close($con);
?>
