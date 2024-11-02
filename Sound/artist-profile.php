<?php
require("config.php");

if (isset($_SESSION["loggedin"]) == true) {
    $login = true;
} else {
    header("location:login.php");
}


$id = $_GET['id'];
$userId = $_SESSION['user_id'] ?? null; 

require("header.php");

$sql = "SELECT * FROM `artist` WHERE artist.artist_id = $id";
$res = mysqli_query($con, $sql);
$artist = mysqli_fetch_assoc($res);

$followingSql = "SELECT * FROM user_follow WHERE user_id = $userId AND artist_id = $id";
$followingRes = mysqli_query($con, $followingSql);
$isFollowing = mysqli_num_rows($followingRes) > 0;
?>

<div class="profile-background">
    <div class="profile-image">
        <img src="../Admin/uploads/<?php echo htmlspecialchars($artist['image']); ?>" alt="Profile Picture">
        <div class="edit-icon" data-toggle="modal" data-target="#imageModal">
            <i class="fas fa-pencil-alt"></i>
        </div>
    </div>
</div>

<div class="profile-content">
    <h1><?php echo htmlspecialchars($artist['artist_name']); ?></h1>
    <p class="role"></p>

    <?php if ($isFollowing): ?>
        <a href="unfollow.php?id=<?php echo $artist['artist_id']; ?>" class="btn btn-danger">Unfollow</a>
    <?php else: ?>
        <a href="follow.php?id=<?php echo $artist['artist_id']; ?>" class="follow-btn">Follow</a>
    <?php endif; ?>


    <?php
    $sql = "SELECT COUNT(*) as total FROM `songs` WHERE `artist` = '$id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result); 

    $totalsongs = $row['total']; 
    ?>

    <div class="profile-stats">
        <div class="stat">
            <h3><?php echo  $totalsongs ?></h3>
            <p>Songs</p>
        </div>
        <div class="stat">
            <?php
                $sql1 = "SELECT * FROM `artist`
                LEFT JOIN category ON artist.category_id = category.category_id
                 WHERE artist_id = '$id'";
                $res = mysqli_query($con,$sql1);
                $rows = mysqli_fetch_assoc($res);
            ?>
        <h3><?php echo $rows['category_name']; ?></h3>
        <p>Category</p>
        </div>
        <div class="stat">
            <h3><?php echo $artist['followers'] ??'0' ; ?></h3>
            <p>Followers</p>
        </div>
    </div>
    <div class="favorite-songs">
        <h2>Artist Songs</h2>
        <div class="container">
            <div class="row oneMusic-albums">

                <?php
                // Search for songs
                $sql_songs = "SELECT songs.*, artist.artist_name FROM songs 
                              LEFT JOIN artist ON songs.artist = artist.artist_id 
                              WHERE artist = $id ";
                $res_songs = mysqli_query($con, $sql_songs);

                // Check if songs were found
                if (mysqli_num_rows($res_songs) > 0) {
                    while ($row = mysqli_fetch_assoc($res_songs)) {
                ?>
                <!-- Single Album for Song -->
                <div class="col-12 col-sm-4 col-md-3 col-lg-2 single-album-item t c p">
                    <div class="single-album">
                    <a href="player.php?song=<?php echo $row['song_id']; ?>"><img style="width:150px; height:150px;" src="../Admin/uploads/<?php echo $row['thumbnail']; ?>" alt=""></a>
                        <div class="album-info">
                            <a href="#">
                                <h5><a href="player.php?song=<?php echo $row['song_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></h5>
                            </a>
                            <p><a href="player.php?song=<?php echo $row['song_id']; ?>"><?php echo htmlspecialchars(substr($row['description'], 0, 30)) . (strlen($row['description']) > 50 ? '..' : ''); ?></a></p>
                            <p><a href="player.php?song=<?php echo $row['song_id']; ?>"><?php echo htmlspecialchars($row['artist_name']); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }else{
                  echo ' <div class="col-4 single-album-item">
                            <h2>No Songs Found</h2>
                         </div>';
                }

             
               ?>
            </div>
        </div>
       
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
require "footer.php";
?>
