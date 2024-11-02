<?php
require("config.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location:login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $song_id = $_POST['song_id'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $artist = $_POST['artist'];
    $release_on = $_POST['release_on'];


    $thumbnail = $_FILES['thumbnail'];
    $thumbnailPath = "";
    
    if (!empty($thumbnail['name'])) {

        $target_dir = "uploads/";
        $thumbnailPath = $target_dir . basename($thumbnail["name"]);
        
        if (move_uploaded_file($thumbnail["tmp_name"], $thumbnailPath)) {
        } else {
            // Handle upload error 
        }
    } else {

        $existing_sql = "SELECT thumbnail FROM songs WHERE song_id = ?";
        $stmt = $con->prepare($existing_sql);
        $stmt->bind_param("i", $song_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing_song = $result->fetch_assoc();
        $thumbnailPath = $existing_song['thumbnail'];
    }

    $sql = "UPDATE songs SET title = ?, category = ?, artist = ?, release_on = ?, thumbnail = ? WHERE song_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siissi", $title, $category, $artist, $release_on, $thumbnailPath, $song_id);

    if ($stmt->execute()) {
        header("location:song.php?message=Song updated successfully");
    } else {
        echo "Error updating song: " . mysqli_error($con);
    }
} else {
    header("location:song.php");
}
?>
