<?php
session_start();

// Check if seller is logged in
if (!isset($_SESSION['sellerID'])) {
    header("Location: login2.php");
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

// Retrieve products uploaded by the seller
$sellerID = $_SESSION['sellerID'];
$query = "SELECT * FROM products WHERE sellerID = $sellerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Update product details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $productID = mysqli_real_escape_string($conn, $_POST['productID']);
    $productName = mysqli_real_escape_string($conn, $_POST['productName']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['productDescription']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['productPrice']);

    // Update product details in the database
    $updateQuery = "UPDATE products SET productName = '$productName', productDescription = '$productDescription', productPrice = '$productPrice' WHERE productID = $productID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $success_message = "Product details updated successfully.";
        echo '<script>window.location.href = window.location.href;</script>'; // Redirect to the same page after successful update
        exit();
    } else {
        $error_message = "Failed to update product details. Please try again.";
    }
}

// Delete product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $productID = mysqli_real_escape_string($conn, $_POST['productID']);

    // Delete product from the database
    $deleteQuery = "DELETE FROM products WHERE productID = $productID";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        $success_message = "Product deleted successfully.";
        echo '<script>window.location.href = window.location.href;</script>'; // Redirect to the same page after successful deletion
        exit();
    } else {
        $error_message = "Failed to delete product. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style13.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<nav>
    <ul>
        <li class="logo"><a href="home1.php"><img src="logo.png" alt="Logo" width="125px"></a></li>
        <li><a href="home2.php">Home</a></li>
        <li><a href="product2.php">Products</a></li>
        <li><a href="userprofile2.php">Seller Profile</a></li>
        <li style="float:right"><a href="logout2.php">Logout</a></li>
    </ul>
</nav>

<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Product Description</th>
        <th>Product Price</th>
        <th>Product Image</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['productID']; ?></td>
            <td><?php echo $row['productName']; ?></td>
            <td><?php echo $row['productDescription']; ?></td>
            <td>$<?php echo $row['productPrice']; ?></td>
            <td><img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents($row['productImage'])); ?>" alt="Product Image" style="max-width: 150px; max-height: 150px;"></td>
            <td>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="productID" value="<?php echo $row['productID']; ?>">
                    <div>
                        <label for="productName">Product Name:</label>
                        <input type="text" id="productName" name="productName" value="<?php echo $row['productName']; ?>" required>
                    </div>
                    <div>
                        <label for="productDescription">Product Description:</label>
                        <textarea id="productDescription" name="productDescription" required><?php echo $row['productDescription']; ?></textarea>
                    </div>
                    <div>
                        <label for="productPrice">Product Price:</label>
                        <input type="text" id="productPrice" name="productPrice" value="<?php echo $row['productPrice']; ?>" required>
                    </div>
                    <div>
                        <label for="productImage">Product Image:</label>
                        <input type="file" id="productImage" name="productImage" accept="image/*">
                    </div>
                    <div>
                        <button type="submit" name="update">Update Product</button>
                        <button type="submit" name="delete">Delete Product</button>
                    </div>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php if (isset($error_message)): ?>
    <div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <div class="success"><?php echo $success_message; ?></div>
<?php endif; ?>

</body>
</html>
