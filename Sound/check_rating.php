<?php
include "config.php";

$songId = $_GET['song'];
$username = $_GET['username'];

// Check if the user has already rated the song
$query = "SELECT * FROM ratings_reviews WHERE song_id = '$songId' AND username = '$username'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['hasRated' => true]);
} else {
    echo json_encode(['hasRated' => false]);
}
