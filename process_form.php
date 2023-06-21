<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $message = $_POST["message"];

    // Connect to the database
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "online_store_db";
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Insert form data into the database
    $sql = "INSERT INTO submissions (first_name, last_name, email, phone, message) VALUES ('$first_name', '$last_name', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you for contacting us! Your submission has been saved.";
        header("Refresh: 3; URL=home1.php"); // Redirect after 3 seconds
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" type="text/css" href="style8.css">
    <!-- Represent the social media icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
</head>
<body>
    <h2>Contact Us</h2>
    <div class="form-container">
        <form action="process_form.php" method="POST">
        <label for="first_name">First Name:</label>
<input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required><br><br>

<label for="last_name">Last Name:</label>
<input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required><br><br>

<label for="email">Email:</label>
<input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>

<label for="phone">Phone Number:</label>
<input type="text" id="phone" name="phone" placeholder="Enter your phone number" required><br><br>

<label for="message">Message:</label>
<textarea id="message" name="message" placeholder="Enter your message" required></textarea><br><br>

            <input type="submit" value="Submit">
            <a href="home1.php"><input type="back" value="Back"></a>
        </form>
    </div>

    <div id="social-icons">
        <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.twitter.com"><i class="fab fa-twitter"></i></a>
        <a href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
        <a href="mailto:contact@example.com"><i class="fas fa-envelope"></i></a>
    </div>

    <div id="confirmation" style="display: none;">
        Thank you for contacting us! Your submission has been saved.
    </div>

    <script>
        function showConfirmation() {
            document.getElementById("confirmation").style.display = "block";
            setTimeout(function() {
                window.location.href = "home1.php";
            }, 3000); // Redirect after 3 seconds
        }
    </script>
    
</body>
</html>
