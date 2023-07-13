<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <style> 
        /* CSS code for orderstatus2.php */
        
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
            color: #fff;
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

        /* Toggle button styling */
        .toggle-btn {
            width: 50px;
            height: 26px;
            background-color: #ccc;
            border-radius: 13px;
            position: relative;
            cursor: pointer;
        }

        .toggle-btn .slider {
            position: absolute;
            top: 10%;
            transform: translate(5%, -50%);
            left: 3px;
            width: 20px;
            height: 20px;
            background-color: #fff;
            border-radius: 50%;
            transition: transform 0.2s ease-in-out;
        }

        .toggle-btn.active .slider {
            transform: translate(24px, -50%);
        }

        .toggle-btn:before {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 13px;
            background-color: #fff;
            opacity: 0.6;
            z-index: -1;
        }
    </style>
</head>
<body>

<nav>
    <ul>
        <li class="logo"><a href="home2.php"><img src="logo.png" alt="Logo" width="125px"></a></li>
        <li><a href="home2.php">Home</a></li>
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

// Function to update the status in the database
function updateStatus($con, $orderID, $status) {
    $orderID = mysqli_real_escape_string($con, $orderID);
    $status = mysqli_real_escape_string($con, $status);
    
    $query = "UPDATE checkout SET status = '$status' WHERE orderID = '$orderID'";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        return true; // Status updated successfully
    } else {
        return false; // Status update failed
    }
}

// Handle status update when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderID = $_POST['orderID'];
    $status = $_POST['status-toggle'];
    
    if (updateStatus($con, $orderID, $status)) {
        echo '<div class="success">Status updated successfully</div>';
    } else {
        echo '<div class="error">Status update failed</div>';
    }
}

// Fetch orders and buyer details
$result = mysqli_query($con, "SELECT buyerName, buyerAddress, buyerEmail, buyerPhoneNumber, productName, productPrice, status FROM checkout");

// Toggle button HTML and form
echo '<form method="POST">';
echo '<table>';
echo '<tr>';
echo '<th>Order ID</th>';
echo '<th>Buyer Name</th>';
echo '<th>Buyer Address</th>';
echo '<th>Buyer Email</th>';
echo '<th>Buyer Phone Number</th>';
echo '<th>Product Name</th>';
echo '<th>Product Price</th>';
echo '<th>Status</th>';
echo '</tr>';

if (mysqli_num_rows($result) > 0) {
    $orderCounter = 1; // Initialize the order counter
    while ($row = mysqli_fetch_assoc($result)) {
        $orderID = '' . str_pad($orderCounter, 4, '0', STR_PAD_LEFT); // Generate the order ID
        $status = isset($row['status']) ? $row['status'] : '';

        echo '<tr>';
        echo '<td>' . $orderID . '</td>';
        echo '<td>' . $row['buyerName'] . '</td>';
        echo '<td>' . $row['buyerAddress'] . '</td>';
        echo '<td>' . $row['buyerEmail'] . '</td>';
        echo '<td>' . $row['buyerPhoneNumber'] . '</td>';
        echo '<td>' . $row['productName'] . '</td>';
        echo '<td>' . $row['productPrice'] . '</td>';
        echo '<td>';
        echo '<div class="toggle-btn ' . ($status == 'Available' ? 'active' : '') . '">';
        echo '<input type="hidden" name="orderID" value="' . $orderID . '">';
        echo '<input type="hidden" name="status-toggle" value="' . ($status == 'Available' ? 'Unavailable' : 'Available') . '">';
        echo '<div class="slider"></div>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';

        $orderCounter++; // Increment the order counter
    }
} else {
    echo '<tr><td colspan="7">No orders found.</td></tr>';
}

echo '</table>';
echo '</form>';

mysqli_close($con);
?>

<script>
    // JavaScript code for the toggle button functionality
    const toggleButtons = document.querySelectorAll('.toggle-btn');

    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            button.classList.toggle('active');
            const orderIDInput = button.querySelector('input[name="orderID"]');
            const statusInput = button.querySelector('input[name="status-toggle"]');
            const orderID = orderIDInput.value;
            const status = button.classList.contains('active') ? 'Available' : 'Unavailable';

            // Update the status in the form before submitting
            statusInput.value = status;
            
            // Submit the form
            button.closest('form').submit();
        });
    });
</script>
</body>
</html>