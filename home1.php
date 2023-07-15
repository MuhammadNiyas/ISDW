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


// Add product reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve_product'])) {
    $productID = $_POST['productID'];

    // Check if the product is already reserved by the buyer
    $reservationQuery = "SELECT * FROM reservations WHERE buyerID = {$_SESSION['buyerID']} AND productID = $productID";
    $reservationResult = mysqli_query($conn, $reservationQuery);

    if (!$reservationResult) {
        die("Error: " . mysqli_error($conn));
    }

    if ($reservationResult->num_rows > 0) {
        // Product already reserved by the buyer
    
    } else {
        // Retrieve product details from the database
        $productQuery = "SELECT productName, productPrice, productImage FROM products WHERE productID = $productID";
        $productResult = mysqli_query($conn, $productQuery);

        if (!$productResult) {
            die("Error: " . mysqli_error($conn));
        }

        if ($productResult->num_rows > 0) {
            // Fetch product details
            $productData = mysqli_fetch_assoc($productResult);
            $productName = $productData['productName'];
            $productPrice = $productData['productPrice'];
            $productImage = $productData['productImage'];

            $reserveDate = date('Y-m-d');
            $reserveTime = date('H:i:s');

            $insertQuery = "INSERT INTO reservations (buyerID, productID, productName, productPrice, productImage, reservedate, reservetime) 
            VALUES ({$_SESSION['buyerID']}, $productID, '$productName', $productPrice, '$productImage', '$reserveDate', '$reserveTime')";
$insertResult = mysqli_query($conn, $insertQuery);

            if (!$insertResult) {
                die("Error: " . mysqli_error($conn));
            }

            // Retrieve the generated reserveID
            $reserveID = mysqli_insert_id($conn);

 // Redirect to the reservation page
            header("Location: reserve.php?reserveID=$reserveID");
            exit();
            
        }
    }
}




// Retrieve products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}


// Add product to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
  $productID = $_POST['productID'];

  // Check if the product is already in the cart
  if (!isset($_SESSION['cart'][$productID])) {
      // Add the product to the cart with a quantity of 1
      $_SESSION['cart'][$productID] = 1;

      // Retrieve product details from the database
      $productQuery = "SELECT productName, productPrice, productImage FROM products WHERE productID = $productID";
      $productResult = mysqli_query($conn, $productQuery);

      if (!$productResult) {
          die("Error: " . mysqli_error($conn));
      }

      if ($productResult->num_rows > 0) {
          // Fetch product details
          $productData = mysqli_fetch_assoc($productResult);
          $productName = $productData['productName'];
          $productPrice = $productData['productPrice'];
          $productImage = $productData['productImage'];

          // Insert the product into the cart database
          $insertQuery = "INSERT INTO cart (productID, productName, productPrice, productImage) 
                          VALUES ($productID, '$productName', $productPrice, '$productImage')";
          $insertResult = mysqli_query($conn, $insertQuery);

          if (!$insertResult) {
              die("Error: " . mysqli_error($conn));
          }
      }
  }
}


// Calculate total number of items in the cart
$totalItems = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $totalItems += $quantity;
    }
}

// Update cart count in session if it's not already set
if (!isset($_SESSION['cartCount'])) {
    $_SESSION['cartCount'] = $totalItems;
}



$searchTerm = '';

// Check if the search query parameter is set
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];

    // Prepare the SQL query with search conditions
    $sql = "SELECT productID, productName, productPrice, productImage, productDescription FROM products WHERE productCategories LIKE '%$searchTerm%' OR productName LIKE '%$searchTerm%'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Display the search results as "Latest Products"


    }
}


// Category filter
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedCategory = "";
    
    if (isset($_POST['category'])) {
        $selectedCategory = $_POST['category'];
    }

    if (isset($_POST['filter_button'])) {
        if (!empty($selectedCategory)) {
            $query = "SELECT * FROM products WHERE productCategories = '$selectedCategory'";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Error: " . mysqli_error($conn));
            }
        }
    }
}

// Retrieve reserved product IDs
$reservedProductIDs = [];
$reservationQuery = "SELECT productID FROM reservations";
$reservationResult = mysqli_query($conn, $reservationQuery);

