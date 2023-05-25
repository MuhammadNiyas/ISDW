<?php
session_start();

// Check if user is logged in
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

// Retrieve user details from database
$sellerID = $_SESSION['sellerID'];
$query = "SELECT * FROM Sellers WHERE sellerID = $sellerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerName = mysqli_real_escape_string($conn, $_POST['sellerName']);
    $sellerPhoneNumber = mysqli_real_escape_string($conn, $_POST['sellerPhoneNumber']);
    $sellerEmail = mysqli_real_escape_string($conn, $_POST['sellerEmail']);
    

    $updateQuery = "UPDATE Sellers SET sellerName = '$sellerName', sellerPhoneNumber = '$sellerPhoneNumber', sellerEmail = '$sellerEmail' WHERE sellerID = $sellerID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $_SESSION['success_message'] = "Profile updated successfully.";
        echo "<script>alert('Profile updated successfully.');</script>";
        header("Location: home2.php");
        exit();
    } else {
        $error_message = "Profile update failed. Please try again.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="script1.js" defer></script>
</head>
<body>
<nav>
    <ul>
    <li class="logo"><a href="home1.php"><img src="logo.png" alt="Logo" width="125px"></a></li>
        <li><a href="home2.php"> Home</a></li>
        <li><a href="product2.php">Products</a></li>
        <li><a href="userprofile2.php"></i> Seller Profile</a></li> 
      <li style="float:right"><a href="logout2.php">Logout</a></li>
      
    </ul>
</nav>
    <div class="container">
        <h1>Seller Profile</h1>
        <?php if(isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        <form method="post">
            <div>
                <label for="sellerName">Name:</label>
                <input type="text" id="sellerName" name="sellerName" value="<?php echo $row['sellerName']; ?>" required>
            </div>
            <div>
                <label for="sellerEmail">Email:</label>
                <input type="email" id="sellerEmail" name="sellerEmail" value="<?php echo $row['sellerEmail']; ?>" required>
            </div>
            <div>
                <label for="sellerPhoneNumber">Phone Number:</label>
                <input type="text" id="sellerPhoneNumber" name="sellerPhoneNumber" value="<?php echo $row['sellerPhoneNumber']; ?>" required>
        </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
