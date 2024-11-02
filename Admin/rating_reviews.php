<?php
require("config.php");


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location:login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $del_sql = "DELETE FROM `ratings_reviews` WHERE review_id = '$id'";
    $res = mysqli_query($con, $del_sql);
    if ($res) {
        header('Location: rating_reviews.php');
        exit;
    }
}


if (isset($_GET['deleteall']) && $_GET['deleteall'] == 'true') {
    $deletesql = "TRUNCATE TABLE `music`.`ratings_reviews`";
    $res = mysqli_query($con, $deletesql);
    if ($res) {
        header("Location: rating_reviews.php");
        exit; // Stop further execution
    } else {
        // Optional: Handle error
        echo "Error deleting rating: " . mysqli_error($con);
    }
}


require("header.php");
?>

<div class="container my-4">
    <div class="row">
        <div class="col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="row">
                    <div class="col-md-10 my-2">
                        <h6 class="mb-4">All Ratings & Reviews</h6>
                    </div>
                    <div class="col-md-2 my-2">
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteAll()">Delete All</button>
                    </div>
                    <div class="col-md-2 my-2">
                        <?php
                        $sql = "SELECT COUNT(*) as total FROM `ratings_reviews`"; 
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result); 
                        $totalReviews = $row['total']; 
                        ?>
                        <p>Total Reviews: <?php echo $totalReviews; ?></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Username</th>
                                <th scope="col">Song</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Review</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit = 10;
                            if (isset($_GET['page'])) {
                                $page = $_GET['page'];
                            } else {
                                $page = 1;
                            }
                            $offset = ($page - 1) * $limit;
                            $sql = "SELECT * FROM `ratings_reviews`
                            LEFT JOIN user ON ratings_reviews.username = user.user_id
                            LEFT JOIN songs ON ratings_reviews.song_id = songs.song_id
                             LIMIT {$offset}, {$limit}";
                            $res = mysqli_query($con, $sql);
                            if (mysqli_num_rows($res) > 0) {
                                $srn = 1;
                                while ($row = mysqli_fetch_assoc($res)) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $srn; ?></th>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['rating']); ?> <i class="fa fa-star"></i></td>
                                <td><?php echo htmlspecialchars($row['review']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $row['review_id']; ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php
                                    $srn++;
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center mt-3'><h3 class='my-3'>No Reviews found</h3></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php
                    $sql1 = "SELECT * FROM `ratings_reviews`";
                    $res1 = mysqli_query($con, $sql1);
                    $row1 = mysqli_num_rows($res1);
                    if ($row1 > 0) {
                        $total_record = $row1;
                        $total_pages = ceil($total_record / $limit);
                        echo "<ul class='pagination admin-pagination'>";
                        if ($page > 1) {
                            echo '<li><a href="rating_reviews.php?page='.($page - 1).'">Prev</a></li>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) { 
                            $active = ($i == $page) ? "active" : "";
                            echo '<li class="'.$active.'"><a href="rating_reviews.php?page='.$i.'">'.$i.'</a></li>';
                        }
                        if ($page < $total_pages) {
                            echo '<li><a href="rating_reviews.php?page='.($page + 1).'">Next</a></li>';
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
function confirmDelete(reviewId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this review.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `?id=${reviewId}`;
        }
    });
}
</script>
<script>
    function confirmDeleteAll() {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete all Rating and Reviews...",
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
