<?php
require("config.php");

require("header.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location:login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM `admin` WHERE admin_id = '$id'";
$res = mysqli_query($con, $sql);

// Check if a user was found
if (mysqli_num_rows($res) == 0) {
    echo "User not found.";
    exit;
}

$row = mysqli_fetch_assoc($res);
?>

<div class="container-fluid">
    <div class="profile-background">
            <div class="profile-image">
                    <?php 
                    $profileImage = !empty($row['profile_image']) ? 'uploads/' . htmlspecialchars($row['profile_image']) : 'img/null-user.jpg';
                    echo '<img src="' . $profileImage . '" alt="Profile Picture">';
                    ?>
                    <div class="edit-icon" data-toggle="modal" data-target="#imageModal">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
            </div>

    </div>
    <div class="profile-content">
        <h1 id="profileName"><?php echo htmlspecialchars($row['username']); ?></h1>
        <p class="role" id="profileEmail">Email: <?php echo htmlspecialchars($row['email']); ?></p>

        <button class="btn btn-primary" data-toggle="modal" data-target="#infoModal" onclick="populateModal()">Edit Info</button>
    </div>
</div>

<!-- Modal for image selection -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Select New Profile Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="file" id="imageUpload" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="uploadButton">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for editing user info -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-light">
                <h5 class="modal-title text-light" id="infoModalLabel">Edit Profile Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-light">
                <div class="form-group">
                    <label for="editName">Name</label>
                    <input type="text" class="form-control text-light" id="editName" value="<?php echo htmlspecialchars($row['username']); ?>">
                </div>
                <div class="form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" class="form-control" id="editEmail" value="<?php echo htmlspecialchars($row['email']); ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveInfoButton">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    const uploadButton = document.getElementById("uploadButton");
    const imageUpload = document.getElementById("imageUpload");

    uploadButton.onclick = function() {
        const file = imageUpload.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('id', '<?php echo $id; ?>'); 
            fetch('upload_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response from server:', data);
                if (data.success) {
                    location.reload(); 
                } else {
                    alert(data.message); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            alert("Please select an image file.");
        }
    }

    document.getElementById("saveInfoButton").onclick = function() {
    const newName = document.getElementById("editName").value;
    const newEmail = document.getElementById("editEmail").value;

    const formData = new FormData();
    formData.append('id', '<?php echo $id; ?>'); 
    formData.append('username', newName);
    formData.append('email', newEmail);

    fetch('update_user_info.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


</script>

<?php
require "footer.php";
?>
