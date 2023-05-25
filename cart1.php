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
}

// Remove product from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    $productID = mysqli_real_escape_string($conn, $_POST['productID']);
    
    // Remove product from cart
    unset($_SESSION['cart'][$productID]);
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
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" type="text/css" href="style5.css">
     <!-- Font Awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />

<!-- Bootstrap CDN -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>



<nav>
    <ul>
    <li class="logo"><a href="home1.php"><img src="logo.png" alt="Logo" width="125px"></a></li>
      <li><a href="home1.php">Home</a></li>
     <li><a href="userprofile1.php">User Profile</a></li> 
       <li><a href="cart1.php"> Cart</a></li>
        <li style="float:right"><a href="logout1.php">Logout</a></li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row px-5">
        <div class="col-md-7">
            <div class="shopping-cart">
                <h6>My Cart</h6>
                <hr>

    <?php if (!empty($cartItems)): ?>
<table>
<thead>
<tr>
<th>Product Image</th>
<th>Product Name</th>
<th>Price</th>
<th>Product Description</th>
<th>Quantity</th>
<th>Subtotal</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($cartItems as $item): ?>
<tr>
<td><img src="<?php echo $item['productImage']; ?>" alt="Product Image" style="width: 100px; height: 100px;"></td>
<td><?php echo $item['productName']; ?></td>
<td>$<?php echo $item['productPrice']; ?></td>
<td>$<?php echo $item['productDescription']; ?></td>

<td>
<form method="post" action="cart1.php">
<input type="hidden" name="productID" value="<?php echo $item['productID']; ?>">
<input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
<button type="submit" name="update_quantity">Update</button>
</form>
</td>
<td>$<?php echo $item['subtotal']; ?></td>
<td>
<form method="post" action="cart1.php">
<input type="hidden" name="productID" value="<?php echo $item['productID']; ?>">
<button type="submit" name="remove_from_cart">Remove</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<tr>
<td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
<td colspan="2">$<?php echo $totalCost; ?></td>
</tr>
</tbody>
</table>
<form method="post" action="checkout1.php">
        <button type="submit" name="checkout">Checkout</button>
    </form>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

</div>
        </div>
        <div class="col-md-4 offset-md-1 border rounded mt-5 bg-white h-25">

            <div class="pt-4">
                <h6>PRICE DETAILS</h6>
                <hr>
                <div class="row price-details">
                    <div class="col-md-6">
                        <?php
                            if (isset($_SESSION['cart'])){
                                $count  = count($_SESSION['cart']);
                                echo "<h6>Price ($count items)</h6>";
                            }else{
                                echo "<h6>Price (0 items)</h6>";
                            }
                        ?>
                        <h6>Delivery Charges</h6>
                        <hr>
                        <h6>Amount Payable</h6>
                    </div>
                    <div class="col-md-6">
                        <h6>$<?php echo $totalCost; ?></h6>
                        <h6 class="text-success">FREE</h6>
                        <hr>
                        <h6>$<?php
                            echo $totalCost;
                            ?></h6>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</div>
</body>
</html>
<?php
mysqli_close($conn);
?>
