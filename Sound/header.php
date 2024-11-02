<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Musically - Vibe Music Sing Everywhere</title>
    <link rel="icon" href="img/core-img/favicon.ico"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">
     <style>
        #logo{
	position: absolute;
	top: 10px;
	left: 30px;
	font-size: 25px;
	color: #ffffff;
}#logo i{
	margin-right: 15px;
}
     </style>
</head>

<body>
    <?php
    include "config.php";
    ?>
    
    <!-- ##### Header Area Start ##### -->
    <header class="header-area">
        <div class="oneMusic-main-menu">
            <div class="classy-nav-container breakpoint-off">
                <div class="container">
                    <nav class="classy-navbar justify-content-between" id="oneMusicNav">
                        <a href="index.php" class="nav-brand"><p id="logo"><i class="fa fa-music"></i>Musically </p></a>
                        <div class="classy-navbar-toggler">
                            <span class="navbarToggler"><span></span><span></span><span></span></span>
                        </div>

                        <div class="classy-menu">
                            <div class="classycloseIcon">
                                <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                            </div>

                            <div class="classynav">
                                <ul>
                                    <li><a href="index.php">Home</a></li>
                                    <li><a href="">Upcoming</a></li>
                                    <li><a href="">Latest</a></li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>

                                <!-- Login/Register & Cart Button -->
                                <div class="login-register-cart-button d-flex align-items-center">
                                    <?php
                                    if (isset($_SESSION["loggedin"]) == true) {
                                        echo '<div class="login-register-btn mr-50">
                                        <a href="logout.php" id="loginBtn">Logout</a>
                                        ';
                                    } else {
                                        echo '<div class="login-register-btn mr-50">
                                        <a href="login.php" id="loginBtn">Login</a>
                                        <span class="m-2 text-light">/</span>
                                        <a href="register.php" id="loginBtn">Register</a>
                                    </div>';
                                    }
                                    ?>   
                                </div>

                                <?php
                                if (isset($_SESSION['username'])) {
                                    echo '<a class="text-light" style="font-size:16px; margin-right:24px;" href="">Welcome: ' . htmlspecialchars($_SESSION["username"]) . '</a>';
                                } else {
                                    echo '<a class="text-light" style="font-size:16px; margin-right:24px;" href="">Guest Mode</a>';
                                }
                                ?>
                            </div>
                            <!-- Nav End -->

                            <!-- Search Bar (visible only when logged in) -->
                            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                                <form action="search.php"  method="GET" class="form-inline my-2 my-lg-0 ml-auto">
                                    <input class="form-control mr-sm-2" name="search" type="text" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
<!-- <script src="js/player.js"></script> -->
</body>
</html>
