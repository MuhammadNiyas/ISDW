<?php
// Start session
session_start();

// Check if buyer is already logged in
if(isset($_SESSION['sellerID'])) {
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

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['sellerUsername']);
    $email = mysqli_real_escape_string($conn, $_POST['sellerEmail']);
    $password = mysqli_real_escape_string($conn, $_POST['sellerPassword']);

    
    // Check if username or email already exist in database
    $query = "SELECT * FROM sellers WHERE sellerUsername='$username' OR sellerEmail='$email'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        // Username or email already exist
        $error_message = "Username or email already exists.";
    } else {
        // Get the current date/time
        $signupDateTime = date('Y-m-d H:i:s');
        
        // Insert user into database
        $query = "INSERT INTO sellers (sellerUsername, sellerEmail, sellerPassword, signupDateTime) VALUES ('$username', '$email', '$password', '$signupDateTime')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // User registered successfully
            $_SESSION['success_message'] = "Registration successful. Please login.";
            header("Location: index2.php");
            exit();
        } else {
            // Registration failed
            $error_message = "Registration failed. Please try again.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>SELLER SIGNUP</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>
    <div class="container">
        <h1>SELLER SIGNUP</h1>
        <?php if(isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="sellerUsername" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="sellerEmail" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <div class="password-input">
                <input type="password" id="password" name="sellerPassword" required>
                <button type="button" id="password-toggle" onclick="showHidePassword()">Show</button>
            </div>
            <button type="submit">Sign Up</button>
        </form>

        </div>
        <div class="centered-link">
    Already a seller? <a href="index2.php">Log In</a><br>
    Signup as a buyer? <a href="signup1.php">Sign Up</a>

    </div>
       
    
    </div>
    
    <script src="main.js"></script>
</body>
</html>