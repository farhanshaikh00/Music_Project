<?php 
require("config.php");

 if (isset($_SESSION["loggedin"]) == true) {
        $login = true;
    } else {
        header("location:login.php");
    }


if ($_SERVER['REQUEST_METHOD']=="POST") {
    $cat = $_POST['category_name'];

    $sql = "INSERT INTO `category` (`category_name`, `post`) VALUES ('$cat', '0');";
    $res = mysqli_query($con,$sql);
    if ($res) {
        header("Location: category.php");
    }
}
include "header.php"; 
?>
<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Add Category</h6>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="category_name" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>





<?php
    require "footer.php";
?>