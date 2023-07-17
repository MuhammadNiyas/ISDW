<?php 
session_start(); 
include "db_conn.php";

if (isset($_POST['adminID']) && isset($_POST['adminPassword'])) {

    function validate($data){
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
    }

    $adminID = validate($_POST['adminID']);
    $adminPassword = validate($_POST['adminPassword']);

    if (empty($adminID)) {
        header("Location: index3.php?error=AdminID is required");
        exit();
    } else if(empty($adminPassword)) {
        header("Location: index3.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM admin WHERE adminID='$adminID' AND adminPassword='$adminPassword'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['adminID'] === $adminID && $row['adminPassword'] === $adminPassword) {
                $_SESSION['adminID'] = $row['adminID'];
                header("Location: index10.php");
                exit();
            } else {
                header("Location: index3.php?error=Incorrect ID or password");
                exit();
            }
        } else {
            header("Location: index3.php?error=Incorrect ID or password");
            exit();
        }
    }
} else {
    header("Location: index10.php");
    exit();
}
