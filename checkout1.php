<?php
session_start();

// Check if buyer is logged in
if (!isset($_SESSION['buyerID'])) {
    header("Location: login1.php");
    exit();
}

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart1.php");
    exit();
}

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user details from the database
$buyerID = $_SESSION['buyerID'];
$query = "SELECT * FROM buyers WHERE buyerID = $buyerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Update buyer details in the session
$_SESSION['buyerName'] = $row['buyerName'];
$_SESSION['buyerPhoneNumber'] = $row['buyerPhoneNumber'];
$_SESSION['buyerEmail'] = $row['buyerEmail'];
$_SESSION['buyerAddress'] = $row['buyerAddress'];

$productIDs = array_keys($_SESSION['cart']);
$productIDsString = implode(",", $productIDs);

$query = "SELECT * FROM products WHERE productID IN ($productIDsString)";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}








// Fetch checkout and buyer details from the database
$query = "SELECT c.productID, c.productPrice, c.productName, b.buyerName, b.buyerAddress, b.buyerEmail, b.buyerPhoneNumber
          FROM checkout c
          JOIN buyers b ON c.buyerID = b.buyerID
          WHERE c.buyerID = $buyerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$checkoutItems = array();
while ($row = mysqli_fetch_assoc($result)) {
    $checkoutItems[] = array(
        'productID' => $row['productID'],
        'productPrice' => $row['productPrice'],
        'productName' => $row['productName'],
        'buyerName' => $row['buyerName'],
        'buyerAddress' => $row['buyerAddress'],
        'buyerEmail' => $row['buyerEmail'],
        'buyerPhoneNumber' => $row['buyerPhoneNumber'],
    );
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


// Update buyer details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $buyerName = isset($_SESSION['buyerName']) ? $_SESSION['buyerName'] : 'Default value';
  $buyerPhoneNumber = isset($_SESSION['buyerPhoneNumber']) ? $_SESSION['buyerPhoneNumber'] : 'Default value';
  $buyerEmail = isset($_SESSION['buyerEmail']) ? $_SESSION['buyerEmail'] : 'Default value';
  $buyerAddress = isset($_SESSION['buyerAddress']) ? $_SESSION['buyerAddress'] : 'Default value';

  // Update buyer details in the session
  $_SESSION['buyerName'] = $buyerName;
  $_SESSION['buyerPhoneNumber'] = $buyerPhoneNumber;
  $_SESSION['buyerEmail'] = $buyerEmail;
  $_SESSION['buyerAddress'] = $buyerAddress;

  // Update buyer details in the database
  $query = "UPDATE checkout SET buyerName = '$buyerName', buyerPhoneNumber = '$buyerPhoneNumber', buyerEmail = '$buyerEmail', buyerAddress = '$buyerAddress' WHERE buyerID = $buyerID";
  $result = mysqli_query($conn, $query);

  if (!$result) {
      die("Error: " . mysqli_error($conn));
  }

  // Update or insert checkout details in the database
  if (empty($checkoutItems)) {
      $query = "INSERT INTO checkout (buyerID, buyerName, buyerPhoneNumber, buyerEmail, buyerAddress, productID, productPrice, productName) VALUES ($buyerID, '$buyerName', '$buyerPhoneNumber', '$buyerEmail', '$buyerAddress', '$productID', '$productPrice', '$productName')";
      $result = mysqli_query($conn, $query);

      if (!$result) {
          die("Error: " . mysqli_error($conn));
      }
  } else {
      $query = "UPDATE checkout SET productID = '$productID', productPrice = '$productPrice', productName = '$productName' WHERE buyerID = $buyerID";
      $result = mysqli_query($conn, $query);

      if (!$result) {
          die("Error: " . mysqli_error($conn));
      }
  }

 
}
mysqli_close($conn);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style30.css">
    <title>CHECKOUT</title>
