<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Checkout Success</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
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
            color: white;
        }

        /*icon*/
        .topnav a i {
            margin-right: 5px;
            font-size: 20px;
            /* Adjust the font size as desired */
            display: inline-flex;
            align-items: center;
            height: 100%;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        /* Styles for the thank you message */
        .thank-you {
            text-align: center;
            font-size: 24px;
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #f5f5f5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: #000;
        }

        .thank-you::before {
            content: "\f4fe"; /* Unicode for the "fa-check-circle" icon */
            font-family: "Font Awesome 5 Free"; /* Make sure to include Font Awesome */
            display: block;
            font-size: 30px;
            margin-bottom: 10px;
        }

        .thank-you p {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="topnav">
        <img src="logo2.png" alt="Logo" width="150px">
        <a href="view_transaction.php"><i class="fas fa-money-check-alt"></i></a>
        <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> </a>
        <a href="process_form.php"><i class="fas fa-paper-plane"></i> CONTACT US</a>
        <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a>
        <a href="reserve.php"><i class="far fa-calendar-alt"></i> RESERVE</a>
        <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
        <a href="home1.php"><i class="fas fa-home"></i> HOME</a>
    </div>

    <div class="container">
 
        <div class="thank-you">
          <h1>Thank you for the order✅</h1>
               <p>Your order has been successfully placed.<br> We will process it shortly.</p>
        </div>
    </div>
</body>
</html>
