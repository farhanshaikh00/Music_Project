<?php
require("config.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location:login.php");
    exit;
}


if (isset($_GET['deleteall']) && $_GET['deleteall'] == 'true') {
    $deletesql = "TRUNCATE TABLE `music`.`songs`";
    $res = mysqli_query($con, $deletesql);
    if ($res) {
        header("Location: song.php");
        exit; // Stop further execution
    } else {
        // Optional: Handle error
        echo "Error deleting song: " . mysqli_error($con);
    }
}


require("header.php");


?>
<script>
function confirmDelete(songId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You Want to delete this song...",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `delete-song.php?id=${songId}`;
        }
    });
}
</script>

<div class="container my-4">
    <div class="row">
        <div class="col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="row">               
                    <div class="col-md-10 my-2">
                         <h6 class="mb-4">All Songs</h6>
                    </div>
                    <div class="col-md-2 my-2">
                        <a href="add-song.php" class="btn btn-sm btn-outline-danger">Add Song</a>
                    </div>
                    <div class="col-md-2 my-2">
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteAll()">Delete All</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Song Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Artist</th>
                                <th scope="col">Release on</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit = 5;
                            if (isset($_GET['page'])) {
                                $page = $_GET['page'];
                            }else{
                               $page = 1;
                            }
                            $offset = ($page - 1) * $limit;
                            $sql = "SELECT songs.*, category.category_name , artist.artist_name FROM songs 
                                    LEFT JOIN category ON songs.category = category.category_id
                                    LEFT JOIN artist ON songs.artist = artist.artist_id
                                    ORDER BY song_id DESC
                                     LIMIT {$offset} , {$limit}";
                            $res = mysqli_query($con, $sql);

                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                            ?>
                                    <tr >
                                        <td>
                                            <img src="uploads/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="" style="width: 60px; height: 60px;">
                                        </td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['artist_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['release_on']); ?></td>
                                        <td>
                                            <a href='update-song.php?id=<?php echo $row['song_id']; ?>' class="btn btn-sm btn-success">Edit</a>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $row['song_id']; ?>)">Delete</button>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr ><td colspan='6' class='text-center mt-3'><h3 class='my-3'>No Song found</h3></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                 $sql1 = "SELECT * FROM `songs`";
                 $res1 = mysqli_query($con,$sql1);
                 $row1 = mysqli_num_rows($res1);
                if($row1 > 0){
                    $total_record = $row1;
                    $total_pages = ceil($total_record / $limit);
                echo "<ul class='pagination admin-pagination'>";
                if ($page > 1) {
                    echo '<li><a href="song.php?page='.($page - 1).'" >Prev</a></li>';
                }
                    for ($i=1; $i <= $total_pages ; $i++) { 
                        if ($i == $page) {
                            $active ="active";
                        }else{
                            $active ="";

                        }
                    echo '<li class = '.$active.'><a href="song.php?page='.$i.'" >'.$i.'</a></li>';
                }
                if ($page < $total_pages) {
                echo '<li><a href="song.php?page='.($page + 1).'" >Next</a></li>';
                }
                echo '</ul>';
                }

?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmDeleteAll() {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete all Songs...",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete all!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the delete all URL
            window.location.href = "?deleteall=true";
        }
    });
}
</script>
<?php
require "footer.php";
?>
