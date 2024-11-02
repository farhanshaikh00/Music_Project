<?php

require("config.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location:login.php");
    exit;
}

if (isset($_GET['id'])) {
    $song_id = $_GET['id'];

    // Fetch the song details
    $sql = "SELECT thumbnail, music, category, artist FROM songs WHERE song_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $song_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $song = $result->fetch_assoc();
        
        // Prepare file paths
        $thumbnail_path = "uploads/" . $song['thumbnail'];
        $music_path = "uploads/music/" . $song['music'];

        // Delete the song from the database
        $sql = "DELETE FROM songs WHERE song_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $song_id);
        
        if ($stmt->execute()) {
            // Decrement post count for category and artist
            $sql = "UPDATE category SET post = post - 1 WHERE category_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $song['category']);
            $stmt->execute();

            $sql = "UPDATE artist SET post = post - 1 WHERE artist_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $song['artist']);
            $stmt->execute();

            // Delete files from the server
            if (file_exists($thumbnail_path)) {
                unlink($thumbnail_path);
            }
            if (file_exists($music_path)) {
                unlink($music_path);
            }

            header("Location: song.php?message=Song deleted successfully");
            exit;
        } else {
            echo "Database error: " . mysqli_error($con);
        }
    } else {
        header("Location: song.php?error=Song not found");
        exit;
    }
} else {
    header("Location: song.php?error=No song ID specified");
    exit;
}
?>
