<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['buyerID'])) {
    header("Location: login1.php");
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
$buyerID = $_SESSION['buyerID'];
$query = "SELECT * FROM Buyers WHERE buyerID = $buyerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyerName = mysqli_real_escape_string($conn, $_POST['buyerName']);
    $buyerPhoneNumber = mysqli_real_escape_string($conn, $_POST['buyerPhoneNumber']);
    $buyerAddress = mysqli_real_escape_string($conn, $_POST['buyerAddress']);
    $buyerEmail = mysqli_real_escape_string($conn, $_POST['buyerEmail']);

    $updateQuery = "UPDATE Buyers SET buyerName = '$buyerName', buyerPhoneNumber = '$buyerPhoneNumber', buyerAddress = '$buyerAddress', buyerEmail = '$buyerEmail' WHERE buyerID = $buyerID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $_SESSION['success_message'] = "Profile updated successfully.";
        header("Location: home1.php");
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
        <li><a href="home1.php"> Home</a></li>
       <li><a href="userprofile1.php"> User Profile</a></li> 
        <li><a href="cart1.php"> Cart</a></li>
 <li style="float:right"><a href="logout1.php">Logout</a></li>
      
    </ul>
</nav>

    <div class="container">
        <h1>User Profile</h1>
        <?php if(isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post">
            <div>
                <label for="buyerName">Name:</label>
                <input type="text" id="buyerName" name="buyerName" value="<?php echo $row['buyerName']; ?>" required>
            </div>
            <div>
                <label for="buyerEmail">Email:</label>
                <input type="email" id="buyerEmail" name="buyerEmail" value="<?php echo $row['buyerEmail']; ?>" required>
            </div>
            <div>
                <label for="buyerPhoneNumber">Phone Number:</label>
                <input type="text" id="buyerPhoneNumber" name="buyerPhoneNumber" value="<?php echo $row['buyerPhoneNumber']; ?>" required>
            </div>
            <div>
                <label for="buyerAddress">Address:</label>
                <textarea id="buyerAddress" name="buyerAddress" required><?php echo $row['buyerAddress']; ?></textarea>
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>
   
</body>
</html>
