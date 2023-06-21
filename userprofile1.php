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
    $buyerEmail = mysqli_real_escape_string($conn, $_POST['buyerEmail']);
    $buyerAddress = mysqli_real_escape_string($conn, $_POST['buyerAddress']);

    // Handle profile picture upload
    if ($_FILES['profilePicture']['name']) {
        $file_name = $_FILES['profilePicture']['name'];
        $file_tmp = $_FILES['profilePicture']['tmp_name'];
        $file_type = $_FILES['profilePicture']['type'];
        $file_size = $_FILES['profilePicture']['size'];
        $file_error = $_FILES['profilePicture']['error'];

        // Check if uploaded file is an image
        $allowed_extensions = array("jpg", "jpeg", "png");
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            $error_message = "Invalid file type. Only JPG, JPEG, and PNG images are allowed.";
        } elseif ($file_error !== 0) {
            $error_message = "Error uploading file. Please try again.";
        } else {
            // Generate a unique filename and move the uploaded file to the destination directory
            $new_filename = uniqid() . '.' . $file_extension;
            $destination = "profile_pictures/" . $new_filename;
            if (move_uploaded_file($file_tmp, $destination)) {
                // Delete the previous profile picture if it exists
                if (!empty($row['profilePicture'])) {
                    unlink("profile_pictures/" . $row['profilePicture']);
                }

                // Update the database with the new profile picture filename
                $updateQuery = "UPDATE buyers SET buyerName = '$buyerName', buyerPhoneNumber = '$buyerPhoneNumber', buyerEmail = '$buyerEmail', buyerAddress = '$buyerAddress', profilePicture = '$new_filename' WHERE buyerID = $buyerID";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    $_SESSION['success_message'] = "Profile updated successfully.";
                    echo "<script>alert('Profile updated successfully.');</script>";
                    header("Location: home1.php");
                    exit();
                } else {
                    $error_message = "Profile update failed. Please try again.";
                }
            } else {
                $error_message = "Error moving uploaded file. Please try again.";
            }
        }
    } else {
        // Update user details without changing the profile picture
        $updateQuery = "UPDATE buyers SET buyerName = '$buyerName', buyerPhoneNumber = '$buyerPhoneNumber', buyerEmail = '$buyerEmail', buyerAddress = '$buyerAddress' WHERE buyerID = $buyerID";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            $_SESSION['success_message'] = "Profile updated successfully.";
            echo "<script>alert('Profile updated successfully.');</script>";
            header("Location: home1.php");
            exit();
        } else {
            $error_message = "Profile update failed. Please try again.";
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style1.css">
    <title>User Profile</title>
</head>
<body>
    <div class="topnav">
        <img src="logo2.png" alt="Logo" width="150px">
        <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
        <a href="userprofile1.php" class="active"><i class="fas fa-user"></i> USER PROFILE</a>
        <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
        <a href="home1.php"><i class="fas fa-home"></i> HOME</a>
    </div>

    <div class="container">
    <h1>buyer Profile</h1>
    <?php if(isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <div style="text-align: center;">
        <img src="profile_pictures/<?php echo $row['profilePicture']; ?>" class="profile-pic" alt="Profile Picture">
    </div>
    <form method="post" enctype="multipart/form-data">
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
            <input type="text" id="buyerAddress" name="buyerAddress" value="<?php echo $row['buyerAddress']; ?>" required>
        </div>
       
        <div>
            <label for="profilePicture">Profile Picture:</label>
            <input type="file" id="profilePicture" name="profilePicture">
        </div>
        <button type="submit">Update Profile</button>
    </form>
    <p><a href="changepassword2.php">Change Password</a></p>
    </div>
    
</body>
</html>