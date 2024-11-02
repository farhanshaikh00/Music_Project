<?php
require "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, get the image path from the database
    $image_sql = "SELECT image FROM `artist` WHERE artist_id = '$id'";
    $image_res = mysqli_query($con, $image_sql);

    if ($image_res && mysqli_num_rows($image_res) > 0) {
        $row = mysqli_fetch_assoc($image_res);
        $image_path = $row['image'];

        // Delete the record from the database
        $del_sql = "DELETE FROM `artist` WHERE artist_id = '$id'";
        $res = mysqli_query($con, $del_sql);

        if ($res) {
            // Delete the image file from the uploads directory
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            header('Location: artist.php');
            exit;
        }
    }
}
?>
