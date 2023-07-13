
<?php
session_start();

// Connect to the database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check the database connection
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Retrieve products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUY AND SELL DISTED COLLEGE </title>
    <style>
        * {
    margin: 0;
    padding: 0;
    font-family: sans-serif;
  }
  
  .banner {
    width: 100%;
    height: 100vh;
    background-image: url(image21.jpeg);
    background-size: cover;
    background-position: center;
  }
  
  .navbar {
    width: 85%;
    margin: auto;
    padding: 35px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  
  .logo {
    width: 150px;
    cursor: pointer;
  }
  
  .navbar ul li {
    list-style: none;
    display: inline-block;
    margin: 0 20px;
    position: relative;
  }
  
  .navbar ul li a {
    text-decoration: none;
    color: #fff;
    text-transform: uppercase;
  }
  
  .navbar ul li::after {
    content: '';
    height: 3px;
    width: 0;
    background: #563bdd;
    position: absolute;
    left: 0;
    bottom: -10px;
    transition: 0.1s;
  }
  
  .navbar ul li:hover::after {
    width: 100%;
  }
  
  .content {
    width: 100%;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    text-align: center;
    color: #fff;
  }
  
  .content h1 {
    font-size: 70px;
    margin-top: 80px;
  }
  
  .content p {
    margin: 20px auto;
    font-weight: 100;
    line-height: 5px;
  }
  
  .button {
    width: 100px;
    display: inline-block;
    padding: 10px 20px;
    color: white;
    text-decoration: none;
    border: 3px solid #563bdd;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    background: transparent;
    margin: 20px 10px;
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s;
  }
  
  span {
    background: #563bdd;
    height: 100%;
    width: 0;
    border-radius: 25px;
    position: absolute;
    left: 0;
    bottom: 0;
    z-index: -1;
    transition: 0.5s;
  }
  
  button:hover span {
    width: 100%;
  }
  
  button:hover {
    border: none;
  }
  

  /* Styles for the "About Us" and "Team" sections */
  html {
    box-sizing: border-box;
  }
  
  *,
  *:before,
  *:after {
    box-sizing: inherit;
  }
  

  
  .about-section {
    margin-top: 50px; /* Increase the margin-top value to create more space */
    margin-bottom: 20px; /* Add margin-bottom for spacing consistency */
    padding: 50px;
    text-align: center;
    background-color: #000;
    color: white;
  }
  
  .container {
    padding: 0 16px;
  }
  
  .container::after,
  .row::after {
    content: "";
    clear: both;
    display: table;
  }

  .container p {
    margin-bottom: 25px; /* add some bottom margin to create separation between lines */
  }
  
  .title {
    color: grey;
  }
  
  .button1 {
    text-decoration: none;
    border: none;
    outline: 0;
    display: inline-block;
    padding: 8px;
    color: white;
    background-color: #000;
    text-align: center;
    cursor: pointer;
    width: 100%;
  }
  
  .button1:hover {
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
  .home-category .slide {
    text-align: center;
    text-decoration: none; /* Add text-decoration */
    color: #000;
    border: var(--border);
    border-radius: .5rem;
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
    background-color: transparent;
    display: inline-block;
    transition: background-color 0.3s ease;
    width: 250px;
    height: 300px;
    margin: 25px;
  }


  
  .category-item {
    background-color: transparent;
    border: none;
    color: black;
    cursor: pointer;
  }
  .home-category .slide img {
    height: 10rem;
    width: 10rem;
    object-fit: contain;
    margin-bottom: 1rem;
    margin-top: 1rem; /* Add margin top */
  }

  .home-category .slide h3 {
    color: #000;
    margin-top: 1rem; /* Add margin top */
  }

  .category-title {
    font-size: 20px;
    text-decoration: none;
  }
  
  
  .home-category .slide:hover {
    background-color: #536bdd;
  }
  
  .home-category .slide:hover img {
    filter: invert();
  }
  
  .home-category .slide:hover h3 {
    color: #fff;
  }



 /*product section */
          
          
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
    background-color: #536bdd;
    text-align: center;
    cursor: pointer;
    width: 100%;
    font-size: 18px;
  }
  
  .card1 button:hover {
    opacity: 0.7;

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

    <div class="banner">
        <div class="navbar">
            <img src="logo2.png" class="logo">
            <ul>
                <li style="float:right"><a href="signup1.php">Signup</a></li>
                <li style="float:right"><a href="index1.php">Login</a></li>
                <li style="float:right"><a href="index3.php">Admin</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>WELCOME TO OUR WEBSITE</h1>
            <p>Feel free to browse around and check out our services!</p>
        </div>
    </div>


<h1 style="text-align:center; margin-top: 20px; margin-bottom: 20px;"; >About Us</h1>
<div class="about-section">
<p>Some text about who we are and what we do.</p>
  <p>Resize the browser window to see that this page is responsive by the way.</p>
</div>


<h1 style="text-align:center">Category</h1>
<section class = "home-category">
    <div class = "swiper category-slider">
    <div class="swiper-wrapper">

   
        
<form method="post" action="index1.php" class ="swiper-slide slide">
    <input type="hidden" name="category" value="">
    <button type="submit" name="filter_button" class="category-item">
        <img src="icon-12.png" alt="All Categories" class="category-image">
        <h3 class="category-title">All Categories</h3>
    </button>
</form>

<form method="post" action="index1.php" class ="swiper-slide slide">
                <input type="hidden" name="category" value="stationery">
                <button type="submit" name="filter_button" class="category-item">
                    <img src="icon-10.png" alt="Stationery" class="category-image">
                    <h3 class="category-title">Stationery</h3>
                </button>
            </form>

            <form method="post" action="index1.php" class ="swiper-slide slide">
                <input type="hidden" name="category" value="electronics">
                <button type="submit" name="filter_button" class="category-item">
                    <img src="icon-14.png" alt="Electronics" class="category-image">
                    <h3 class="category-title">Electronics</h3>
                </button>
            </form>

            <form method="post" action="index1.php" class ="swiper-slide slide">
                <input type="hidden" name="category" value="clothes">
                <button type="submit" name="filter_button" class="category-item">
                    <img src="icon-9.png" alt="Clothes" class="category-image">
                    <h3 class="category-title">Clothes</h3>
                </button>
            </form>

            <form method="post" action="index1.php" class ="swiper-slide slide">
                <input type="hidden" name="category" value="shoes">
                <button type="submit" name="filter_button" class="category-item">
                    <img src="icon-13.png" alt="Shoes" class="category-image">
                    <h3 class="category-title">Shoes</h3>
                </button>
            </form>

            <form method="post" action="index1.php" class ="swiper-slide slide">
                <input type="hidden" name="category" value="health&beauty">
                <button type="submit" name="filter_button" class="category-item">
                    <img src="icon-11.png" alt="Health&Beauty" class="category-image">
                    <h3 class="category-title">H&B</h3>
                </button>
            </form>
         
</div>
</div>
    </div>
</section>



 <!-- Product section -->
 <div class="container">
        <h1 style="text-align:center">Latest Product</h1>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="product">
                <div class="card1">
                    <h3><?php echo $row['productName']; ?></h3>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents($row['productImage'])); ?>" alt="Product Image">
                    <p><?php echo $row['productDescription']; ?></p>
                    <p>Price: $<?php echo $row['productPrice']; ?></p>   
                    <form method="post" action="index1.php">
                        <input type="hidden" name="productID" value="<?php echo $row['productID']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                     <form method="POST" action="index1.php">
    <input type="hidden" name="productID" value="<?php echo $productID; ?>">
    <button type="submit" name="reserve_product" onclick="return confirmReservation();">Reserve</button>
  </form>
                </div>
            </div>
        <?php endwhile; ?>
        </div>




<script>
    
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
    </div>
      

</footer>




</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>