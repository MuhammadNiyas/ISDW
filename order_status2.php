<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <style>
        /* CSS code for orderpaymentStatus2.php */

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
        }

        mySlides {
            display: none;
        }

        img {
            vertical-align: middle;
        }

        body {
            background-image: url("images17.png");
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        html {
            box-sizing: border-box;
        }

        h1 {
            margin-bottom: 20px;
        }
/* Navigation bar styling */
nav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #000000;
  text-align: right;
}

nav li {
  float: left;
}

nav li a {
  display: block;
  color: #ffffff;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

nav li a:hover {
  background-color: #6b5a5a;
}
        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
            color: #333;
        }

        table td img {
            max-width: 150px;
            max-height: 150px;
        }

        /* Form input styling */
        form {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form div {
            margin-top: 10px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form label {
            width: 150px;
            margin-right: 5px;
        }

        form select,
        form button {
            margin-right: 5px;
        }

        form select {
            width: 200px;
        }

        form button {
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #111;
        }

        /* Error and success message styling */
        .error,
        .success {
            margin-top: 10px;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .error {
            background-color: #ffcccc;
            color: #ff0000;
        }

        .success {
            background-color: #ccffcc;
            color: #00cc00;
        }

       /* CSS code for orderpaymentStatus2.php */

        .toggle-btn {
            width: 50px;
            height: 26px;
            background-color: red; /* Default color for the button */
            border-radius: 13px;
            position: relative;
            cursor: pointer;
            text-align: center;
            line-height: 26px;
            color: #fff;
        }

        .toggle-btn.paid {
            background-color: green; /* Color for the button when the payment status is "Paid" */
        }

        .toggle-btn.delivered {
            background-color: blue; /* Color for the button when the delivery status is "Delivered" */
        }

        .toggle-btn:hover {
            background-color: red; /* Override the hover effect and keep the button color unchanged */
        }
       
    </style>
</head>
<body>
<nav>
    <ul>
        <li class="logo"><a href="home2.php"><img src="logo2.png" alt="Logo" width="125px"></a></li>
        <li><a href="home2.php"> Home</a></li>
        <li><a href="product2.php">Products</a></li>
        <li><a href="userprofile2.php">Seller Profile</a></li>
        <li><a href="order_status2.php">Orders</a></li>
        <li style="float:right"><a href="logout2.php">Logout</a></li>
    </ul>
</nav>


    <?php
    // Establish database connection
    $con = mysqli_connect("localhost", "root", "", "online_store_db");

    // Check if the connection was successful
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    function updatePaymentStatus($con, $checkoutID, $paymentStatus) {
        $query = "UPDATE checkout SET paymentStatus = '$paymentStatus' WHERE checkoutID = '$checkoutID'";
        return mysqli_query($con, $query);
    }
    
    function updateDeliveryStatus($con, $checkoutID, $deliveryStatus) {
        $query = "UPDATE checkout SET deliveryStatus = '$deliveryStatus' WHERE checkoutID = '$checkoutID'";
        return mysqli_query($con, $query);
    }
    


    // Fetch reservation details
    $reservationsResult = mysqli_query($con, "SELECT * FROM reservations");



  // Reservation Details Table
  echo '<h2>Reservation Details</h2>';
  echo '<table>';
  echo '<tr>';
  echo '<th>Reserve ID</th>';
  echo '<th>Buyer ID</th>';
  echo '<th>Product Name</th>';
  echo '<th>Product Price</th>';
  echo '<th>Product Image</th>';
  echo '<th>Product ID</th>';
  echo '<th>Reservation Date</th>';
  echo '<th>Reservation Time</th>';
  echo '</tr>';



    if (mysqli_num_rows($reservationsResult) > 0) {
        while ($row = mysqli_fetch_assoc($reservationsResult)) {

            $reserveID = $row['reserveID'];
            $buyerID = $row['buyerID'];
            $productName = $row['productName'];
            $productPrice = $row['productPrice'];
            $productImage = $row['productImage'];
            $productID = $row['productID'];
            $reserveDate = $row['reserveDate'];
            $reserveTime = $row['reserveTime'];

            echo '<tr>';
            echo '<td>' . $reserveID . '</td>';
            echo '<td>' . $buyerID . '</td>';
            echo '<td>' . $productName . '</td>';
            echo '<td>' . $productPrice . '</td>';
            echo '<td><img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($row['productImage'])) . '" alt="Product Image" width="150px"></td>';
            
            echo '<td>' . $productID . '</td>';
            echo '<td>' . $reserveDate . '</td>';
            echo '<td>' . $reserveTime . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="9">No reservations found.</td></tr>';
    }

    echo '</table>';



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $checkoutID = $_POST['checkoutID'];
    
        if (isset($_POST['paymentStatus-toggle'])) {
            $paymentStatus = $_POST['paymentStatus-toggle'];
    
            if (updatePaymentStatus($con, $checkoutID, $paymentStatus)) {
                echo '<div class="success">Payment Status updated successfully</div>';
            } else {
                echo '<div class="error">Payment Status update failed</div>';
            }
        } else if (isset($_POST['deliveryStatus-toggle'])) {
            $deliveryStatus = $_POST['deliveryStatus-toggle'];
    
            if (updateDeliveryStatus($con, $checkoutID, $deliveryStatus)) {
                echo '<div class="success">Delivery Status updated successfully</div>';
            } else {
                echo '<div class="error">Delivery Status update failed</div>';
            }
        }
    }


    // Fetch orders and buyer details
    $ordersResult = mysqli_query($con, "SELECT checkoutID, buyerName, buyerAddress, buyerEmail, buyerPhoneNumber, productName, productPrice, paymentStatus, deliveryStatus FROM checkout");

    // Order Details Table
    echo '<h2>Order Details</h2>';
    echo '<table>';
    echo '<tr>';
    echo '<th>Checkout ID</th>';
    echo '<th>Buyer Name</th>';
    echo '<th>Buyer Address</th>';
    echo '<th>Buyer Email</th>';
    echo '<th>Buyer Phone Number</th>';
    echo '<th>Product Name</th>';
    echo '<th>Product Price</th>';
    echo '<th>Payment Status</th>';
    echo '<th>Delivery Status</th>';
    echo '</tr>';

    if (mysqli_num_rows($ordersResult) > 0) {
        while ($row = mysqli_fetch_assoc($ordersResult)) {
            $checkoutID = $row['checkoutID']; // Get the Checkout ID from the database
            $paymentStatus = isset($row['paymentStatus']) ? $row['paymentStatus'] : '';
            $deliveryStatus = isset($row['deliveryStatus']) ? $row['deliveryStatus'] : '';

            echo '<tr>';
            echo '<td>' . $checkoutID . '</td>';
            echo '<td>' . $row['buyerName'] . '</td>';
            echo '<td>' . $row['buyerAddress'] . '</td>';
            echo '<td>' . $row['buyerEmail'] . '</td>';
            echo '<td>' . $row['buyerPhoneNumber'] . '</td>';
            echo '<td>' . $row['productName'] . '</td>';
            echo '<td>' . $row['productPrice'] . '</td>';
            echo '<td>';
            echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
            echo '<input type="hidden" name="checkoutID" value="' . $checkoutID . '">';
            echo '<button class="toggle-btn ' . ($paymentStatus == 'Paid' ? 'paid' : '') . '" type="submit" name="paymentStatus-toggle" value="' . ($paymentStatus == 'Paid' ? 'Pending' : 'Paid') . '"></button>';
            echo '</form>';
            echo '</td>';
            echo '<td>';
            echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
            echo '<input type="hidden" name="checkoutID" value="' . $checkoutID . '">';
            echo '<button class="toggle-btn ' . ($deliveryStatus == 'Delivered' ? 'delivered' : '') . '" type="submit" name="deliveryStatus-toggle" value="' . ($deliveryStatus == 'Delivered' ? 'Pending' : 'Delivered') . '"></button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8">No orders found.</td></tr>';
    }

    echo '</table>';

    mysqli_close($con);
    ?>
</table>

<script>
    function toggleStatus(button, statusType) {
        var row = button.parentNode.parentNode;
        var checkoutID = row.querySelector('[name="checkoutID"]').value;
        var statusToggle = row.querySelector('[name="' + statusType + '-toggle"]');

        if (statusToggle.value === 'Pending') {
            statusToggle.value = 'Paid';
            row.querySelector('.toggle-btn').classList.add('paid');
        } else {
            statusToggle.value = 'Pending';
            row.querySelector('.toggle-btn').classList.remove('paid');
        }

        // Perform any additional actions or AJAX requests if needed

        // Submit the form
        row.querySelector('form').submit();
    }
</script>
</body>
</html>