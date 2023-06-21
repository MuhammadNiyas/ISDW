
<!DOCTYPE html>
 <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Checkout Successs</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

body {
    margin: 0;
    font-family:  sans-serif;
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

/*icon*/
  .topnav a i {
    margin-right: 5px;
  font-size: 20px; /* Adjust the font size as desired */
  display: inline-flex;
  align-items: center;
  height: 100%;
  }


.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

h1 {
  text-align: center;
}

.message {
  text-align: center;
  font-size: 18px;
}
     </style>
    </head>
    <body>

    <div class="topnav">
        <img src="logo2.png" alt="Logo" width="150px">
        <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
        <a href="userprofile1.php"><i class="fas fa-user"></i> USER PROFILE</a> 
        <a href="cart1.php"><i class="fas fa-shopping-cart"></i> CART</a>
        <a href="home1.php" ><i class="fas fa-home"></i> HOME</a> 
    </div>

    <div class="container">
    <h1>Payment Confirmation&#x2705;</h1>
    <p class="message">Your payment has been successful! Thank you for your purchase.</p>

  </div>
    </body>
</html>

