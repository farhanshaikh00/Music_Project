<?php 
require("config.php");

if (isset($_SESSION["loggedin"]) == true) {
    $login = true;
} else {
    header("location:login.php");
    exit;
}

// Check if the artist ID is provided
if (!isset($_GET['id'])) {
    header("Location: artist.php");
    exit;
}

$artist_id = $_GET['id'];

// Fetch the current artist data
$sql = "SELECT * FROM `artist` WHERE artist_id = '$artist_id'";
$result = mysqli_query($con, $sql);
$artist = mysqli_fetch_assoc($result);

if (!$artist) {
    echo "Artist not found!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $artist_name = $_POST['artist_name'];
    $cat_id = $_POST['cat_id']; // Retrieve category_id from the form
    $old_image = $artist['image']; 

    // File upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; 
        $image_name = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            $target_file = $target_dir . $image_name; // Save the file in uploads directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Delete old image if it exists
                if (file_exists($old_image)) {
                    unlink($old_image);
                }

                // Update artist information including category_id and image filename
                $sql = "UPDATE `artist` SET `artist_name` = '$artist_name', `image` = '$image_name', `category_id` = '$cat_id' WHERE artist_id = '$artist_id'";
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
        // If no new image is uploaded, update the name and category_id only
        $sql = "UPDATE `artist` SET `artist_name` = '$artist_name', `category_id` = '$cat_id' WHERE artist_id = '$artist_id'";
        $res = mysqli_query($con, $sql);
        if ($res) {
            header("Location: artist.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}

include "header.php"; 
?>

<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Update Artist</h6>
                <form onsubmit="confirmSaveChanges(event)" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="artist_name" class="form-label">Artist Name</label>
                        <input type="text" class="form-control" name="artist_name" id="artist_name" value="<?php echo htmlspecialchars($artist['artist_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="cat_id" class="form-control" required>
                            <option disabled>Select Category</option>
                            <?php
                            $sql = "SELECT * FROM `category`";
                            $res = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_assoc($res)) {
                                $selected = ($row['category_id'] == $artist['category_id']) ? 'selected' : '';
                                echo '<option value="' . $row['category_id'] . '" ' . $selected . '>' . $row['category_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Artist Image</label>
                        <input type="file" class="form-control" name="image" id="image" accept=".jpg,.jpeg,.png,.gif" onchange="previewImage(event)">
                    </div>
                    <div class="mb-3">
                        <img id="imagePreview" src="uploads/<?php echo htmlspecialchars($artist['image']); ?>" alt="Image Preview" style="display: block; width: 300px; height: auto;">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
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
    } else {
        imagePreview.src = ""; 
    }
}

function confirmSaveChanges(event) {
    event.preventDefault();

    Swal.fire({
        title: "Do you want to save the changes?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Save",
        denyButtonText: `Don't save`
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire("Saved!", "", "success").then(() => {
                event.target.submit(); 
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
