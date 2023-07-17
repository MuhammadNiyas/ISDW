<?php 
session_start(); 
include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	if (empty($uname)) {
		header("Location: index1.php?error=User Name is required");
	    exit();
	}else if(empty($pass)){
        header("Location: index1.php?error=Password is required");
	    exit();
	}else{
		$sql = "SELECT * FROM buyers WHERE buyerUsername='$uname' AND buyerPassword='$pass'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['buyerUsername'] === $uname && $row['buyerPassword'] === $pass) {
            	$_SESSION['buyerUsername'] = $row['buyerUsername'];
            	$_SESSION['buyerName'] = $row['buyerName'];
            	$_SESSION['buyerID'] = $row['buyerID'];
            	header("Location: home1.php");
		        exit();
            }else{
				header("Location: index1.php?error=Incorect User name or password");
		        exit();
			}
		}else{
			header("Location: index1.php?error=Incorect User name or password");
	        exit();
		}
	}
	
}else{
	header("Location: home1.php");
	exit();
}