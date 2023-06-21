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

// Add product to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
  $productID = $_POST['productID'];

  // Check if the product is already in the cart
  if (!isset($_SESSION['cart'][$productID])) {
      // Add the product to the cart with a quantity of 1
      $_SESSION['cart'][$productID] = 1;

      // Update cart count in session
      $_SESSION['cartCount']++;
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
  <link rel="stylesheet" type="text/css" href="style10.css">
  <title>HomePage</title>
</head>
<body>


<div class="topnav">
<img src="logo2.png" alt="Logo" width="150px">
<a href="logout1.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
  <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a> 
  <a href="cart1.php"><i class="fas fa-shopping-cart"></i><?php echo $totalItems;?> CART</a>
  <a href="home1.php" class="active"><i class="fas fa-home"></i> HOME</a> 
</div>


<div class="header">
<h1>WELCOME TO OUR WEBSITE</h1>
<p>Feel free to browse around and check out our services!</p>
</div>




<h1 style="text-align:center">About Us</h1>
<div class="about-section">
<p>Some text about who we are and what we do.</p>
  <p>Resize the browser window to see that this page is responsive by the way.</p>
</div>

<h1 style="text-align:center">Team</h1>
<div class="row">
  <div class="column">
    <div class="card">
      <img src="image3.jpg" alt="Jane" style="width:100%">
      <div class="container">
        <h2>Jane Doe</h2>
        <p class="title">CEO & Founder</p>
        <p>Some text that describes me lorem ipsum ipsum lorem.</p>
        <p>jane@example.com</p>
        <p> <a href="process_form.php" class="button">Contact</a></p>
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
        <p> <a href="process_form.php" class="button">Contact</a></p>
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
        <p> <a href="process_form.php" class="button">Contact</a></p>
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
        <p> <a href="process_form.php" class="button">Contact</a></p>
      </div>
    </div>
  </div>
</div>

<!-- home category section starts -->
<h1 style="text-align:center">Category</h1>
<section class = "home-category">
    <div class = "swiper category-slider">
    <div class="swiper-wrapper">
       

    <a href ="category.php" class ="swiper-slide slide">
    <img src = "icon-1.png" alt="">
    <h3>Laptop</h3>
    </a>

    <a href="category.php?category=tv" class="swiper-slide slide">
      <img src="icon-2.png" alt="">
      <h3>TV</h3>
   </a>

   <a href="category.php?category=camera" class="swiper-slide slide">
      <img src="icon-3.png" alt="">
      <h3>Camera</h3>
   </a>

   <a href="category.php?category=mouse" class="swiper-slide slide">
      <img src="icon-4.png" alt="">
      <h3>Mouse</h3>
   </a>

   <a href="category.php?category=fridge" class="swiper-slide slide">
      <img src="icon-5.png" alt="">
      <h3>Fridge</h3>
   </a>

   <a href="category.php?category=washing" class="swiper-slide slide">
      <img src="icon-6.png" alt="">
      <h3>Washing machine</h3>
   </a>

   <a href="category.php?category=smartphone" class="swiper-slide slide">
      <img src="icon-7.png" alt="">
      <h3>Smartphone</h3>
   </a>

   <a href="category.php?category=watch" class="swiper-slide slide">
      <img src="icon-8.png" alt="">
      <h3>Watch</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

   <!--Product section-->
   <div class="container">
<h1 style="text-align:center">Latest Product</h1>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product">
        <div class="card1">
          
            <h3><?php echo $row['productName']; ?></h3>
            <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents($row['productImage'])); ?>" alt="Product Image">
            <p><?php echo $row['productDescription']; ?></p>
            <p>Price: $<?php echo $row['productPrice']; ?></p>   
              
        
       
    </p>
    <form method="post" action="home1.php">
                <input type="hidden" name="productID" value="<?php echo $row['productID']; ?>">
                <button type="submit" name="add_to_cart">Add to Cart</button>
                
           
            </form>
        </div>
      </div>
    <?php endwhile; ?>

</div>



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


</body>
</html>

