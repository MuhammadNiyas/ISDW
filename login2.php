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
        header("Location: index2.php?error=User Name is required");
        exit();
    } else if(empty($pass)) {
        header("Location: index2.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM sellers WHERE sellerUsername='$uname' AND sellerPassword='$pass'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['sellerUsername'] === $uname && $row['sellerPassword'] === $pass) {
                $_SESSION['sellerUsername'] = $row['sellerUsername'];
                $_SESSION['sellerID'] = $row['sellerID'];
                header("Location: home2.php");
                exit();
            } else {
                header("Location: index2.php?error=Incorrect username or password");
                exit();
            }
        } else {
            header("Location: index2.php?error=Incorrect username or password");
            exit();
        }
    }
} else {
    header("Location: home2.php");
    exit();
}