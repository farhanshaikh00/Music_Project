<?php
require("config.php");

$userId = $_SESSION['user_id'];
$artistId = $_GET['id'];

// Insert follow relationship
$insertSql = "INSERT INTO user_follow (user_id, artist_id) VALUES ($userId, $artistId)";
mysqli_query($con, $insertSql);

// Update artist's followers count
$updateSql = "UPDATE artist SET followers = followers + 1 WHERE artist_id = $artistId";
mysqli_query($con, $updateSql);

// Redirect back to artist profile
header("Location: artist-profile.php?id=" . $artistId);
exit;
?>
