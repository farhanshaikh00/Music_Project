<?php
require("config.php");

 if (isset($_SESSION["loggedin"]) == true) {
        $login = true;
    } else {
        header("location:login.php");
    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $del_sql = "DELETE FROM `user` WHERE user_id = '$id'";
        $res = mysqli_query($con,$del_sql);
        if ($res) {
            header('Location: users.php');
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
                                    <h6 class="mb-4">All Users</h6>
                                </div>                   
                           
                                <div class="col-md-2 my-2">
                              <?php
                                $sql = "SELECT COUNT(*) as total FROM `user`"; 
                                $result = mysqli_query($con, $sql);
                                $row = mysqli_fetch_assoc($result); 
        
                                $totaluser = $row['total']; 
                              ?>
                                      <p>Total Users: <?php echo  $totaluser?></p> 
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Email</th>
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
                                    $sql = "SELECT * FROM `user` LIMIT {$offset} , {$limit}";
                                    $res = mysqli_query($con,$sql);
                                    if ($row = mysqli_num_rows($res)>0) {
                                        $srn = 1;
                                        while ($row = mysqli_fetch_assoc($res)) {
                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $srn ?></th>
                                            <td><?php echo $row['first_name'] ?> <?php echo $row['last_name'] ?></td>
                                            <td><?php echo $row['username'] ?></td>
                                            <td><?php echo $row['email'] ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $row['user_id']; ?>)">Delete</button>
                                            </td>

                                            
                                           
                                        </tr>
                                        <?php
                        $srn++;
                        }
                    }else {
                        echo "<tr ><td colspan='6' class='text-center mt-3'><h3 class='my-3'>No User found</h3></td></tr>";
                    }
                     ?>
                                    </tbody>
                                </table>
  
                                <?php
                 $sql1 = "SELECT * FROM `user`";
                 $res1 = mysqli_query($con,$sql1);
                 $row1 = mysqli_num_rows($res1);
                if($row1 > 0){
                    $total_record = $row1;
                    $total_pages = ceil($total_record / $limit);
                echo "<ul class='pagination admin-pagination'>";
                if ($page > 1) {
                    echo '<li><a href="users.php?page='.($page - 1).'" >Prev</a></li>';
                }
                    for ($i=1; $i <= $total_pages ; $i++) { 
                        if ($i == $page) {
                            $active ="active";
                        }else{
                            $active ="";

                        }
                    echo '<li class = '.$active.'><a href="users.php?page='.$i.'" >'.$i.'</a></li>';
                }
                if ($page < $total_pages) {
                echo '<li><a href="users.php?page='.($page + 1).'" >Next</a></li>';
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
function confirmDelete(userId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You Want to delete this User...",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `?id=${userId}`;
        }
    });
}
</script>

<?php
    require "footer.php";
?>