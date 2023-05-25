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


// Calculate total number of items in the cart
$totalItems = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $totalItems += $quantity;
    }
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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Buyer Product Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="style5.css">
</head>
<body>

<nav>
    <ul>
    <li class="logo"><a href="home1.php"><img src="logo.png" alt="Logo" width="125px"></a></li>
        <li><a href="home1.php"> Home</a></li>
      <li><a href="userprofile1.php"> User Profile</a></li> 
        <li><a href="cart1.php"> Cart <?php echo $totalItems; ?></a></li>
 <li style="float:right"><a href="logout1.php">Logout</a></li>
      
    </ul>
</nav>

<div class="slideshow-container">

<div class="mySlides fade">
  <div class="numbertext">1 / 3</div>
  <img src="image1.jpg" style="width:100% ">
  
</div>

<div class="mySlides fade">
  <div class="numbertext">2 / 3</div>
  <img src="image2.png" style="width:100%">

</div>

<div class="mySlides fade">
  <div class="numbertext">3 / 3</div>
  <img src="img_mountains_wide.jpg" style="width:100%">

</div>

<a class="prev" onclick="plusSlides(-1)">❮</a>
<a class="next" onclick="plusSlides(1)">❯</a>

</div>
<br>

<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span> 
</div>




<div class="container">
<h2 style="text-align:center">Latest Product</h2>

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
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}

var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});



</script>

</body>
</html>

<?php
mysqli_close($conn);
?>