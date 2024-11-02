<?php
require("config.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $file = $_FILES['image'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Validate image file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.']);
        exit;
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Error uploading the file.']);
        exit;
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        // Update the database with the new image filename
        $sql = "UPDATE `admin` SET profile_image = '" . mysqli_real_escape_string($con, basename($file["name"])) . "' WHERE admin_id = '$id'";
        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'imageUrl' => $targetFile]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File could not be moved.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
