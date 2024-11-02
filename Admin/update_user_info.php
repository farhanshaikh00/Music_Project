<?php
require("config.php");


// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Validate and sanitize input
if (isset($_POST['id']) && isset($_POST['username']) && isset($_POST['email'])) {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit;
    }

    // Check if the email already exists (excluding the current user)
    $emailCheckQuery = "SELECT * FROM `admin` WHERE email = '$email' AND admin_id != '$id'";
    $result = mysqli_query($con, $emailCheckQuery);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists.']);
        exit;
    }

    // Update user info in the database
    $sql = "UPDATE `admin` SET username = '$username', email = '$email' WHERE admin_id = '$id'";
    if (mysqli_query($con, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>
