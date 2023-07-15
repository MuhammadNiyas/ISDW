<?php
session_start();

// Check if buyer is logged in
if (!isset($_SESSION['buyerID'])) {
    header("Location: login1.php");
    exit();
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



// Check if the "reserve_product" button is clicked
if (isset($_POST['reserve_product'])) {
    // Retrieve the product ID, reserve date, and reserve time from the form submission
    $productID = mysqli_real_escape_string($conn, $_POST['productID']);
    $reserveDate = mysqli_real_escape_string($conn, $_POST['reserveDate']);
    $reserveTime = mysqli_real_escape_string($conn, $_POST['reserveTime']);

    // Example: Insert reservation data into the "reservations" table using prepared statements
    $insertQuery = "INSERT INTO reservations (productID, reserveDate, reserveTime) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "sss", $productID, $reserveDate, $reserveTime);
    $insertResult = mysqli_stmt_execute($stmt);

    if ($insertResult) {
        echo "Reservation successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Check if the "cancel_reservation" button is clicked
if (isset($_POST['cancel_reservation'])) {
    // Retrieve the reservation ID from the form submission
    $reserveID = mysqli_real_escape_string($conn, $_POST['reserveID']);

    // Example: Delete reservation from the "reservations" table using prepared statements
    $deleteQuery = "DELETE FROM reservations WHERE reserveID = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $reserveID);
    $deleteResult = mysqli_stmt_execute($stmt);

    if ($deleteResult) {
        echo "";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Check if the "change_reservation" button is clicked
if (isset($_POST['change_reservation'])) {
    // Retrieve the reservation ID, new reserve date, and new reserve time from the form submission
    $reserveID = mysqli_real_escape_string($conn, $_POST['reserveID']);
    $newReserveDate = mysqli_real_escape_string($conn, $_POST['newReserveDate']);
    $newReserveTime = mysqli_real_escape_string($conn, $_POST['newReserveTime']);

    // Example: Update the reservation with new reserve date and time in the "reservations" table using prepared statements
    $updateQuery = "UPDATE reservations SET reserveDate = ?, reserveTime = ? WHERE reserveID = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssi", $newReserveDate, $newReserveTime, $reserveID);
    $updateResult = mysqli_stmt_execute($stmt);

    if ($updateResult) {
        echo "";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Redirect to the checkout page when the "Proceed to Checkout" button is clicked
if (isset($_POST['proceed_to_checkout'])) {
    header("Location: checkout1.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>RESERVE</title>
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
        }

        /icon/
        .topnav a i {
            margin-right: 5px;
            font-size: 20px;
            /* Adjust the font size as desired */
            display: inline-flex;
            align-items: center;
            height: 100%;
        }

        .container {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }

        .left-section {
            flex: 5;
            margin-right: 20px;
        }

        .right-section {
            flex: 5;
        }

        .product-table {
            border-collapse: collapse;
            width: 100%;
        }

        .product-table th,
        .product-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .product-table th {
            background-color: #f2f2f2;
        }

        .product-table img {
            width: 200px;
            height: auto;
        }

        .product-table button {
            background-color: #000;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
        }

        .product-table button:hover {
            background-color: #536bdd;
        }

        .order-summary-table {
            border-collapse: collapse;
            width: 100%;
        }

        .order-summary-table th,
        .order-summary-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-summary-table th {
            background-color: #f2f2f2;
        }

        .checkout-button {
            margin-top: 20px;
            text-align: center;
        }

        .checkout-button button {
            background-color: #000;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }

        .checkout-button button:hover {
            background-color: #536bdd;
        }

        /* Footer Styles */
        footer {
            background-color: #000;
            padding: 20px;
            text-align: center;
        }

        .footer-content {
            max-width: 960px;
            margin: 0 auto;
            color: #fff;
        }

        .social-media ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        .social-media ul li {
            display: inline-block;
            margin-right: 10px;
        }

        .social-media ul li a {
            color: #fff;
            font-size: 20px;
        }

        .footer-icons {
            text-align: center;
            margin-top: 20px;
        }

        .footer-icons a {
            color: #fff;
            font-size: 20px;
            margin: 0 10px;
            transition: color 0.3s ease;
            text-decoration: none;
        }

        .cancel-button,
        .change-button {
            background-color: #000;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
        }

        .cancel-button:hover,
        .change-button:hover {
            background-color: #536bdd;
        }

    </style>
</head>
<body>
<div class="topnav">
    <img src="logo2.png" alt="Logo" width="150px">
    <a href="view_transaction.php"><i class="	fas fa-money-check-alt"></i></a>
    <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> </a>
    <a href="process_form.php"><i class="fas fa-paper-plane"></i> CONTACT US</a>
    <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a>
    <a href="reserve.php" class="active"><i class="far fa-calendar-alt"></i> RESERVE</a>
    <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
    <a href="home1.php"><i class="fas fa-home"></i> HOME</a>


    </div>
</div>

<div class="container">
    <div class="left-section">
        <h2 style="text-align: center">Reserve Items</h2>
        <?php
        // Fetch data from the "reservations" table
        $query = "SELECT * FROM reservations";
        $result = mysqli_query($conn, $query);

        // Check if there are any rows returned
        if ($result && mysqli_num_rows($result) > 0) {
            ?>

            <table class="product-table">
                <tr>
                    <th>Product Name</th>
                    <th>Product ID</th>
                    <th>Product Image</th>
                    <th>Product Price</th>
                    <th>Total Cost</th>
                    <th>Reserve Date</th>
                    <th>Reserve Time</th>
                    <th>Action</th>
                </tr>
                <?php
                $orderSummary = []; // Array to store order summary data
                $subtotal = 0; // Variable to store the subtotal cost

                // Display the fetched data
                while ($reservationData = mysqli_fetch_assoc($result)) {
                    $reserveID = $reservationData['reserveID'];
                    $productID = $reservationData['productID'];

                    // Fetch product information from the "products" table
                    $productQuery = "SELECT * FROM products WHERE productID = '$productID'";
                    $productResult = mysqli_query($conn, $productQuery);

                    // Check if product information is available
                    if ($productResult && mysqli_num_rows($productResult) > 0) {
                        $productData = mysqli_fetch_assoc($productResult);
                        $totalCost = $productData['productPrice'];
                        $subtotal += $totalCost; // Add the total cost to the subtotal

                        $orderSummary[] = [
                            'productID' => $productID,
                            'productName' => $productData['productName'],
                            'productPrice' => $productData['productPrice']
                        ];
                        ?>
                        <tr>
                            <td><?php echo $productData['productName']; ?></td>
                            <td><?php echo $productData['productID']; ?></td>
                            <td><img src="<?php echo $productData['productImage']; ?>" alt="Product Image" width="100px"></td>
                            <td>RM<?php echo $productData['productPrice']; ?></td>
                            <td>RM<?php echo $totalCost; ?></td>
                            <td><?php echo $reservationData['reserveDate']; ?></td>
                            <td><?php echo $reservationData['reserveTime']; ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="reserveID" value="<?php echo $reserveID; ?>">
                                    <input type="hidden" name="newReserveDate" value="">
                                    <input type="hidden" name="newReserveTime" value="">
                                    <button type="submit" name="cancel_reservation" class="cancel-button">Cancel</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
    </div>

    <div class="right-section">
        <h2 style="text-align:center">Order Summary </h2>
        <table class="order-summary-table">
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Price</th>
            </tr>
            <?php
            // Display the order summary data
            foreach ($orderSummary as $item) {
                ?>
                <tr>
                    <td><?php echo $item['productID']; ?></td>
                    <td><?php echo $item['productName']; ?></td>
                    <td>RM<?php echo $item['productPrice']; ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="2"><strong>Subtotal</strong></td>
                <td>RM<?php echo $subtotal; ?></td>
            </tr>
        </table>

        <?php
        } else {
            echo "No reserved products found.";
        }
        ?>
        <div class="checkout-button">
            <form action="" method="post">
                <button type="submit" name="proceed_to_checkout">Proceed to Checkout</button>
            </form>
        </div>
    </div>
</div>

<footer>
    <div class="footer-content">
        <p>&copy; 2023 BUY AND SELL DISTED COLLEGE. All rights reserved.</p>

        <div class="row">
            <div class="col-md-12 text-center">
                <h4>Contact Information</h4>
                <p>Email: info@example.com</p>
                <p>Phone: +1 123-456-7890</p>
                <p>Address: 123 Street, City, Country</p>
                <div class="social-media">
                    <h4>Follow Us</h4>
                    <ul>
                        <li><a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-icons">
                <a href="home1.php"><i class=""></i>Home</a>
                <a href="cart1.php"><i class=""></i>Cart</a>
                <a href="userprofile1.php"><i class=""></i>User Profile</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
