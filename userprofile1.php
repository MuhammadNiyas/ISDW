<?php
session_start();

// Check if user is logged in
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

// Retrieve user details from the database
$buyerID = $_SESSION['buyerID'];
$query = "SELECT * FROM Buyers WHERE buyerID = $buyerID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $buyerName = mysqli_real_escape_string($conn, $_POST['buyerName']);
    $buyerEmail = mysqli_real_escape_string($conn, $_POST['buyerEmail']);
    $buyerPhoneNumber = mysqli_real_escape_string($conn, $_POST['buyerPhoneNumber']);
    $buyerAddress = mysqli_real_escape_string($conn, $_POST['buyerAddress']);

    // Validate user input
    function validateName($name) {
        // Check if name is not empty and contains only letters and spaces
        if (empty($name) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
            return false;
        }
        return true;
    }

    function validateEmail($email) {
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    function validatePhoneNumber($phoneNumber) {
        // Remove any non-digit characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the phone number has exactly 10 digits
        if (strlen($phoneNumber) !== 10) {
            return false;
        }
        return true;
    }

    function validateAddress($address) {
        // Check if address is not empty
        if (empty($address)) {
            return false;
        }
        return true;
    }

    if (!validateName($buyerName)) {
        $error_message = "Invalid name. Please enter a valid name.";
    } elseif (!validateEmail($buyerEmail)) {
        $error_message = "Invalid email. Please enter a valid email address.";
    } elseif (!validatePhoneNumber($buyerPhoneNumber)) {
        $error_message = "Invalid phone number. Please enter a 10-digit phone number.";
    } elseif (!validateAddress($buyerAddress)) {
        $error_message = "Invalid address. Please enter a valid address.";
    } else {
        // User input is valid, proceed with updating the user details

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
                    $updateQuery = "UPDATE Buyers SET buyerName = '$buyerName', buyerPhoneNumber = '$buyerPhoneNumber', buyerEmail = '$buyerEmail', buyerAddress = '$buyerAddress', profilePicture = '$new_filename' WHERE buyerID = $buyerID";
                    $updateResult = mysqli_query($conn, $updateQuery);

                    if ($updateResult) {
                        $_SESSION['success_message'] = "Profile updated successfully.";
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
            $updateQuery = "UPDATE Buyers SET buyerName = '$buyerName', buyerPhoneNumber = '$buyerPhoneNumber', buyerEmail = '$buyerEmail', buyerAddress = '$buyerAddress' WHERE buyerID = $buyerID";
            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                $_SESSION['success_message'] = "Profile updated successfully.";
                header("Location: home1.php");
                exit();
            } else {
                $error_message = "Profile update failed. Please try again.";
            }
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
    <title>User Profile</title>
    <style>
        /* user profile 1 */
        body {
            margin: 0;
            font-family: sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .topnav {
            overflow: hidden;
            background-color: #000;
        }

        .topnav a {
            float: right;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 15px;
            text-transform: uppercase
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
            background-color: #536bdd;
            color: white;
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
            margin-top: 10px;
            margin: 0 auto; /* Center the button horizontally */
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 80px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            justify-content: center;

        }

        button[type="submit"]:hover {
            background-color: #536bdd;
        }

        input[type="file"] {
            margin-bottom: 10px;
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
            margin-bottom: 30px;
        }

        .success-message {
            margin-top: 30px;
            text-align: center;
            color: green; /* Set the color to green */
            font-weight:bold;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            margin-bottom: 50px; /* Increase the bottom margin to create distance from the footer */
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

        .file-input {
            display: none;
        }

        .file-input-container {
            display: flex;
            align-items: flex-start; /* Align items to the top */
        }

        .choose-file-link {
            padding: 10px 40px;
            color: #000;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            margin-left: 125px;
            margin-top: -45px;
        }

        .selected-file {
            margin-top: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="topnav">
        <img src="logo2.png" alt="Logo" width="150px">
        <a href="view_transaction.php"><i class="fas fa-money-check-alt"></i></a>
        <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> </a>
        <a href="process_form.php"><i class="fas fa-paper-plane"></i> CONTACT US</a>
        <a href="userprofile1.php" class="active"><i class="fas fa-user"></i> USER PROFILE</a>
        <a href="reserve.php"><i class="far fa-calendar-alt"></i> RESERVE</a>
        <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
        <a href="home1.php"><i class="fas fa-home"></i> HOME</a>
    </div>
    <div class="container">
        <h1>Buyer Profile</h1>
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <div style="text-align: center;">
            <img src="profile_pictures/<?php echo $row['profilePicture']; ?>" class="profile-pic" alt="Profile Picture">
        </div>
        <form method="post" enctype="multipart/form-data">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success-message"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
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
                <div class="file-input-container">
                    <input type="file" id="profilePicture" name="profilePicture" class="file-input" style="display: none;">
                    <a href="#" onclick="document.getElementById('profilePicture').click()" class="choose-file-link">Upload</a>
                    <span class="selected-file"></span>
                </div>
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>

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
        </div>
    </footer>
</body>
</html>
