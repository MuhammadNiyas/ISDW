<!DOCTYPE html>
<html>
<head>
	<title>ADMIN LOGIN</title>
	<link rel="stylesheet" type="text/css" href="style12.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
     <form action="login3.php" method="post">
     	<h2>ADMIN LOGIN</h2>
		 <?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
		 <i class="fas fa-user"></i>
     	<label>Admin ID</label>
     	<input type="text" name="adminID" placeholder="adminID"><br>

		 <i class="fas fa-lock"></i>
     	<label>Password</label>
     	<input type="password" name="adminPassword" placeholder="adminPassword"><br>

     	<button type="submit">Login</button>
     </form>
	 
	 <div class="centered-link">
	 Not an admin? Signup as a user.<a href="signup1.php">Sign Up</a>
    </div>
</body>
</html>