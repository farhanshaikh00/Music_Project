<?php
require("config.php");

 if (isset($_SESSION["loggedin"]) == true) {
        $login = true;
    } else {
        header("location:login.php");
    }

    if (isset($_GET['deleteall']) && $_GET['deleteall'] == 'true') {
        $deletesql = "TRUNCATE TABLE `music`.`category`";
        $res = mysqli_query($con, $deletesql);
        if ($res) {
            header("Location: category.php");
            exit; // Stop further execution
        } else {
            // Optional: Handle error
            echo "Error deleting categories: " . mysqli_error($con);
        }
    }
    

require("header.php");

?>
<script>
function confirmDelete(categoryId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You Want to delete this Category...",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `delete-category.php?id=${categoryId}`;
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
                                    <h6 class="mb-4">All Categories</h6>
                                </div>                   
                           
                                <div class="col-md-2 my-2">
                                       <a href="add-category.php" class="btn btn-sm btn-outline-danger">Add Category</a> 
                                </div>
                                <div class="col-md-2 my-2">
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteAll()">Delete All</button>
                            </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Category Name</th>
                                            <th scope="col">No. of Song</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                         $limit = 10;
                                         if (isset($_GET['page'])) {
                                             $page = $_GET['page'];
                                         }else{
                                            $page = 1;
                                         }
                                         $offset = ($page - 1) * $limit;
                                    $sql = "SELECT * FROM `category` LIMIT {$offset} , {$limit}";
                                    $res = mysqli_query($con,$sql);
                                    if ($row = mysqli_num_rows($res)>0) {
                                        $srn = 1;
                                        while ($row = mysqli_fetch_assoc($res)) {
                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $srn ?></th>
                                            <td><?php echo $row['category_name'] ?></td>
                                            <td><?php echo $row['post'] ?></td>
                                            <td>
                                                <a href='update-category.php?id=<?php echo $row['category_id'] ?>' class="btn btn-sm btn-success">Edit</a>
                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $row['category_id']; ?>)">Delete</button>
                                        </td>
                                            
                                           
                                        </tr>
                                        <?php
                        $srn++;
                        }
                    }else {
                        echo "<tr ><td colspan='6' class='text-center mt-3'><h3 class='my-3'>No Category found</h3></td></tr>";
                    }
                     ?>
                                    </tbody>
                                </table>

                                <?php
                 $sql1 = "SELECT * FROM `category`";
                 $res1 = mysqli_query($con,$sql1);
                 $row1 = mysqli_num_rows($res1);
                if($row1 > 0){
                    $total_record = $row1;
                    $total_pages = ceil($total_record / $limit);
                echo "<ul class='pagination admin-pagination'>";
                if ($page > 1) {
                    echo '<li><a href="category.php?page='.($page - 1).'" >Prev</a></li>';
                }
                    for ($i=1; $i <= $total_pages ; $i++) { 
                        if ($i == $page) {
                            $active ="active";
                        }else{
                            $active ="";

                        }
                    echo '<li class = '.$active.'><a href="category.php?page='.$i.'" >'.$i.'</a></li>';
                }
                if ($page < $total_pages) {
                echo '<li><a href="category.php?page='.($page + 1).'" >Next</a></li>';
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
        text: "You want to delete all categories...",
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