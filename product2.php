<?php
session_start();

// Check if seller is logged in
if (!isset($_SESSION['sellerID'])) {
    header("Location: login2.php");
    exit();
}

// Connect to database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerID = $_SESSION['sellerID'];
    $productName = mysqli_real_escape_string($conn, $_POST['productName']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['productDescription']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['productPrice']);

    // Handle product image upload
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["productImage"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (!in_array($imageFileType, $allowedExtensions)) {
        $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
            // Insert product details into database
            $insertQuery = "INSERT INTO products (sellerID, productName, productDescription, productImage, productPrice) VALUES ('$sellerID', '$productName', '$productDescription', '$targetFile', '$productPrice')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                $success_message = "Product uploaded successfully.";
            } else {
                $error_message = "Failed to upload product. Please try again.";
            }
        } else {
            $error_message = "Error uploading product image. Please try again.";
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Seller User Page</title>
    <link rel="stylesheet" type="text/css" href="style16.css">
</head>
<body>

<nav>
            <ul>
            <li class="logo"><a href="home1.php"><img src="logo.png" alt="Logo" width="125px"></a></li>
            <li><a href="home2.php"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="product2.php"><i class="fa fa-product-hunt"></i>Products</a></li>
      <li><a href="userprofile2.php"><i class="fa fa-user"></i> Seller Profile</a></li>
        <li style="float:right"><a href="logout2.php">Logout</a></li>
            </ul>
        </nav>


    
        <?php if(isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if(isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <div class="form-container">
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td><label for="productName">Product Name:</label></td>
                <td><input type="text" id="productName" name="productName" required></td>
            </tr>
            <tr>
                <td><label for="productDescription">Product Description:</label></td>
                <td><textarea id="productDescription" name="productDescription" required></textarea></td>
            </tr>
            <tr>
                <td><label for="productPrice">Product Price:</label></td>
                <td><input type="text" id="productPrice" name="productPrice" required></td>
            </tr>
            <tr>
                <td><label for="productImage">Product Image:</label></td>
                <td><input type="file" id="productImage" name="productImage" accept="image/*" required></td>
            </tr>
        </table>

        <button type="submit">Upload Product</button>
    </form>
</div>




      




                

</div>

</body>
</html>