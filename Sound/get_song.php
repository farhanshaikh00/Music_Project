<?php
include "config.php";

if (isset($_GET['song'])) {
    $id = $_GET['song'];
    $sql = "SELECT * FROM `songs`
            LEFT JOIN artist ON songs.artist = artist.artist_id
            WHERE song_id = '$id'";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);
    
    echo json_encode($row);
}
?>
