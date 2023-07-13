<?php
session_start();

// Check if buyer is logged in
if (!isset($_SESSION['buyerID'])) {
    header("Location: login1.php");
    exit();
}

// Retrieve buyer details from session or database
$buyerID = $_SESSION['buyerID'];

if (isset($_SESSION['buyerDetails'])) {
    $buyerDetails = $_SESSION['buyerDetails'];
}

// Connect to the database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$reservedItems = array();
$query = "SELECT * FROM reservations WHERE buyerID = {$_SESSION['buyerID']}";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $productID = $row['productID'];

    // Check if the product is already in the checkout table
    $checkQuery = "SELECT COUNT(*) AS count FROM checkout WHERE productID = '$productID'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (!$checkResult) {
        die("Error checking product existence in checkout table: " . mysqli_error($conn));
    }

    $productExists = mysqli_fetch_assoc($checkResult)['count'];

    if ($productExists == 0) {
        $reservedItems[] = array(
            'reserveID' => $row['reserveID'],
            'productID' => $row['productID'],
            'productName' => $row['productName'],
            'productPrice' => $row['productPrice']
        );
    }
}

$cartItems = array();
$totalCost = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $productIDs = implode(",", array_keys($_SESSION['cart']));

    $query = "SELECT * FROM products WHERE productID IN ($productIDs)";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $productID = $row['productID'];
        $productName = $row['productName'];
        $productPrice = $row['productPrice'];

        $cartItems[] = array(
            'productID' => $productID,
            'productName' => $productName,
            'productPrice' => $productPrice,
        );
    }
}

if (isset($_POST['checkout'])) {

    // Insert the reserved items into the checkout table
    foreach ($reservedItems as $item) {
        $reserveID = $item['reserveID'];
        $productID = $item['productID'];
        $productName = $item['productName'];
        $productPrice = $item['productPrice'];

        $insertQuery = "INSERT INTO checkout (reserveID, productID, productName, productPrice, buyerID, buyerUsername, buyerName, buyerEmail, buyerAddress, buyerPhoneNumber) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $reserveID, $productID, $productName, $productPrice, $buyerID, $buyerDetails['buyerUsername'], $buyerDetails['buyerName'], $buyerDetails['buyerEmail'], $buyerDetails['buyerAddress'], $buyerDetails['buyerPhoneNumber']);
        $insertResult = mysqli_stmt_execute($stmt);

        if (!$insertResult) {
            die("Error inserting reserved items into checkout table: " . mysqli_error($conn));
        }
    }

    // Insert the cart items into the checkout table
    foreach ($cartItems as $item) {
        $productID = $item['productID'];
        $productName = $item['productName'];
        $productPrice = $item['productPrice'];

        $insertQuery = "INSERT INTO checkout (productID, productName, productPrice, buyerID, buyerUsername, buyerName, buyerEmail, buyerAddress, buyerPhoneNumber) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssssssss", $productID, $productName, $productPrice, $buyerID, $buyerDetails['buyerUsername'], $buyerDetails['buyerName'], $buyerDetails['buyerEmail'], $buyerDetails['buyerAddress'], $buyerDetails['buyerPhoneNumber']);
        $insertResult = mysqli_stmt_execute($stmt);

        if (!$insertResult) {
            die("Error inserting cart items into checkout table: " . mysqli_error($conn));
        }
    }
}

// Retrieve buyer details from the database
$query = "SELECT * FROM Buyers WHERE buyerID = $buyerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$buyerDetails = mysqli_fetch_assoc($result);

