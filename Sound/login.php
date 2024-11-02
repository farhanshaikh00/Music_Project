<?php 

require "config.php"; 
$register_success = isset($_GET['registersuccess']);
$error = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$password'";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $rows = mysqli_num_rows($res);
        if ($rows == 1) {
            while ($row = mysqli_fetch_assoc($res)) {
             
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            header("Location: index.php");
            }
        } else {
            $error = true;
        }
    }
}
require "header.php"; 
?>

    <!-- ##### Breadcumb Area Start ##### -->
    <section class="breadcumb-area bg-img bg-overlay" style="background-image: url(img/bg-img/breadcumb3.jpg);">
        <div class="bradcumbContent">
            <p>See what’s new</p>
            <h2>Login</h2>
        </div>
    </section>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Login Area Start ##### -->
    <section class="login-area section-padding-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="login-content">
                        <h3>Welcome Back</h3>
                        <!-- Login Form -->
                        <div class="login-form">
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                            <span style="color:red;">
                                <?php 
                                    if ($error == true) {
                                        echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                    <strong>Login Failed!</strong> Your email or Password is not Correct.
                                </div>';
                                    }
                                ?>
                                <?php if ($register_success): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> Registration was successful. You can now log in.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            </span>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter E-mail">
                                    <small id="emailHelp" class="form-text text-muted">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <button type="submit" class="btn oneMusic-btn mt-30">Login</button>
                               
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