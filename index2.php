<!DOCTYPE html>
<html>
<head>
	<title>SELLER LOGIN</title>
	<link rel="stylesheet" type="text/css" href="style10.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
     <form action="login2.php" method="post">
     	<h2>SELLER LOGIN</h2>
		 
		 <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>
            <i class="fas fa-user"></i>
            User Name
        </label>
        <input type="text" name="uname" placeholder="User Name"><br>

        <label>
            <i class="fas fa-lock"></i>
            Password
        </label>
        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit">Login</button>
     </form>
	 <div class="centered-link">
        Not an existing seller? <a href="signup2.php">Sign Up</a>
    </div>
</body>
</html>


