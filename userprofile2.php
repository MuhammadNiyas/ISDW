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
                $updateQuery = "UPDATE Sellers SET sellerName = '$sellerName', sellerPhoneNumber = '$sellerPhoneNumber', sellerEmail = '$sellerEmail', profilePicture = '$new_filename' WHERE sellerID = $sellerID";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    $_SESSION['success_message'] = "Profile updated successfully.";
                    echo "<script>alert('Profile updated successfully.');</script>";
                    header("Location: home2.php");
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
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        body {
            background-image: url("images17.png");
            background-size: cover;
            background-repeat: no-repeat;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }


        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            width: 500px;
            border: 2px solid #ccc;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            font-size: 20px;
            padding: 10px;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
            opacity: .7;
        }

        .error {
            background: #F2DEDE;
            color: #A94442;
            padding: 10px;
            width: 95%;
            border-radius: 5px;
            margin: 20px auto;
        }

        .success {
            color: #008000;
            margin-bottom: 10px;
        }

        .popup {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 9999;
        }

        .popup.show {
            display: inline-block;
        }

        .popup-message {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .popup-close {
            text-align: center;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            max-width: 100%;
            max-height: 100%;
        }
        /* Navigation Styles */
nav {
  background-color: #333;
  color: #fff;
}

nav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
}

nav li {
  float: left;
}

nav a {
  display: block;
  color: #fff;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

nav a:hover {
  background-color: #111;
}

nav li:last-child {
  float: right;
}
    </style>
</head>
<body>
<nav>
    <ul>
        <li class="logo"><a href="home2.php"><img src="logo2.png" alt="Logo" width="125px"></a></li>
        <li><a href="home2.php"> Home</a></li>
        <li><a href="product2.php">Products</a></li>
        <li><a href="userprofile2.php">Seller Profile</a></li> 
        <li><a href="order_status2.php">Orders</a></li>
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
    <div style="text-align: center;">
        <img src="profile_pictures/<?php echo $row['profilePicture']; ?>" class="profile-pic" alt="Profile Picture">
    </div>
    <form method="post" enctype="multipart/form-data">
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
