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
  
    <!-- Represent the social media icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-image: url("image21.jpeg");
    background-size: cover;
    background-position: center;
    color: #fff; /* Set text color to white */
   
  
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
            text-transform: uppercase;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
           background-color: #536bdd;
        }

        /icon/
        .topnav a i {
            margin-right: 5px;
            font-size: 20px;
            /* Adjust the font size as desired */
            display: inline-flex;
            align-items: center;
            height: 100%;
        }


        h2 {
    color: #fff; /* Set title color to white */
    text-align: center; /* Center align the title */
}

form {
    max-width: 400px;
      margin: 0 auto; /* Center align the form */
    text-align: center; /* Center align form elements */
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #fff; /* Set label color to white */
    text-align: left; /* Align labels to the left */
}

input[type="text"],
input[type="email"],
textarea {
    width: 100%;
    font-weight: bold;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: rgba(255, 255, 255, 0.8); /* Set input background color to semi-transparent white */
    color: #333; /* Set input text color to dark gray */
    text-align: left; /* Align input text to the left */
}

textarea {
    height: 120px;
    resize: vertical;
}


    input[type="submit"],
input[type="back"] {
    width: 100px; /* Remove fixed width */
    display: inline-block;
    padding: 10px 10px; /* Adjust padding as desired */
    color: white;
    text-decoration: none;
    border: 3px solid #563bdd;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    background: transparent;
    margin: 20px 10px;
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s;
}



input[type="submit"]:hover,
input[type="back"]:hover {
    background-color: #563bdd;
    width: 30%;
}

#confirmation {
    display: none;
    margin-top: 10px;
    padding: 10px;
    background-color: #f0f0f0;
    color: #333;
    text-align: center;
}

#social-icons {
    text-align: center;
    margin-top: 20px;
}

#social-icons a {
    display: inline-block;
    margin-right: 10px;
    color: #fff;
    font-size: 20px;
    text-decoration: none;
}

#social-icons a:hover {
    color: #563bdd;
}



        </style>
</head>
<body>

<div class="topnav">
    <img src="logo2.png" alt="Logo" width="150px">
    <a href="view_transaction.php"><i class="	fas fa-money-check-alt"></i></a>
    <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> </a>
    <a href="process_form.php"><i class="fas fa-paper-plane"></i> CONTACT US</a>
    <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a>
    <a href="reserve.php" class="active"><i class="far fa-calendar-alt"></i> RESERVE</a>
    <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
    <a href="home1.php"><i class="fas fa-home"></i> HOME</a>
</div>
</div>


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
