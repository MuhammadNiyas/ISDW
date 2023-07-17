<?php
// Start session
session_start();

// Check if buyer is already logged in
if(isset($_SESSION['buyerID'])) {
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

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['buyerUsername']);
    $email = mysqli_real_escape_string($conn, $_POST['buyerEmail']);
    $password = mysqli_real_escape_string($conn, $_POST['buyerPassword']);

    
    // Check if username or email already exist in database
    $query = "SELECT * FROM buyers WHERE buyerUsername='$username' OR buyerEmail='$email'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        // Username or email already exist
        $error_message = "Username or email already exists.";
    } else {
        // Get the current date/time
        $signupDateTime = date('Y-m-d H:i:s');
        
        // Insert user into database
        $query = "INSERT INTO buyers (buyerUsername, buyerEmail, buyerPassword, signupDateTime) VALUES ('$username', '$email', '$password', '$signupDateTime')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // User registered successfully
            $_SESSION['success_message'] = "Registration successful. Please login.";
            header("Location: index1.php");
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
    <title>BUYER SIGNUP</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>
    <div class="container">
        <h1>BUYER SIGNUP</h1>
        <?php if(isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="buyerUsername" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="buyerEmail" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <div class="password-input">
                    <input type="password" id="password" name="buyerPassword" required>
                    <button type="button" id="password-toggle" onclick="showHidePassword()">Show</button>
                </div>
            </div>
            <button type="submit">Sign Up</button>
        </form>

        <div class="centered-link">
    Already a user? <a href="index1.php">Log In</a><br>
    Signup as a seller? <a href="signup2.php">Sign Up</a>
</div>
    </div>
    
    <script src="main.js"></script>
</body>
</html>

