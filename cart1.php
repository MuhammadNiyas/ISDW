<?php
session_start();

// Check if buyer is logged in
if (!isset($_SESSION['buyerID'])) {
    header("Location: login1.php");
    exit();
}

// Retrieve buyer information from session
if (!isset($_SESSION['buyers'])) {
    $_SESSION['buyers'] = array(); // Initialize an empty array for "buyers"
}

$buyers = $_SESSION['buyers'];

// Connect to the database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$buyerID = $_SESSION['buyerID'];
$query = "SELECT buyerName, buyerEmail, buyerPhoneNumber, buyerAddress FROM buyers WHERE buyerID = '$buyerID'";
$result = mysqli_query($conn, $query);


$buyerInfo = mysqli_fetch_assoc($result);
$buyerName = $buyerInfo['buyerName'];
$buyerEmail = $buyerInfo['buyerEmail'];
$buyerPhoneNumber = $buyerInfo['buyerPhoneNumber'];
$buyerAddress = $buyerInfo['buyerAddress'];

// Update buyer details if session variable is set
if (isset($_SESSION['updatedBuyerInfo'])) {
    $updatedBuyerInfo = $_SESSION['updatedBuyerInfo'];
    $buyerName = $updatedBuyerInfo['buyerName'];
    $buyerEmail = $updatedBuyerInfo['buyerEmail'];
    $buyerPhoneNumber = $updatedBuyerInfo['buyerPhoneNumber'];
    $buyerAddress = $updatedBuyerInfo['buyerAddress'];

    // Clear the session variable
    unset($_SESSION['updatedBuyerInfo']);
}


// Update product quantity in the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $productID = mysqli_real_escape_string($conn, $_POST['productID']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    // Validate quantity
    if ($quantity < 1) {
        // Remove product from cart if quantity is 0 or negative
        unset($_SESSION['cart'][$productID]);
    } else {
        // Update quantity in cart
        $_SESSION['cart'][$productID] = $quantity;
    }

    if (empty($_SESSION['cart'])) {
        // Delete buyer information if cart is empty
        unset($_SESSION['buyers']);

        // Redirect to home1.php
        header("Location: home1.php");
        exit();
    }
}

// Remove product from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    $productID = mysqli_real_escape_string($conn, $_POST['productID']);

    // Remove product from cart
    unset($_SESSION['cart'][$productID]);

    if (empty($_SESSION['cart'])) {
        // Delete buyer information if cart is empty
        unset($_SESSION['buyers']);

    }

    
}

// Retrieve products from the cart
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
        $productDescription = $row['productDescription'];
        $quantity = $_SESSION['cart'][$productID];
        $subtotal = $productPrice * $quantity;
        $productImage = $row['productImage'];

        $cartItems[] = array(
            'productID' => $productID,
            'productName' => $productName,
            'productPrice' => $productPrice,
            'productDescription' => $productDescription,
            'productImage' => $productImage,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        );

        $totalCost += $subtotal;
        $totalCost += 0; // Delivery charge is free, so add 0

    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updatedBuyerInfo = array(
        'buyerName' => $_POST['buyerName'],
        'buyerEmail' => $_POST['buyerEmail'],
        'buyerPhoneNumber' => $_POST['buyerPhoneNumber'],
        'buyerAddress' => $_POST['buyerAddress']
    );

    // Store the updated buyer information in the session
    $_SESSION['updatedBuyerInfo'] = $updatedBuyerInfo;

    // Redirect to the cart page to see the updated information
    header("Location: cart1.php");
    exit();
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyerName = mysqli_real_escape_string($conn, $_POST['buyerName']);
    $buyerPhoneNumber = mysqli_real_escape_string($conn, $_POST['buyerPhoneNumber']);
    $buyerEmail = mysqli_real_escape_string($conn, $_POST['buyerEmail']);
    $buyerAddress = mysqli_real_escape_string($conn, $_POST['buyerAddress']);

    // Update user details in the database
    $updateQuery = "UPDATE buyers SET buyerName = '$buyerName', buyerPhoneNumber = '$buyerPhoneNumber', buyerEmail = '$buyerEmail', buyerAddress = '$buyerAddress' WHERE buyerID = $buyerID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $_SESSION['buyerName'] = $buyerName;
        $_SESSION['buyerPhoneNumber'] = $buyerPhoneNumber;
        $_SESSION['buyerEmail'] = $buyerEmail;
        $_SESSION['buyerAddress'] = $buyerAddress;

        $_SESSION['success_message'] = "Profile updated successfully.";
        header("Location: home1.php");
        exit();
    } else {
        $error_message = "Profile update failed. Please try again.";
    }
}



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<title>Cart</title>
<style>
body {
    margin: 0;
    font-family:  sans-serif;
    
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
    text-transform: uppercase
  }
  
  .topnav a:hover {
    background-color: #ddd;
    color: black;
  }
  
  .topnav a.active {
    background-color: #536bdd;
    color: white;
  }




  .cart-title {
    text-align: center;
    font-size: 28px; /* Change the font size as desired */
}

.cart-icon {
    margin-right: 5px;
}

