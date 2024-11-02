<?php
require("config.php");

$userId = $_SESSION['user_id'];
$artistId = $_GET['id'];

$deleteSql = "DELETE FROM user_follow WHERE user_id = $userId AND artist_id = $artistId";
mysqli_query($con, $deleteSql);

$updateSql = "UPDATE artist SET followers = followers - 1 WHERE artist_id = $artistId";
mysqli_query($con, $updateSql);

header("Location: artist-profile.php?id=" . $artistId);
exit;
?>
