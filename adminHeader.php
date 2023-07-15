<?php
   session_start();
   include_once "./config/dbconnect.php";
?>

<!-- nav -->
<nav class="navbar navbar-expand-lg navbar-light px-5" style="background-color: #3B3131;">
    <div class="container">
        <a class="navbar-brand ml-5" href="./index10.php">
            <img src="logo2.png" alt="Logo" width="150px">
        </a>
        
        <ul class="navbar-nav ml-auto"></ul> <!-- Modified class to align to the right -->

        <div class="user-cart">
            <?php
            if(isset($_SESSION['user_id'])){
            ?>
            <a href="" style="text-decoration:none;">
                <i class="fa fa-user mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
            </a>
            <?php
            } else {
            ?>
            <a href="logout3.php">
                <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
            </a>
            <?php
            } ?>
        </div>
    </div>
</nav>

