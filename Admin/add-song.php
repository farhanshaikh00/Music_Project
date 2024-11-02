<?php

require("config.php");

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location:login.php");
    exit;
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $artist = $_POST['artist'] ?? '';
    $release_on = $_POST['release_on'] ?? '';

    // Sanitize input
    $title = mysqli_real_escape_string($con, $title);
    $desc = mysqli_real_escape_string($con, $desc);
    $category = mysqli_real_escape_string($con, $category);
    $artist = mysqli_real_escape_string($con, $artist);
    $release_on = mysqli_real_escape_string($con, $release_on);

    // Handling Thumbnail Upload
    $thumbnailError = '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $file_name = $_FILES['thumbnail']['name'];
        $file_size = $_FILES['thumbnail']['size'];
        $tmp_name = $_FILES['thumbnail']['tmp_name'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ["jpg", "jpeg", "png"];

        if (!in_array($file_ext, $allowed_extensions)) {
            $thumbnailError = "Allow only JPG or PNG images";
        } elseif ($file_size > 20971520) { // 20 MB
            $thumbnailError = "File must be 20MB or lower in size";
        } else {
            $new_thumbnail_name = time() . "-" . basename($file_name);
            $thumbnail_target = "uploads/" . $new_thumbnail_name;
            if (!move_uploaded_file($tmp_name, $thumbnail_target)) {
                $thumbnailError = "Error uploading thumbnail.";
            }
        }
    } else {
        $thumbnailError = "No thumbnail uploaded or there was an upload error.";
    }

   // Handling Music Upload with Size Validation
    $music_file_name = $_FILES['music']['name'] ?? '';
    $music_tmp_name = $_FILES['music']['tmp_name'] ?? '';
    $music_file_size = $_FILES['music']['size'] ?? 0;

    if (!empty($music_file_name) && !empty($music_tmp_name)) {
        // Check file size (greater than 2MB allowed)
        if ($music_file_size > 20971520) { // 20 MB in bytes
            echo "Music file must be 20MB or lower in size.";
            return;
        }
        
        $music_target = "uploads/music/" . basename($music_file_name);
        if (!move_uploaded_file($music_tmp_name, $music_target)) {
            echo "Error uploading music file.";
            return;
        }
    } else {
        echo "No music file uploaded.";
        return;
    }

    // Check for errors before inserting into the database
    if (empty($thumbnailError)) {
        $sql = "INSERT INTO `songs` (`title`, `description`, `category`, `release_on`, `artist`, `thumbnail`, `music`) 
                VALUES ('$title', '$desc', '$category', '$release_on', '$artist', '$new_thumbnail_name', '$music_file_name');";
        $sql .= "UPDATE `category` SET post = post + 1 WHERE category_id = {$category};";
        $sql .= "UPDATE `artist` SET post = post + 1 WHERE artist_id = {$artist};";

        if (mysqli_multi_query($con, $sql)) {
            header("Location: song.php");
            exit;
        } else {
            echo "Database error: " . mysqli_error($con);
        }
    } else {
        echo $thumbnailError;
    }
}

include "header.php"; 
?>

<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Add Song</h6>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Song Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="release_on" class="form-label">Release On</label>
                        <select name="release_on" class="form-control" required>
                            <option value="" disabled selected>Select year</option>
                            <?php
                            $currentYear = date("Y");
                            for ($year = 1990; $year <= $currentYear; $year++) {
                                echo "<option value='$year'>$year</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            <option disabled selected>Select Category</option>
                            <?php
                            $sql = "SELECT * FROM `category`";
                            $res = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="artist" class="form-label">Artist</label>
                        <select name="artist" class="form-control" required>
                            <option disabled selected>Select Artist</option>
                            <?php
                            $sql = "SELECT * FROM `artist`";
                            $res = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<option value="' . $row['artist_id'] . '">' . $row['artist_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail" id="thumbnail" accept=".jpg,.jpeg,.png" required onchange="previewImage(event)">
                    </div>
                    <div class="mb-3">
                        <label for="music" class="form-label">Upload Audio File</label>
                        <input type="file" class="form-control" accept=".mp3 , .m4a" name="music" id="music" required>
                    </div>
                    <div class="mb-3">
                        <img id="imagePreview" src="" alt="Image Preview" style="display: none; width: 300px; height: auto;">
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        imagePreview.src = e.target.result;
        imagePreview.style.display = 'block'; // Show the image
    }

    if (file) {
        reader.readAsDataURL(file); // Convert the file to base64 URL
    } else {
        imagePreview.src = ""; // Clear the image
        imagePreview.style.display = 'none'; // Hide the image
    }
}
</script>

<?php
require "footer.php";
?>
