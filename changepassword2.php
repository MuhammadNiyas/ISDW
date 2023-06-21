<?php
session_start();
// Check if the user is not logged in
if (!isset($_SESSION['sellerID'])) {
    header("Location: login2.php");
    exit();
}

// Database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_store_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerID = $_SESSION['sellerID'];
    $currentPassword = mysqli_real_escape_string($conn, $_POST['currentPassword']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Retrieve the current password from the database
    $query = "SELECT sellerPassword FROM sellers WHERE sellerID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $sellerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $storedPassword = $row['sellerPassword'];

        // Verify the current password
        if ($currentPassword === $storedPassword) {
            // Check if the new password and confirm password match
            if ($newPassword === $confirmPassword) {
                // Update the password in the database
                $updateQuery = "UPDATE sellers SET sellerPassword = ? WHERE sellerID = ?";
                $stmt = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($stmt, "ss", $newPassword, $sellerID);
                $updateResult = mysqli_stmt_execute($stmt);

                if ($updateResult) {
                    $_SESSION['success'] = "Password changed successfully.";
                    header("Location: changepassword2.php");
                    exit();
                } else {
                    $errorMessage = "Failed to update password. Please try again.";
                }
            } else {
                $errorMessage = "New password and confirm password do not match.";
            }
        } else {
            $errorMessage = "Invalid current password.";
        }
    } else {
        $errorMessage = "Failed to retrieve current password. Please try again.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            text-align: center;
        }

        .error {
            color: #ff0000;
            margin-bottom: 10px;
            text-align: center;
        }

        .success {
            color: #00cc00;
            margin-bottom: 10px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        p {
            text-align: center;
        }

        a {
            color: #4caf50;
        }
    </style>
    <script>
        function showHidePassword() {
            var passwordInput = document.getElementById("currentPassword");
            var passwordToggle = document.getElementById("password-toggle");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordToggle.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                passwordToggle.textContent = "Show";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>

        <?php if (isset($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?php echo $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="post" action="">
            <div>
                <label for="currentPassword">Current Password:</label>
                <input type="password" id="currentPassword" name="currentPassword" required>
                <button type="button" id="password-toggle" onclick="showHidePassword()">Show</button>
            </div>
            <div>
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>
            </div>
            <div>
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div>
                <button type="submit">Change Password</button>
            </div>
        </form>

        <p><a href="userprofile2.php">Back to Profile</a></p>
    </div>
</body>
</html>

