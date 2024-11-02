<?php
include "config.php";
include "header.php";

if (isset($_GET['search'])) {
    $search_term = mysqli_real_escape_string($con, $_GET['search']);
?>

    <!-- ##### Breadcumb Area Start ##### -->
    <section class="breadcumb-area bg-img bg-overlay" style="background-image: url(img/bg-img/breadcumb3.jpg);">
        <div class="bradcumbContent">
            <p>You Searched for</p>
            <h1><?php echo htmlspecialchars($search_term); ?></h1>
        </div>
    </section>
    <hr>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Album Catagory Area Start ##### -->
    <section class="album-catagory section-padding-100-0">
        <div class="container">
            <div class="row oneMusic-albums">

                <?php

                $sql_songs = "SELECT songs.*, artist.artist_id,artist_name FROM songs 
                              LEFT JOIN artist ON songs.artist = artist.artist_id 
                              WHERE title LIKE '%$search_term%' OR description LIKE '%$search_term%'";
                $res_songs = mysqli_query($con, $sql_songs);

                if (mysqli_num_rows($res_songs) > 0) {
                    while ($row = mysqli_fetch_assoc($res_songs)) {
                ?>
                <!-- Single Album for Song -->
                <div class="col-12 col-sm-4 col-md-3 col-lg-2 single-album-item t c p">
                    <div class="single-album">
                    <a href="player.php?song=<?php echo $row['song_id']; ?>"><img style="width:150px; height:150px;" src="../Admin/uploads/<?php echo $row['thumbnail']; ?>" alt=""></a>
                        <div class="album-info">
                            <a href="">
                                <h5><a href="player.php?song=<?php echo $row['song_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></h5>
                            </a>
                            <p><a href="player.php?song=<?php echo $row['song_id']; ?>"><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . (strlen($row['description']) > 50 ? '...' : ''); ?></a></p>
                            <p><a href="artist-profile.php?id=<?php echo $row['artist_id'] ?>"><?php echo htmlspecialchars($row['artist_name']); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }

                $sql_artists = "SELECT * FROM artist 
                                WHERE artist_name LIKE '%$search_term%'";
                $res_artists = mysqli_query($con, $sql_artists);

                if (mysqli_num_rows($res_artists) > 0) {
                    while ($row = mysqli_fetch_assoc($res_artists)) {
                ?>
                <div class="col-12 col-sm-4 col-md-3 col-lg-2 single-album-item t c p">
                    <div class="single-album">
                      <a href="artist-profile.php?id=<?php echo $row['artist_id'] ?>">  <img style="width:150px; height:150px;" src="../Admin/uploads/<?php echo $row['thumbnail'] ?? $row['image']; ?>" alt=""></a>
                        <div class="album-info">
                            <a href="artist-profile.php?id=<?php echo $row['artist_id'] ?>">
                                <h5><?php echo htmlspecialchars($row['artist_name']); ?></h5>
                            </a>
                            <p>Artist</p> 
                        </div>
                    </div>
                </div>
                <?php
                    }
                }

                // No records found
                if (mysqli_num_rows($res_songs) == 0 && mysqli_num_rows($res_artists) == 0) {
                    echo "<h2>No Record Found</h2>";
                }
                ?>
            </div>
        </div>
    </section>

<?php
} else {
    header("Location: index.php");
    exit();
}
?>

</body>
</html>