// Store buyer details in session for future use
$_SESSION['buyerDetails'] = $buyerDetails;

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>CHECKOUT</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        * {
            box-sizing: border-box;
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

        .icon {
            margin-right: 5px;
            font-size: 20px;
            display: inline-flex;
            align-items: center;
            height: 100%;
        }

        .checkout-heading {
            text-align: center;
        }

        .checkout-heading i {
            margin-right: 10px;
        }

        .order-summary {
            margin-top: 20px;
        }

        .order-summary h2 {
            margin-bottom: 10px;
            text-align: left;
            font-size: 18px;
        }

        .summary-box {
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        .col-25 {
            -ms-flex: 25%;
            /* IE10 */
            flex: 25%;
        }

        .col-25,
        .col-50,
        .col-75 {
            padding: 0 16px;
        }

        .container {
            background-color: #f2f2f2;
            padding: 5px 20px 15px 20px;
            border: 1px solid lightgrey;
            border-radius: 3px;
            flex: 10%;
        }

        .icon-container {
            margin-bottom: 20px;
            padding: 7px 0;
            font-size: 24px;
        }

        .payment-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .payment-container .col-50 {
            flex: 50%;
            padding: 10px;
            border-radius: 5px;
            background-color: #f2f2f2;
            margin-bottom: 20px;
        }

        .payment-container h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        .payment-container label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .payment-container input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .payment-container .row::after {
            content: "";
            display: table;
            clear: both;
        }

        .payment-container .col-50 .btn {
            background-color: #536bdd;
            color: white;
            padding: 14px 20px;
            margin-top: 20px;
            border: none;
            width: 100%;
            border-radius: 3px;
            cursor: pointer;
            font-size: 17px;
            transition: background-color 0.3s ease;
        }

        .payment-container .col-50 .btn:hover {
            background-color: #4054b2;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <!-- Header and navigation code here -->
    <div class="topnav">
        <img src="logo2.png" alt="Logo" width="150px">
        <a href="view_transaction.php"><i class="	fas fa-money-check-alt"></i></a>
    <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> </a>
        <a href="process_form.php"><i class="fas fa-paper-plane"></i> CONTACT US</a>
        <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a>
        <a href="reserve.php" ><i class="far fa-calendar-alt"></i> RESERVE</a>
        <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
        <a href="home1.php" ><i class="fas fa-home"></i> HOME</a>
    </div>

    <div class="col-25">
        <div class="container">
            <!-- Order summary and checkout button -->
            <?php if (!empty($reservedItems) && empty($cartItems)): ?>
                <div class="order-summary">
                <h3 style="text-align:center">Reserved Items</h3>
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
                        <?php foreach ($reservedItems as $item): ?>
                            <tr>
                                <td><?php echo $item['productID']; ?></td>
                                <td><?php echo $item['productName']; ?></td>
                                <td><?php echo $item['productPrice']; ?></td>
                                <td><?php echo $buyerDetails['buyerName']; ?></td>
                                <td><?php echo $buyerDetails['buyerEmail']; ?></td>
                                <td><?php echo $buyerDetails['buyerAddress']; ?></td>
                                <td><?php echo $buyerDetails['buyerPhoneNumber']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php $reservedTotalCost = 0; ?>
                    <?php foreach ($reservedItems as $item): ?>
                        <?php $reservedTotalCost += $item['productPrice']; ?>
                    <?php endforeach; ?>
                    <h3>Total Cost: $<?php echo $reservedTotalCost; ?></h3>
                </div>
            <?php elseif (empty($reservedItems) && !empty($cartItems)): ?>
                <div class="order-summary">
                <h3 style="text-align:center">Cart Items</h3>
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
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?php echo $item['productID']; ?></td>
                                <td><?php echo $item['productName']; ?></td>
                                <td><?php echo $item['productPrice']; ?></td>
                                <td><?php echo $buyerDetails['buyerName']; ?></td>
                                <td><?php echo $buyerDetails['buyerEmail']; ?></td>
                                <td><?php echo $buyerDetails['buyerAddress']; ?></td>
                                <td><?php echo $buyerDetails['buyerPhoneNumber']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php
                    $totalCost = 0;

                    // Calculate the total cost of reserved items
                    foreach ($reservedItems as $item) {
                        $totalCost += $item['productPrice'];
                    }

                    // Calculate the total cost of cart items
                    foreach ($cartItems as $item) {
                        $totalCost += $item['productPrice'];
                    }
                    ?>

                    <h3>Total Cost: $<?php echo $totalCost; ?></h3>
                </div>
            <?php elseif (!empty($reservedItems) && !empty($cartItems)): ?>
                <div class="order-summary">
                    <p>Error: Cannot proceed with checkout. Please remove either the reserved items or the items in the cart.</p>
                </div>
            <?php endif; ?>
            <div class="payment-container">
                <div class="col-50">
                    <h3>Payment</h3>
                    <form method="post" action="" onsubmit="return validatePaymentForm()">
                        <div class="row">
                            <div class="col-50">
                                <label for="cname">Name on Card</label>
                                <input type="text" id="cname" name="cardname" placeholder="Enter name on card">
                            </div>
                            <div class="col-50">
                                <label for="ccnum">Credit card number</label>
                                <input type="text" id="ccnum" name="cardnumber" placeholder="Enter your credit card number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-50">
                                <label for="expmonth">Exp Month</label>
                                <input type="text" id="expmonth" name="expmonth" placeholder="Month">
                            </div>
                            <div class="col-50">
                                <label for="expyear">Exp Year</label>
                                <input type="text" id="expyear" name="expyear" placeholder="Year">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-50">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="CVV">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-50">
                                <button type="submit" name="checkout" class="btn">Proceed to Checkout</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validatePaymentForm() {
            var cardname = document.getElementById("cname").value;
            var cardnumber = document.getElementById("ccnum").value;
            var expmonth = document.getElementById("expmonth").value;
            var expyear = document.getElementById("expyear").value;
            var cvv = document.getElementById("cvv").value;

            if (cardname.trim() === "") {
                alert("Please enter the name on the card.");
                return false;
            }

            if (cardnumber.trim() === "") {
                alert("Please enter the credit card number.");
                return false;
            }

            if (expmonth.trim() === "") {
                alert("Please enter the expiration month.");
                return false;
            }

            if (expyear.trim() === "") {
                alert("Please enter the expiration year.");
                return false;
            }

            if (cvv.trim() === "") {
                alert("Please enter the CVV number.");
                return false;
            }

            // Additional validation logic can be added here as needed

            return true;
        }
    </script>
</body>
</html>