.cart-title:hover {
    color: #536bdd; /* Change to desired hover color */
}




  /button hover/
  button[name="update_quantity"] {
    padding: 5px 10px;
    background-color: #333;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease; /* Add transition for smooth effect */
    font-size: 13px; /* Increase the font size as desired */
  }
  
  button[name="update_quantity"]:hover {
    background-color: #536bdd;
  }

  .remove-button {
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
  }

  /* Styles for the button when hovered */
  .remove-button:hover {
    background-color: #536bdd;
  }


  /icon/
  .topnav a i {
    margin-right: 5px;
  font-size: 20px; /* Adjust the font size as desired */
  display: inline-flex;
  align-items: center;
  height: 100%;
  }

  
  /* Cart Styles */
.shopping-cart {
    background-color: #fff;
    padding: 20px;
   
  }
  
  .shopping-cart h2 {
 
    font-weight: bold;
    text-align: center;
    font-size: 28px; /* Change the font size as desired */
  }
  
  .shopping-cart hr {
    margin-top: 10px;
    margin-bottom: 10px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  table th,
  table td {
    padding: 8px;
    text-align: left;
  }
  
  table th {
    background-color: #f2f2f2;
    font-weight: bold;
  }
  
  table tr:nth-child(even) {
    background-color: #f9f9f9;
  }
  
  table tr:hover {
    background-color: #f5f5f5;
  }
  
  .price-details {
    margin-top: 20px;
  }
  
  .price-details h6 {
    font-weight: bold;
  }
  
  .price-details hr {
    margin-top: 10px;
    margin-bottom: 10px;
  }
  
  .price-details .text-success {
    color: green;
  }


/*order summary split with shopping cart */
.cart-container {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.shopping-cart {
  flex: 80%; /* Adjust the width as desired */
  padding-right: 1px;
}

.order-summary {
  flex: 30%; /* Adjust the width as desired */
  padding: 30px;
  border-radius: 5px;
  margin-right: 1%;
}

  .summary-box {
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
    width: 100%; /* Adjust the width as needed */
}

.buyer-details {
    width: 100%; /* Adjust the width as needed */
    text-align:right;
}

.buyer-info {
    margin-bottom: 20px;
   
}
  
  
  .order-summary h2 {
    margin-top: 10px;
    text-align: center;
    font-size: 29px;
  }
  
  .summary-table {
    width: 100%;
  }
  
  .summary-table th,
  .summary-table td {
    padding: 10px;
    border-bottom: 1px solid #ccc;
    text-align: left;
  }
  
  .summary-table .total {
    font-weight: bold;
  }
  
  .summary-table td:last-child {
    text-align: right;
  }
  
  .buyer-info {
    text-align: left;
  }
  
  .buyer-info h3 {
    font-size: 20px;
    margin-bottom: 10px;
  }
  
  .buyer-info p {
    margin-bottom: 5px;
    text-align: left;
  }
  
  .checkout-button {
    display: block;
    width: 107%;
    padding: 10px;
    background-color: #000;
    color: #fff;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    border: none;
    cursor: pointer;
    margin-top: 20px;
    
  }
  
  .checkout-button:hover {
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




  /* Other Styles */
h1 {
    text-align: center;
    margin-top: 20px;
  }
  
  p {
    text-align: center;
    margin-top: 20px;
  }
  
  button {
    padding: 5px 10px;
    background-color: #333;
    color: #fff;
    border: none;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #111;
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
        <a href="reserve.php" ><i class="far fa-calendar-alt"></i> RESERVE</a>
        <a href="cart1.php" class="active"><i class="fas fa-shopping-cart"></i> CART</a>
        <a href="home1.php"><i class="fas fa-home"></i> HOME</a>
    </div>

    <div class="cart-container">
    <div class="shopping-cart">
        <h2>My Cart</h2>
        <hr>
        <table>
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cartItems as $item) : ?>
                <tr>
                    <td><img src="<?php echo $item['productImage']; ?>" alt="Product Image" width="50px"></td>
                    <td><?php echo $item['productName']; ?></td>
                    <td>$<?php echo $item['productPrice']; ?></td>
                    <td>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="productID" value="<?php echo $item['productID']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo $item['subtotal']; ?></td>
                    <td>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="productID" value="<?php echo $item['productID']; ?>">
                            <button type="submit" name="remove_from_cart">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>


    <div class="order-summary">
    <h2>Order Summary</h2>
    <div class="summary-box">
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product</th>
                <th>Price</th>
            </tr>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo $item['productID']; ?></td>
                    <td><strong><?php echo $item['productName']; ?></strong></td>
                    <td>$<?php echo $item['productPrice']; ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="total">Subtotal: $<?php echo $item['subtotal']; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="total">Delivery Charge: Free</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Total Cost: $<?php echo $totalCost; ?></td>
            </tr>
        </table>
    </div>
    <form method="post" action="checkout1.php">
        <button type="submit" class="checkout-button" <?php echo empty($cartItems) ? 'disabled' : ''; ?>>Proceed to Checkout</button>
    </form>
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
     
      

</footer>

    
</body>
</html>