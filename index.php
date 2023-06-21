
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
    <title>REDSTORE</title>
    <link rel="stylesheet" href="style25.css">
</head>
<body>

    <div class="banner">
        <div class="navbar">
            <img src="logo2.png" class="logo">
            <ul>
                <li style="float:right"><a href="signup1.php">Signup</a></li>
                <li style="float:right"><a href="index1.php">Login</a></li>
                <li style="float:right"><a href="signup3.php">Admin</a></li>
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

<h1 style="text-align:center; margin-top: 20px; margin-bottom: 20px;">Team</h1>
<div class="row">
  <div class="column">
    <div class="card">
      <img src="image3.jpg" alt="Jane" style="width:100%">
      <div class="container">
        <h2>Jane Doe</h2>
        <p class="title">CEO & Founder</p>
        <p>Some text that describes me lorem ipsum ipsum lorem.</p>
        <p>jane@example.com</p>
        <p> <a href="index1.php" class="button1">Contact</a></p>
      </div>
    </div>
  </div>

  <div class="column">
    <div class="card">
      <img src="image3.jpg" alt="Mike" style="width:100%">
      <div class="container">
        <h2>Mike Ross</h2>
        <p class="title">Art Director</p>
        <p>Some text that describes me lorem ipsum ipsum lorem.</p>
        <p>mike@example.com</p>
        <p> <a href="index1.php" class="button1">Contact</a></p>
      </div>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <img src="image3.jpg" alt="John" style="width:100%">
      <div class="container">
        <h2>John Doe</h2>
        <p class="title">Designer</p>
        <p>Some text that describes me lorem ipsum ipsum lorem.</p>
        <p>john@example.com</p>
        <p> <a href="index1.php" class="button1">Contact</a></p>
      </div>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <img src="image3.jpg" alt="Amelia" style="width:100%">
      <div class="container">
        <h2>Amelia Smith</h2>
        <p class="title">Marketing Manager</p>
        <p>Some text that describes me lorem ipsum ipsum lorem.</p>
        <p>amelia@example.com</p>
        <p> <a href="index1.php" class="button1">Contact</a></p>
      </div>
    </div>
  </div>
</div>

<!-- home category section starts -->
<h1 style="text-align:center">Category</h1>
<section class = "home-category">
    <div class = "swiper category-slider">
    <div class="swiper-wrapper">
       

    <a href ="index1.php" class ="swiper-slide slide">
    <img src = "icon-1.png" alt="">
    <h3>Laptop</h3>
    </a>

    <a href="index1.php" class="swiper-slide slide">
      <img src="icon-2.png" alt="">
      <h3>TV</h3>
   </a>

   <a href="index1.php" class="swiper-slide slide">
      <img src="icon-3.png" alt="">
      <h3>Camera</h3>
   </a>

   <a href="index1.php" class="swiper-slide slide">
      <img src="icon-4.png" alt="">
      <h3>Mouse</h3>
   </a>

   <a href="index1.php" class="swiper-slide slide">
      <img src="icon-5.png" alt="">
      <h3>Fridge</h3>
   </a>

   <a href="index1.php" class="swiper-slide slide">
      <img src="icon-6.png" alt="">
      <h3>Washing machine</h3>
   </a>

   <a href="index1.php" class="swiper-slide slide">
      <img src="icon-7.png" alt="">
      <h3>Smartphone</h3>
   </a>

   <a href="index1.php" class="swiper-slide slide">
      <img src="icon-8.png" alt="">
      <h3>Watch</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>


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
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    
<script src="main.js"></script>
<script>
    // JavaScript code for the slideshow
    var slides = document.querySelectorAll('.slide-images img');
    var currentSlide = 0;

    function showSlide(slideIndex) {
        for (var i = 0; i < slides.length; i++) {
            slides[i].classList.remove('active');
        }

        slides[slideIndex].classList.add('active');
    }

    function nextSlide() {
        currentSlide++;
        if (currentSlide >= slides.length) {
            currentSlide = 0;
        }
        showSlide(currentSlide);
    }

    setInterval(nextSlide, 3000); // Change slide every 3 seconds
</script>
</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>