if ($reservationResult && mysqli_num_rows($reservationResult) > 0) {
    while ($reservationData = mysqli_fetch_assoc($reservationResult)) {
        $reservedProductIDs[] = $reservationData['productID'];
    }
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
<title>HOMEPAGE</title>
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
        text-transform: uppercase;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    .topnav a:hover {
        background-color: #ddd;
        color: black;
    }
  
  .topnav a.active {
    background-color: #536bdd;
    color: white;
  }

.topnav a i {
  margin-right: 5px;
  font-size: 20px;
}

.topnav .logo {
  margin-right: 10px;
}

.topnav .search-bar {
  position: relative;
  top: -60px;
  left: 42%;
  transform: translateX(-50%);
  width: 800px;
  z-index: 9999;
}

.topnav input[type="text"],
.topnav select {
  width: 300px;
  height: 30px;
  padding: 5px;
  margin-right: 10px;
}

.topnav button[type="submit"] {
  margin-right: 50px;
  height: 30px;
  padding: 5px 10px;
  background: #000;
  font-size: 15px;
  cursor: pointer;
  border: none;
  color: #fff;
}



/*header*/
  .header {
    padding: 180px;
    text-align: center;
    background-image: url(image21.jpeg);
    color: white;
    font-size: 30px;
  }
  
  

 /*about us and our team*/
  html {
    box-sizing: border-box;
  }

  *, *:before, *:after {
    box-sizing: inherit;
  }

 
  .about-section {
    margin-top: 50px;
    padding: 50px;
    text-align: center;
    background-color: #000;
    color: white;
  }

  .container {
    padding: 0 16px;
  }

  .container::after, .row::after {
    content: "";
    clear: both;
    display: table;
  }

  .title {
    color: grey;
  }

  .button {
    text-decoration: none;
    border: none;
    outline: 0;
    display: inline-block;
    padding: 8px;
    color: white;
    background-color: #536bdd;
    text-align: center;
    cursor: pointer;
    width: 100%;
  }

  .button:hover {
    background-color: grey;
  }

  @media screen and (max-width: 768px) {
    .column {
      flex: 50%;
      max-width: 50%;
    }
  }

  @media screen and (max-width: 600px) {
    .column {
      flex: 100%;
      max-width: 100%;
    }
  }

 /*home category section starts */

 .home-category {
      text-align: center;
      margin-top: 50px;
    }

    .category-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin-top: 20px;
    }

    .category-item {
      text-decoration: none;
      color: black;
      cursor: pointer;
      background-color: transparent;
      border: none;
    }

    .category-item button {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #ccc;
      border-radius: 0.5rem;
      padding: 1.5rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      background-color: transparent;
      transition: background-color 0.3s ease;
      width: 230px;
    }

    .category-item button:hover {
      background-color: #536bdd;
    }

    .category-item img {
      height: 100px;
      width: 100px;
      object-fit: contain;
      margin-bottom: 1rem;
    }

    .category-item h3 {
      color: #000;
    }
  
 /*product section */
          
          .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .product {
            width: 300px;
            height: 400px;
            margin: 10px;
            padding: 10px;
            box-sizing: border-box;
            text-align: center;
            font-family: Arial, sans-serif;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .product h3 {
            margin-top: 0;
        }

        .product img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product p {
            margin: 0;
        }

        .product button {
            border: none;
            outline: none;
            padding: 12px;
            color: white;
            background-color: #292929;
            text-align: center;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
        }

        .product button:hover {
            background-color: #536bdd;
        }

        .reserved-product {
            background-color: red;
            color: white;
            padding: 5px;
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
</style>
 </head>
<body>
<div class="topnav" >
    <img src="logo2.png" alt="Logo" width="150px">
    <a href="view_transaction.php"><i class="	fas fa-money-check-alt"></i></a>
    <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> </a>
    <a href="process_form.php"><i class="fas fa-paper-plane"></i> CONTACT US</a> 
    <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a> 
    <a href="reserve.php"><i class="far fa-calendar-alt"></i> Reserve</a> 
    <a href="cart1.php"><i class="fas fa-shopping-cart"></i><?php echo $totalItems;?> CART</a>
    <a href="home1.php" class="active"><i class="fas fa-home"></i> HOME</a>
    <div class="search-bar">
    <form action="home1.php" method="GET">
        <input type="text" name="searchTerm" placeholder="SEARCH">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
    </div>
</div>
</div>

<div class="header">
<h1>WELCOME TO OUR WEBSITE</h1>
<p>Feel free to browse around and check out our services!</p>
</div>

<h1 style="text-align:center; margin-top: 20px; margin-bottom: 20px;"; >About Us</h1>
<div class="about-section">
<p>At  BUY AND SELL DISTED COLLEGE, we are passionate about bringing you the best deals on high-quality used systems.
  <p> We understand that technology moves at a rapid pace, and keeping up with the latest advancements can be expensive.
    <p> That's why we've created a platform where you can buy and sell used systems, making it easier and more affordable for everyone.</p>
</div>


<h1 style="text-align:center">Category</h1>
  <section class="home-category">
    <div class="category-container">
      <form method="post" action="" class="category-item">
        <input type="hidden" name="category" value="">
        <button type="submit" name="filter_button">
          <img src="icon-12.png" alt="All Categories" class="category-image">
          <h3 class="category-title">All Categories</h3>
        </button>
      </form>

      <form method="post" action="" class="category-item">
        <input type="hidden" name="category" value="stationery">
        <button type="submit" name="filter_button">
          <img src="icon-10.png" alt="Stationery" class="category-image">
          <h3 class="category-title">Stationery</h3>
        </button>
      </form>

      <form method="post" action="" class="category-item">
        <input type="hidden" name="category" value="electronics">
        <button type="submit" name="filter_button">
          <img src="icon-14.png" alt="Electronics" class="category-image">
          <h3 class="category-title">Electronics</h3>
        </button>
      </form>

      <form method="post" action="" class="category-item">
        <input type="hidden" name="category" value="clothes">
        <button type="submit" name="filter_button">
          <img src="icon-9.png" alt="Clothes" class="category-image">
          <h3 class="category-title">Clothes</h3>
        </button>
      </form>

      <form method="post" action="" class="category-item">
        <input type="hidden" name="category" value="shoes">
        <button type="submit" name="filter_button">
          <img src="icon-13.png" alt="Shoes" class="category-image">
          <h3 class="category-title">Shoes</h3>
        </button>
      </form>

      <form method="post" action="" class="category-item">
        <input type="hidden" name="category" value="health&beauty">
        <button type="submit" name="filter_button">
          <img src="icon-11.png" alt="Health&Beauty" class="category-image">
          <h3 class="category-title">Health & Beauty</h3>
        </button>
      </form>
    </div>
    <div style="margin-top: 50px;"></div>
  </section>

  <h1 style="text-align: center;">Latest Products</h1>
    <div class="container">
        <div class="products">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $productID = $row["productID"];
                    $productName = $row["productName"];
                    $productPrice = $row["productPrice"];
                    $productImage = $row["productImage"];
                    $productDescription = $row["productDescription"];
                    ?>
                    <div class="product">
                        <div class="card1">
                            <form method="post" action="">
                                <h3><?php echo $row['productName']; ?></h3>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents($row['productImage'])); ?>" alt="Product Image">
                                <p><?php echo $row['productDescription']; ?></p>
                                <p>Price: RM<?php echo $row['productPrice']; ?></p>
                                <?php if (in_array($productID, $reservedProductIDs)): ?>
                                    <div class="reserved-product">
                                        <p class="reserved-label"></p>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="productID" value="<?php echo $row['productID']; ?>">
                                <?php if (in_array($productID, $reservedProductIDs)): ?>
                                   <button type="button" disabled style="background-color: red; color: white;">Reserved</button>
<?php else: ?>
                                    <form method="POST" action="reserve.php">
                                        <input type="hidden" name="productID" value="<?php echo $productID; ?>">
                                        <button type="submit" name="reserve_product" onclick="return confirmReservation();">Reserve</button>
                                    </form>
                                    <form method="POST" action="">
                                        <input type="hidden" name="productID" value="<?php echo $productID; ?>">
                                        <button type="submit" name="add_to_cart">Add to Cart</button>
                                    </form>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </div>
    </div>

   



<script>

 // Function to show the confirmation popup
 function confirmReservation() {
        return confirm("Are you sure you want to reserve this product?");
    }
//Javascipt code cart items increment
  function updateCartCount() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var count = xhr.responseText;
        document.getElementById('cart-count').innerHTML = count;
      }
    };
    xhr.open('GET', 'get_cart_count.php', true);
    xhr.send();
  }

  // Call the updateCartCount function initially
  updateCartCount();

  // Add event listener to the Add to Cart button
  var addToCartButtons = document.querySelectorAll('button[name="add_to_cart"]');
  addToCartButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      var productID = this.previousElementSibling.value;
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          updateCartCount();
        }
      };
      xhr.open('POST', 'add_to_cart.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.send('productID=' + encodeURIComponent(productID));
    });
  });
</script>

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