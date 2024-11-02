<?php 
require("config.php");

if (isset($_SESSION["loggedin"]) == true) {
    $login = true;
} else {
    header("location:login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $artist_name = $_POST['artist_name'];
    $category = $_POST['cat_id'];

    // File upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; 
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Only save the image name (not the full path)
                $image_name = basename($_FILES["image"]["name"]);
                
                // Corrected SQL query
                $sql = "INSERT INTO `artist` (`artist_name`, `post`, `image`,`followers`, `category_id`) VALUES ('$artist_name', '0', '$image_name', '0', '$category')";
                $res = mysqli_query($con, $sql);
                if ($res) {
                    header("Location: artist.php");
                    exit;
                } else {
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        echo "No file uploaded or there was an upload error.";
    }
}

include "header.php"; 
?>

<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Add Artist</h6>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="artist_name" class="form-label">Artist Name</label>
                        <input type="text" class="form-control" name="artist_name" id="artist_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="cat_id" class="form-control" required>
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
                        <label for="image" class="form-label">Artist Image</label>
                        <input type="file" class="form-control" name="image" id="image" accept=".jpg,.jpeg,.png,.gif" required onchange="previewImage(event)">
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
