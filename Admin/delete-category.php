<?php
require "config.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $del_sql = "DELETE FROM `category` WHERE category_id = '$id'";
    $res = mysqli_query($con,$del_sql);
    if ($res) {
        header('Location: category.php');
    }
}
?>