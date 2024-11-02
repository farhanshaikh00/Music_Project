<?php
// save_rating.php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$songId = $data['songId'];
$username = $data['username'];

// Check if the user has already rated the song
$query = "SELECT * FROM ratings_reviews WHERE song_id = '$songId' AND username = '$username'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    // User has already rated this song
    echo json_encode(['success' => false, 'message' => 'You have already rated this song.']);
} else {
    // Proceed to insert the rating
    $rating = $data['rating'];
    $comment = $data['comment'];
    $insertQuery = "INSERT INTO ratings_reviews (username, song_id, rating, review) VALUES ('$username', '$songId', '$rating', '$comment')";
    
    if (mysqli_query($con, $insertQuery)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving rating.']);
    }
}

?>
