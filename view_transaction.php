<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['buyerID'])) {
    header("Location: login1.php");
    exit();
}

// Retrieve the buyerID from the session
$buyerID = $_SESSION['buyerID'];

// Connect to the database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve transaction details from the database
$query = "SELECT * FROM checkout WHERE buyerID = $buyerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$transactions = array();

while ($row = mysqli_fetch_assoc($result)) {

    $productID = $row['productID'];
    $productName = $row['productName'];
    $productPrice = $row['productPrice'];
    $buyerName = $row['buyerName'];
    $buyerEmail = $row['buyerEmail'];
    $buyerAddress = $row['buyerAddress'];
    $buyerPhoneNumber = $row['buyerPhoneNumber'];

    $transactions[] = array(
 
        'productID' => $productID,
        'productName' => $productName,
        'productPrice' => $productPrice,
        'buyerName' => $buyerName,
        'buyerEmail' => $buyerEmail,
        'buyerAddress' => $buyerAddress,
        'buyerPhoneNumber' => $buyerPhoneNumber
    );
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TRANSACTION</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        .topnav {
            overflow: hidden;
            background-color: #000;
        }

        .topnav a {
            float: right;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 15px;
            text-transform: uppercase;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
            background-color: #536bdd;
            color: white;
        }

        h2 {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="topnav">
    <img src="logo2.png" alt="Logo" width="150px">
    <a href="view_transaction.php"class="active"><i class="	fas fa-money-check-alt"></i></a>
    <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> </a>
    <a href="process_form.php"><i class="fas fa-paper-plane"></i> CONTACT US</a>
    <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a>
    <a href="reserve.php"><i class="far fa-calendar-alt"></i> RESERVE</a>
    <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
    <a href="home1.php"><i class="fas fa-home"></i> HOME</a>
</div>

<div>
<h2 style="text-align: center;">Transaction Details</h2>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Product Price</th>
            <th>Buyer Name</th>
            <th>Buyer Email</th>
            <th>Buyer Address</th>
            <th>Buyer Phone Number</th>
        </tr>
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?php echo $transaction['productID']; ?></td>
                <td><?php echo $transaction['productName']; ?></td>
                <td><?php echo $transaction['productPrice']; ?></td>
                <td><?php echo $transaction['buyerName']; ?></td>
                <td><?php echo $transaction['buyerEmail']; ?></td>
                <td><?php echo $transaction['buyerAddress']; ?></td>
                <td><?php echo $transaction['buyerPhoneNumber']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
