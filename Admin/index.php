<?php
require("config.php");

if (isset($_SESSION["loggedin"]) == true) {
    $login = true;
} else {
    header("location:login.php");
}

require("header.php");

?>

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <!-- <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Today Sale</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Sale</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div> -->
                    <?php
                        $sql = "SELECT COUNT(*) as total FROM `songs`"; 
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result); 

                        $totalSongs = $row['total']; 

                        ?>
                        <div class="col-sm-3 col-xl-3"
                        >
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-music fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Songs</p>
                    <h6 class="mb-0"><?php echo htmlspecialchars($totalSongs); ?></h6>
                </div>
            </div>
        </div>  
                       
                    <?php
                        $sql = "SELECT COUNT(*) as total FROM `artist`"; 
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result); 

                        $totalartist = $row['total']; 

                        ?>
                        
                    <div class="col-sm-3 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <i class="fa fa-user fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">All Artist</p>
                                <h6 class="mb-0"><?php echo htmlspecialchars($totalartist); ?></h6>
                            </div>
                        </div>
                    </div>

                         <?php
                        $sql = "SELECT COUNT(*) as total FROM `category`"; 
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result); 

                        $totalcategories = $row['total']; 

                        ?>
                    <div class="col-sm-3 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <i class="fa fa-list fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">All Categories</p>
                                <h6 class="mb-0"><?php echo htmlspecialchars($totalcategories); ?></h6>
                            </div>
                        </div>
                    </div>
                         <?php
                        $sql = "SELECT COUNT(*) as total FROM `ratings_reviews`"; 
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result); 

                        $totalreviews = $row['total']; 

                        ?>
                    <div class="col-sm-3 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <i class="fa fa-star fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total User Reviews</p>
                                <h6 class="mb-0"><?php echo htmlspecialchars($totalreviews); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->


            <!-- Sales Chart Start -->
            <!-- <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-secondary text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Worldwide Sales</h6>
                                <a href="">Show All</a>
                            </div>
                            <canvas id="worldwide-sales"></canvas>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-secondary text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Salse & Revenue</h6>
                                <a href="">Show All</a>
                            </div>
                            <canvas id="salse-revenue"></canvas>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Sales Chart End -->


   
           <?php
           require("footer.php");
           ?>