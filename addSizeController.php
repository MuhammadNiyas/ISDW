<?php
    include_once "../config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $size = $_POST['size'];
       
         $insert = mysqli_query($conn,"INSERT INTO sizes
         (size_name)   VALUES ('$size')");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../index10.php?size=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ../index10.php?size=success");
         }
     
    }
        
?>