<style>
body {
    margin: 0;
    font-family:  sans-serif;
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

/*icon*/
  .topnav a i {
    margin-right: 5px;
  font-size: 20px; /* Adjust the font size as desired */
  display: inline-flex;
  align-items: center;
  height: 100%;
  }

  /*checkout heading*/
 .checkout-heading {
  text-align: center;
}

.checkout-heading i {
  margin-right: 10px;
}


/*prodcut details */

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


.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
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
}

input[type=text] {
  width: 100%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 10px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.btn {
  background-color: #536bdd;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn:hover {
  background-color: grey;
}

a {
  color: #2196F3;
}

hr {
  border: 1px solid lightgrey;
}

span.price {
  float: right;
  color: grey;
}

/* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
</style>
</head>
<body>

<div class="topnav">
        <img src="logo2.png" alt="Logo" width="150px">
        <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
        <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a> 
        <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
        <a href="home1.php" ><i class="fas fa-home"></i> HOME</a> 
    </div>

    <h1 class="checkout-heading"><i class="fas fa-shopping-cart"></i> Checkout</h1>
<div class="row">
  <div class="col-75">
    <div class="container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      
        <div class="row">
          <div class="col-50">
            <h3>Details</h3>
            <div>
            <label for="buyerName">Name:</label>
<input type="text" id="buyerName" name="buyerName" value="<?php echo htmlspecialchars($checkoutItems[0]['buyerName'] ?? $_SESSION['buyerName'] ?? ''); ?>" required><br><br>

<label for="buyerPhoneNumber">Phone Number:</label>
<input type="text" id="buyerPhoneNumber" name="buyerPhoneNumber" value="<?php echo htmlspecialchars($checkoutItems[0]['buyerPhoneNumber'] ?? $_SESSION['buyerPhoneNumber'] ?? ''); ?>" required><br><br>

<label for="buyerEmail">Email:</label>
<input type="text" id="buyerEmail" name="buyerEmail" value="<?php echo htmlspecialchars($checkoutItems[0]['buyerEmail'] ?? $_SESSION['buyerEmail'] ?? ''); ?>" required><br><br>

<label for="buyerAddress">Address:</label>
<input type="text" id="buyerAddress" name="buyerAddress" value="<?php echo htmlspecialchars($checkoutItems[0]['buyerAddress'] ?? $_SESSION['buyerAddress'] ?? ''); ?>" required><br><br>

        <input type="submit" name="update_profile" value="Update Profile">
        </form>

      
</div>
</div>

          <div class="col-50">
            <h3>Payment</h3>
            <label for="fname">Accepted Cards</label>
            <div class="icon-container">
              <i class="fa fa-cc-visa" style="color:navy;"></i>
             <i class="fa fa-cc-mastercard" style="color:red;"></i>
             
            </div>
            <label for="cname">Name on Card</label>
            <input type="text" id="cname" name="cardname" placeholder="">
            <label for="ccnum">Credit card number</label>
            <input type="text" id="ccnum" name="cardnumber" placeholder="">
            <label for="expmonth">Exp Month</label>
            <input type="text" id="expmonth" name="expmonth" placeholder="">
            <div class="row">
              <div class="col-50">
                <label for="expyear">Exp Year</label>
                <input type="text" id="expyear" name="expyear" placeholder="">
              </div>
              <div class="col-50">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="">
              </div>
            </div>
          </div>
          
        </div>
        
       
      </form>
    </div>
  </div>
  <div class="col-25">
    <div class="container">
    <div class="order-summary">
    <h2>Product</h2>
    <div class="summary-box">
        <table>
            <tr>
                <th> ID</th>
                <th>Product</th>
                <th>Price</th>
            </tr>


            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo $item['productID']; ?></td>
                    <td><strong><?php echo $item['productName']; ?></strong></td>
                    <td>$<?php echo $item['productPrice']; ?></td>
                </tr>
            <?php endforeach; ?>

         

           


            <tr>
                <td colspan="3" class="total">Delivery Charge: Free</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Total Cost: $<?php echo $totalCost; ?></td>
            </tr>
        </table>
        <form method="post" action="checkoutmessage1.php">
  <button type="submit">Proceed to Checkout</button>
</form>

</div>


    </div>
  </div>
</div>

</body>
</html>

