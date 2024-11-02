<?php 

require "config.php"; 
$fail = false;
$successfull = false;
$email_error = false;
// if ($_SESSION['loggedin'] == true) {
//     $login = true;
// }else{
//     header("location:index.php");
// }
// if ($_SESSION['role'] == 0) {
//     header("location:post.php");
// }

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    
    $email_sql = "SELECT `email` From `user` WHERE `email` = '$email';";
    $email_query = mysqli_query($con, $email_sql);
    if ($email_query ->num_rows > 0) {
        $email_error = true;
    }else{

    $sql = "INSERT INTO `user` (`first_name`, `last_name`, `username`, `email`, `password`) VALUES ('$name', '$lname', '$username' , '$email', '$password');";
    $result = mysqli_query($con,$sql);
    if (!$result) {
        $fail=true;

        }else{
            header("location: login.php?registersuccess");
        }
}
}
require "header.php"; 

?>

<!-- ##### Breadcumb Area Start ##### -->
    <section class="breadcumb-area bg-img bg-overlay" style="background-image: url(img/bg-img/breadcumb3.jpg);">
        <div class="bradcumbContent">
            <p>See what’s new</p>
            <h2>Register Here</h2>
        </div>
    </section>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Login Area Start ##### -->
    <section class="login-area section-padding-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="login-content">
                        <h3>Welcome</h3>
                        <!-- Login Form -->
                        <div class="login-form">
                            <?php
                                          if ($fail) {
                                            echo ('<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>Registered  Failed!</strong> Site have some technical issues... Try again later</a>
                                        </div>');
                                          }
                                          if ($successfull) {
                                            echo ('<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>Registered  Successfully!</strong> Now You Can Login
                                        </div>');
                                          }
                                          if ($email_error) {
                                            echo ('<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>Error!</strong> Email Already Exist.
                                        </div>');
                                          }
?>
                            <form action="#" method="post">
                                <div class="form-group">
                                    <label for="">First Name</label>
                                    <input type="text" class="form-control" name="fname" id="" aria-describedby="emailHelp" placeholder="Enter First Name" class="form-text text-muted">
                                </div>
                                
                                <div class="form-group">
                                    <label for="">Last Name</label>
                                    <input type="text" class="form-control" name="lname" id="" aria-describedby="emailHelp" placeholder="Enter Last Name" class="form-text text-muted">
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Username</label>
                                    <input type="text" class="form-control" name="username" id="exampleInputEmail1"  placeholder="Enter Username">
                                    <small id="emailHelp" class="form-text text-muted">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter E-mail">
                                    <small id="emailHelp" class="form-text text-muted"><i class="fa fa-lock mr-2"></i>We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <button type="submit" class="btn oneMusic-btn mt-30">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Login Area End ##### -->

      <?php
   require ('footer.php');
   ?>