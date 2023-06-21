<?php
// Define the product categories
$productCategories = array("Stationery", "Clothes", "Electronics", "Health&Beauty", "Shoes", "Others");

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerID = $_SESSION['sellerID'];
    $productName = mysqli_real_escape_string($conn, $_POST['productName']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['productDescription']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['productPrice']);
    $productCategory = mysqli_real_escape_string($conn, $_POST['productCategories']);

    // Handle product image upload
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["productImage"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (!in_array($imageFileType, $allowedExtensions)) {
        $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
            // Insert product details into the database
            $insertQuery = "INSERT INTO products (sellerID, productName, productDescription, productImage, productPrice, productCategories) VALUES ('$sellerID', '$productName', '$productDescription', '$targetFile', '$productPrice', '$productCategory')";
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
    <style>
       
/*product2.php*/
* {box-sizing: border-box}
body {font-family: Verdana, sans-serif; margin:0}
.mySlides {display: none}
img {vertical-align: middle;}


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

*, *:before, *:after {
  box-sizing: inherit;
}

.column {
  float: left;
  width: 33.3%;
  margin-bottom: 16px;
  padding: 0 8px;
}

.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  margin: 8px;
}



.container1 {
  padding: 0 16px;
}

.container1::after, .row::after {
  content: "";
  clear: both;
  display: table;
}

.title {
  color: grey;
}



@media screen and (max-width: 650px) {
  .column {
    width: 100%;
    display: block;
  }
}



h1 {
  margin-bottom: 20px;  
}

h2 {
  margin-top: 20px;
  margin-bottom: 10px;
}


.price {
  color: grey;
  font-size: 22px;
}

.card1 {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.card1 button {
  border: none;
  outline: 0;
  padding: 12px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

.card1 button:hover {
  opacity: 0.7;
}

.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  
  form {
    width: 500px;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  }
  
  table {
    width: 100%;
  }
  
  table td {
    padding: 8px;
  }
  
  table label {
    font-weight: bold;
  }
  
  table input,
  table textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  button {
    width: 100%;
    padding: 10px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #111;
  }
  

.product {
  float: left;
  width: 33.3%;
  padding: 10px; 
}

.product h3 {
  margin-top: 0;
}

.product img {
  max-width: 100%;
  height: auto;
  margin-bottom: 10px;
  /* Force resize the image to a standard size */
  max-width: 300px;
  max-height: 200px;
  justify-content: center;
  align-items: center;
  
}

.product p {
  margin: 0;
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





/* Cart Styles */
.shopping-cart {
  background-color: #fff;
  padding: 20px;
}

.shopping-cart h6 {
  font-size: 18px;
  font-weight: bold;
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

/* Navigation Styles */
nav {
  background-color: #333;
  color: #fff;
}

nav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
}

nav li {
  float: left;
}

nav a {
  display: block;
  color: #fff;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

nav a:hover {
  background-color: #111;
}

nav li:last-child {
  float: right;
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



        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            width: 500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
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

    <div class="form-container">
        <form method="post" enctype="multipart/form-data">
            <h2>Upload Product</h2>

            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (isset($success_message)): ?>
                <div class="success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" name="productName" required>
            </div>

            <div class="form-group">
                <label for="productDescription">Product Description</label>
                <textarea name="productDescription" required></textarea>
            </div>

            <div class="form-group">
                <label for="productImage">Product Image</label>
                <input type="file" name="productImage" required>
            </div>

            <div class="form-group">
                <label for="productPrice">Product Price</label>
                <input type="number" name="productPrice" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="productCategories">Product Category</label>
                <select name="productCategories" required>
                    <?php foreach ($productCategories as $category): ?>
                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" name="submit">Upload</button>
            </div>
        </form>
    </div>

</body>

</html>
