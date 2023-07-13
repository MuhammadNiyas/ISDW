<?php
session_start();

// Check if seller is logged in
if (!isset($_SESSION['sellerID'])) {
    header("Location: login2.php");
    exit();
}

// After validating the login credentials and confirming a successful login
// Set the sellerName value in the session
$sellerID = $_SESSION['sellerID'];

// Connect to the database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT sellerName FROM sellers WHERE sellerID = $sellerID";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['sellerName'] = $row['sellerName'];
} else {
    // Handle the case where the sellerName is not found
    // You can redirect the user to an error page or display an appropriate message
}

// Filter products by productCategories if a productCategories is selected
$productCategories = isset($_GET['productCategories']) ? $_GET['productCategories'] : '';
$productCategoriesQuery = $productCategories ? "AND productCategories = '$productCategories'" : '';

$query = "SELECT * FROM products WHERE sellerID = $sellerID $productCategoriesQuery";
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
    $productCategories = mysqli_real_escape_string($conn, $_POST['productCategories']);

    // Update product details in the database
    $updateQuery = "UPDATE products SET productName = '$productName', productDescription = '$productDescription', productPrice = '$productPrice', productCategories = '$productCategories' WHERE productID = $productID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $success_message = "Product details updated successfully.";
        header("Location: ".$_SERVER['PHP_SELF']."?productCategories=".$productCategories);
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
        header("Location: ".$_SERVER['PHP_SELF']."?productCategories=".$productCategories);
        exit();
    } else {
        $error_message = "Failed to delete product. Please try again.";
    }
}

// Upload product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    $productName = mysqli_real_escape_string($conn, $_POST['productName']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['productDescription']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['productPrice']);
    $productCategories = mysqli_real_escape_string($conn, $_POST['productCategories']);

    // Upload product image
    $productImage = $_FILES['productImage']['tmp_name'];
    $productImage = addslashes(file_get_contents($productImage));

    // Insert product details into the database
    $insertQuery = "INSERT INTO products (sellerID, productName, productDescription, productPrice, productCategories, productImage) VALUES ($sellerID, '$productName', '$productDescription', '$productPrice', '$productCategories', '$productImage')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        $success_message = "Product uploaded successfully.";
        header("Location: ".$_SERVER['PHP_SELF']."?productCategories=".$productCategories);
        exit();
    } else {
        $error_message = "Failed to upload product. Please try again.";
    }
}

// Retrieve all productCategories from the database
$productCategoriesQuery = "SELECT DISTINCT productCategories FROM products";
$productCategoriesResult = mysqli_query($conn, $productCategoriesQuery);

if (!$productCategoriesResult) {
    die("Error: " . mysqli_error($conn));
}

$productCategories = mysqli_fetch_all($productCategoriesResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style> 
        /* CSS code for home2.php */

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .mySlides {
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
        nav {
  display: flex;
  justify-content: space-between;
  background-color: #000000;
}

nav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  display: flex;
  align-items: center;
}

nav ul li {
  margin-left: 10px;
}

nav ul li a {
  color: #fff;
  text-decoration: none;
  padding: 14px 16px;
}

nav ul li a:hover {
  background-color: #6b5a5a;
}

nav .logout {
  margin-right: 10px;
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

        #welcomeMessage {
        /* Remove or update the display property to make the welcome message visible */
    }

    </style>

<script>
        // JavaScript code to hide the welcome message after 5 seconds
        document.addEventListener("DOMContentLoaded", function() {
            var welcomeMessage = document.getElementById("welcomeMessage");
            setTimeout(function() {
                welcomeMessage.style.display = "none";
            }, 5000); // Hide the welcome message after 5 seconds (5000 milliseconds)
        });
    </script>


</head>
<body>
    <nav>
        <ul>
            <li class="logo"><a href="home2.php"><img src="logo2.png" alt="Logo" width="150px"></a></li>
            <li><a href="home2.php">Home</a></li>
            <li><a href="product2.php">Products</a></li>
            <li><a href="userprofile2.php">Seller Profile</a></li>
            <li><a href="order_status2.php">Orders</a></li>
            <li class="logout"><a href="logout2.php">Logout</a></li>
        </ul>
    </nav>

    <?php
    // Check if the welcome message has been displayed
    if (!isset($_SESSION['welcomeDisplayed'])) {
        // Display the welcome message
        echo '<h2 id="welcomeMessage">Welcome, ' . $_SESSION['sellerName'] . '</h2>';
        
        // Set the flag in the session to indicate the welcome message has been displayed
        $_SESSION['welcomeDisplayed'] = true;
    }
    ?>

    <form method="get" action="home2.php">
        <label for="productCategories">Filter by category:</label>
        <select name="productCategories" id="productCategories">
            <option value="">All Products</option>
            <?php foreach ($productCategories as $category): ?>
                <option value="<?php echo $category['productCategories']; ?>" <?php echo ($category['productCategories'] === $productCategories) ? 'selected' : ''; ?>>
                    <?php echo $category['productCategories']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Product Description</th>
            <th>Product Price</th>
            <th>Product Image</th>
            <th>Action</th>
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
                            <label for="productCategories">Product Categories:</label>
                            <select id="productCategories" name="productCategories" required>
                                <option value="">Select a Category</option>
                                <?php foreach ($productCategories as $productCategory): ?>
                                    <option value="<?php echo $productCategory['productCategories']; ?>" <?php echo $productCategory['productCategories'] === $row['productCategories'] ? 'selected' : ''; ?>>
                                        <?php echo $productCategory['productCategories']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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

</body>
</html>