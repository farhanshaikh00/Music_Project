<?php 
require("config.php");

 if (isset($_SESSION["loggedin"]) == true) {
        $login = true;
    } else {
        header("location:login.php");
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        if ($_SERVER['REQUEST_METHOD']=="POST") {
            $cat_id = $_POST['cat_id'];
            $cat_name = $_POST['category_name'];
    
            $update_sql = "UPDATE `category` SET `category_name` = '$cat_name' WHERE `category_id` = '$cat_id';";
            $update_res = mysqli_query($con,$update_sql);
            if ($update_res) {
                header("Location: category.php");
            }
        }
    
        include "header.php"; 
        $sql = "SELECT * FROM `category` WHERE `category_id` = '$id'";
        $res = mysqli_query($con,$sql);
        if ($rows = mysqli_num_rows($res)>0) {
          while ($row = mysqli_fetch_assoc($res)) {

?>
<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Update Category</h6>
                <form onsubmit="confirmSaveChanges(event)" method="post">
                <div class="form-group">
                          <input type="hidden" name="cat_id"  class="form-control" value="<?php echo $row['category_id'] ?>" placeholder="">
                      </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Category Name</label>
                        <input type="text" class="form-control" value="<?php echo $row['category_name'] ?>" name="category_name" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <?php
                    }
                }
            }
                ?>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    
function confirmSaveChanges(event) {
    event.preventDefault();

    Swal.fire({
        title: "Do you want to save the changes?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Save",
        denyButtonText: `Don't save`
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire("Saved!", "", "success").then(() => {
                event.target.submit(); 
            });
        } else if (result.isDenied) {
            Swal.fire("Changes are not saved", "", "info");
        }
    });
}


</script>


<?php
    require "footer.php";
?>