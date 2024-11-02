<?php

require("config.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location:login.php");
    exit;
}

if (isset($_GET['id'])) {
    $song_id = $_GET['id'];
    
    $sql = "SELECT * From `songs` WHERE songs.song_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $song_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $song = $result->fetch_assoc();
    } else {
        header("location:song.php");
        exit;
    }
} else {
    header("location:song.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $desc = mysqli_real_escape_string($con, $_POST['description']);
    $release_on = mysqli_real_escape_string($con, $_POST['release_on']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $artist = mysqli_real_escape_string($con, $_POST['artist']);
    
    // Store old category and artist
    $old_category = $song['category'];
    $old_artist = $song['artist'];
    
    // Default values for thumbnail and music
    $new_thumbnail_name = $song['thumbnail'];
    $music_file_name = $song['music'];

    // Handling Thumbnail Upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $file_name = $_FILES['thumbnail']['name'];
        $file_size = $_FILES['thumbnail']['size'];
        $file_type = $_FILES['thumbnail']['type'];
        $tmp_name = $_FILES['thumbnail']['tmp_name'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ["jpg", "jpeg", "png"];

        if (in_array($file_ext, $allowed_extensions) && $file_size <= 2097152) {
            $new_thumbnail_name = time() . "-" . basename($file_name);
            $thumbnail_target = "uploads/" . $new_thumbnail_name;
            move_uploaded_file($tmp_name, $thumbnail_target);
        }
    }

    // Handling Music Upload
    if (isset($_FILES['music']) && $_FILES['music']['error'] === 0) {
        $music_file_name = $_FILES['music']['name'];
        $music_tmp_name = $_FILES['music']['tmp_name'];
        $music_target = "uploads/music/" . basename($music_file_name);
        move_uploaded_file($music_tmp_name, $music_target);
    }

    // Update song details in the database
    $sql = "UPDATE songs SET title = ?, description = ?, release_on = ?, category = ?, artist = ?, thumbnail = ?, music = ? WHERE song_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssissssi", $title, $desc, $release_on, $category, $artist, $new_thumbnail_name, $music_file_name, $song_id);

    if ($stmt->execute()) {
        // Update category and artist post counts
        if ($category != $old_category) {
            // Decrement old category count
            $sql = "UPDATE category SET post = post - 1 WHERE category_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $old_category);
            $stmt->execute();

            // Increment new category count
            $sql = "UPDATE category SET post = post + 1 WHERE category_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $category);
            $stmt->execute();
        }

        if ($artist != $old_artist) {
            // Decrement old artist count
            $sql = "UPDATE artist SET post = post - 1 WHERE artist_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $old_artist);
            $stmt->execute();

            // Increment new artist count
            $sql = "UPDATE artist SET post = post + 1 WHERE artist_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $artist);
            $stmt->execute();
        }

        header("Location: song.php");
        exit;
    } else {
        echo "Database error: " . mysqli_error($con);
    }
}

include "header.php"; 
?>

<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Update Song</h6>
                <form onsubmit="confirmSaveChanges(event)" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Song Title</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($song['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" required><?php echo htmlspecialchars($song['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="release_on" class="form-label">Release On</label>
                        <select name="release_on" class="form-control" required>
                            <option value="" disabled>Select year</option>
                            <?php
                            $currentYear = date("Y");
                            // Ensure release_on is a valid date format
                            $releaseYear = $song['release_on'];

                            for ($year = 1990; $year <= $currentYear; $year++) {
                                $selected = ($year == $releaseYear) ? 'selected' : '';
                                echo "<option value='$year' $selected>$year</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            <option disabled>Select Category</option>
                            <?php
                            $sql = "SELECT * FROM `category`";
                            $res = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_assoc($res)) {
                                
                                $selected = ($row['category_id'] == $song['category']) ? 'selected' : '';
                                echo '<option value="' . $row['category_id'] . '" ' . $selected . '>' . $row['category_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <input type="hidden" name="old_category" value="<?php echo $song['category'] ?>">
                    </div>
                   
                    <div class="mb-3">
                        <label for="artist" class="form-label">Artist</label>
                        <select name="artist" class="form-control" required>
                            <option disabled>Select Artist</option>
                            <?php
                            $sql = "SELECT * FROM `artist`";
                            $res = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_assoc($res)) {
                                $selected = ($row['artist_id'] == $song['artist']) ? 'selected' : ''; // Change here
                                echo '<option value="' . $row['artist_id'] . '" ' . $selected . '>' . $row['artist_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <input type="hidden" name="old_artist" value="<?php echo $song['artist']; ?>"> <!-- Change here -->
                    </div>
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail (Leave blank to keep current)</label>
                        <input type="file" class="form-control" name="thumbnail" id="thumbnail" accept=".jpg,.jpeg,.png" onchange="previewImage(event)">
                    </div>
                    <div class="mb-3">
                        <label for="music" class="form-label">Upload Audio File (Leave blank to keep current)</label>
                        <input type="file" class="form-control" name="music" id="music" accept=".mp3, .m4a">
                    </div>
                    <div class="mb-3">
                        <img id="imagePreview" src="uploads/<?php echo htmlspecialchars($song['thumbnail']); ?>" alt="Image Preview" style="width: 300px; height: auto;">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="all-songs.php" class="btn btn-secondary">Cancel</a>
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
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}
</script>
<script>
function confirmSaveChanges(event) {
    event.preventDefault(); // Prevent the default form submission

    Swal.fire({
        title: "Do you want to save the changes?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Save",
        denyButtonText: `Don't save`
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire("Saved!", "", "success").then(() => {
                event.target.submit(); // Submit the form after confirmation
            });
        } else if (result.isDenied) {
            Swal.fire("Changes are not saved", "", "info");
        }
    });
}

</script>


<?php
require "footer.php";
?>
