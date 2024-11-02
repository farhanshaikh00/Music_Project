<?php
include "config.php";



include "header.php";
?>
    <!-- ##### Hero Area Start ##### -->
    <section class="hero-area">
        <div class="hero-slides owl-carousel">
            <!-- Single Hero Slide -->
            <div class="single-hero-slide d-flex align-items-center justify-content-center">
                <!-- Slide Img -->
                <div class="slide-img bg-img" style="background-image: url(img/bg-img/bg-1.jpg);"></div>
                <!-- Slide Content -->
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="hero-slides-content text-center">
                                <h6 data-animation="fadeInUp" data-delay="100ms">Latest album</h6>
                                <h2 data-animation="fadeInUp" data-delay="300ms">Beyond Time <span>Beyond Time</span></h2>
                                <a data-animation="fadeInUp" data-delay="500ms" href="#" class="btn oneMusic-btn mt-50">Discover <i class="fa fa-angle-double-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Hero Slide -->
            <div class="single-hero-slide d-flex align-items-center justify-content-center">
                <!-- Slide Img -->
                <div class="slide-img bg-img" style="background-image: url(img/bg-img/bg-2.jpg);"></div>
                <!-- Slide Content -->
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="hero-slides-content text-center">
                                <h6 data-animation="fadeInUp" data-delay="100ms">Latest album</h6>
                                <h2 data-animation="fadeInUp" data-delay="300ms">Colorlib Music <span>Colorlib Music</span></h2>
                                <a data-animation="fadeInUp" data-delay="500ms" href="#" class="btn oneMusic-btn mt-50">Discover <i class="fa fa-angle-double-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Hero Area End ##### -->

    <!-- ##### Latest Albums Area Start ##### -->
    <section class="latest-albums-area section-padding-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading style-2">
                        <p>See what’s new</p>
                        <h2>Artist you like</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <div class="ablums-text text-center mb-70">
                        <p>In fact, it is sad from even a large developer, as the gate of the players' borders. We live in football, football, football, and football. I don't think it's just that it's a little bit of a fringing thing or a lake. If you don't need a tablet, and you hate the course. Vivamus nibh velit, rutrum at ipsum ac, dignissim aculis ante. Until then, in the air, he did not fly the pulvinar, and he did not eat.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="albums-slideshow owl-carousel">
                    <?php
                        $sql_artists = "SELECT * FROM artist";
                        $result_artists = mysqli_query($con, $sql_artists);

                        if ($result_artists && mysqli_num_rows($result_artists) > 0) {
                            $sql_categories = "SELECT * FROM category";
                            $result_categories = mysqli_query($con, $sql_categories);
                            $categories = [];
                            if ($result_categories && mysqli_num_rows($result_categories) > 0) {
                                while ($category = mysqli_fetch_assoc($result_categories)) {
                                    $categories[$category['category_id']] = $category['category_name'];
                                }
                            }
                            
                            while ($rows = mysqli_fetch_assoc($result_artists)) {
                                ?>
                                <div class="single-album">
                                   <a href="artist-profile.php?id=<?php echo $rows['artist_id'] ?>"> <img src="../Admin/uploads/<?php echo $rows['image'] ?>" alt="" style="width:300px; height:220px;"></a>
                                    <div class="album-info">
                                        <a href="artist-profile.php?id=<?php echo $rows['artist_id'] ?>">
                                            <h5><?php echo htmlspecialchars($rows['artist_name']); ?></h5>
                                        </a>
                                        <p>
                                            <?php
                                            if (isset($rows['category_id'])) {
                                                echo isset($categories[$rows['category_id']]) ? htmlspecialchars($categories[$rows['category_id']]) : 'Unknown Category';
                                            } else {
                                                echo 'Category ID Not Available';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <?php    
                            }
                        }    
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Latest Albums Area End ##### -->

    <!-- ##### Buy Now Area Start ##### -->

    <?php
            if (isset($_SESSION['loggedin']) == true) {
                $login = true;
            } else {
                $login = false;
            }
            if ($login == true) {
            ?>
                <section class="oneMusic-buy-now-area has-fluid bg-gray section-padding-100">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-heading style-2">
                                    <p>See what’s new</p>
                                    <h2>All Songs You Like</h2>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <?php
                            $sql = "SELECT * FROM `songs`
                                    LEFT JOIN artist ON songs.artist = artist.artist_id";
                            $res = mysqli_query($con, $sql);
                            $result = mysqli_num_rows($res);
                            if ($result > 0) {
                                while ($rows = mysqli_fetch_assoc($res)) {
                            ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                        <div class="single-album-area wow fadeInUp" data-wow-delay="100ms">
                                            <div class="album-thumb" onclick="toggleAudio(this)" style="position: relative;">
                                                <img src="../Admin/uploads/<?php echo $rows['thumbnail']; ?>" alt="" style="width:200px; height:180px;">
                                                <span class="play-button">▶</span>
                                                <span class="pause-button" style="display: none;">❚❚</span> <!-- Pause button initially hidden -->
                                                <div class="play-icon" style="display:none;">
                                                    <audio src="../Admin/uploads/music/<?php echo $rows['music']; ?>"></audio>
                                                </div>
                                            </div>
                                            <div class="album-info">
                                                <a href="#">
                                                    <h5><a href="player.php?song=<?php echo $rows['song_id']; ?>"><?php echo $rows['title']; ?></a></h5>
                                                </a>
                                                <p>By: <?php echo $rows['artist_name']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<h2>Site is under maintenance... Please cooperate with us...</h2>";
                            }
                            ?>
                            </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="load-more-btn text-center wow fadeInUp" data-wow-delay="300ms">
                                    <a href="#" class="btn oneMusic-btn">Load More <i class="fa fa-angle-double-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
           
    <!-- ##### Buy Now Area End ##### -->

    <!-- ##### Featured Artist Area Start ##### -->
    <section class="featured-artist-area section-padding-100 bg-img bg-overlay bg-fixed" style="background-image: url(img/bg-img/bg-4.jpg);">
        <div class="container">

                <?php
                    $sql = "SELECT * FROM `songs` ORDER BY song_id DESC LIMIT 1";
                    $res = mysqli_query($con, $sql);
                    $result = mysqli_num_rows($res);
                    if ($result > 0) {
                        while ($rows = mysqli_fetch_assoc($res)) {
                    ?>
            <div class="row align-items-end">
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="featured-artist-thumb">
                        <img src="../Admin/uploads/<?php echo $rows['thumbnail'] ?>" alt="" style="width:600px; height:285px;">
                    </div>
                </div>
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="featured-artist-content">
                        <!-- Section Heading -->
                        <div class="section-heading white text-left mb-30">
                            <h2>Recently Added</h2>
                        </div>
                        <p><?php echo $rows['description'] ?></p>
                        <div class="song-play-area">
                            <div class="song-name">
                                <p><?php echo $rows['title'] ?></p>
                            </div>
                            <audio preload="auto" controls>
                                <source src="../Admin/uploads/music/<?php echo $rows['music'] ?>">
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
           <?php
                        }
                    }
                
           ?>
        </div>
    </section> 
    <!-- ##### Featured Artist Area End ##### -->

    <!-- ##### Miscellaneous Area Start ##### -->
    <section class="miscellaneous-area section-padding-100-0">
        <div class="container">
            <div class="row">
                <!-- ***** Weeks Top ***** -->
                <div class="col-12 col-lg-4">
                    <div class="weeks-top-area mb-100">
                        <div class="section-heading text-left mb-50 wow fadeInUp" data-wow-delay="50ms">
                            <p>Previous Year</p>
                            <h2>Before 2024</h2>
                        </div>

                        <?php
                        $sql = "SELECT * FROM `songs` 
                          LEFT JOIN artist ON songs.artist = artist.artist_id
                          WHERE `release_on` != 2024 LIMIT 3";
                            $res = mysqli_query($con, $sql);
                            $result = mysqli_num_rows($res);
                            if ($result > 0) {
                                while ($rows = mysqli_fetch_assoc($res)) {
                        ?>
                        <div class="single-top-item d-flex wow fadeInUp" data-wow-delay="100ms">
                            <div class="thumbnail">
                            <a href="player.php?song=<?php echo $rows['song_id']; ?>"><img src="../Admin/uploads/<?php echo $rows['thumbnail'] ?>" alt="" style="width:60px; height:60px" ></a>
                            
                            </div>
                            <div class="content-">
                            <h6><a href="player.php?song=<?php echo $rows['song_id']; ?>"><?php echo $rows['artist_name'] ?></a></h6>
                            <p><a href="player.php?song=<?php echo $rows['song_id']; ?>"><?php echo $rows['title'] ?></a></p>
                            </div>
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </div>
                </div>

                <!-- ***** New Hits Songs ***** -->
                <div class="col-12 col-lg-4">
                    <div class="new-hits-area mb-100">
                        <div class="section-heading text-left mb-50 wow fadeInUp" data-wow-delay="50ms">
                            <p>See what’s new</p>
                            <h2>HOT 2024</h2>
                        </div>
                        <?php
                        $sql = "SELECT * FROM `songs` 
                          LEFT JOIN artist ON songs.artist = artist.artist_id
                          WHERE `release_on` = 2024 LIMIT 3";
                            $res = mysqli_query($con, $sql);
                            $result = mysqli_num_rows($res);
                            if ($result > 0) {
                                while ($rows = mysqli_fetch_assoc($res)) {
                        ?>
                        <!-- Single Top Item -->
                        <div class="single-new-item d-flex align-items-center justify-content-between wow fadeInUp" data-wow-delay="100ms">
                            <div class="first-part d-flex align-items-center">
                                <div class="thumbnail">
                                    <img src="../Admin/uploads/<?php echo $rows['thumbnail'] ?>" alt="" style="width:60px; height:60px" >
                                </div>
                                <div class="content-">
                                    <h6><a href="player.php?song=<?php echo $rows['song_id']; ?>"><?php echo $rows['artist_name'] ?></a></h6>
                                    <p><a href="player.php?song=<?php echo $rows['song_id']; ?>"><?php echo $rows['title'] ?></a></p>
                                </div>
                            </div>
                            <audio preload="auto" controls>
                                <source src="../Admin/uploads/music/<?php echo $rows['music'] ?>">
                            </audio>
                        </div>
                        <?php
                                }
                            }
                        ?>

                       
                    </div>
                </div>

                <!-- ***** Popular Artists ***** -->
                <div class="col-12 col-lg-4">
                    <div class="popular-artists-area mb-100">
                        <div class="section-heading text-left mb-50 wow fadeInUp" data-wow-delay="50ms">
                            <p>See what’s new</p>
                            <h2>Popular Artist</h2>
                        </div>

                        <?php
                            $sql = "SELECT * FROM `artist` LIMIT 5";
                            $res = mysqli_query($con, $sql);
                            $result = mysqli_num_rows($res);
                            if ($result > 0) {
                                while ($rows = mysqli_fetch_assoc($res)) {
                        ?>
                        <div class="single-artists d-flex align-items-center wow fadeInUp" data-wow-delay="100ms">
                            <div class="thumbnail">
                                <img src="../Admin/uploads/<?php echo $rows['image'] ?>" alt="">
                            </div>
                            <div class="content-">
                                <p><?php echo $rows['artist_name'] ?></p>
                            </div>
                        </div>
                        <?php
                                }
                            }
                        ?>
                       

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Miscellaneous Area End ##### -->
    <?php
                } else{
                    echo '    <h2 class="text-center text-danger">Login to get Full Access and use more features</h2>';
                }
             ?>
    <!-- ##### Contact Area Start ##### -->
    <section class="contact-area section-padding-100 bg-img bg-overlay bg-fixed has-bg-img" style="background-image: url(img/bg-img/bg-2.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading white wow fadeInUp" data-wow-delay="100ms">
                        <p>See what’s new</p>
                        <h2>Get In Touch</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Contact Form Area -->
                    <div class="contact-form-area">
                        <form action="#" method="post">
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group wow fadeInUp" data-wow-delay="100ms">
                                        <input type="text" class="form-control" id="name" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group wow fadeInUp" data-wow-delay="200ms">
                                        <input type="email" class="form-control" id="email" placeholder="E-mail">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group wow fadeInUp" data-wow-delay="300ms">
                                        <input type="text" class="form-control" id="subject" placeholder="Subject">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group wow fadeInUp" data-wow-delay="400ms">
                                        <textarea name="message" class="form-control" id="message" cols="30" rows="10" placeholder="Message"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 text-center wow fadeInUp" data-wow-delay="500ms">
                                    <button class="btn oneMusic-btn mt-30" type="submit">Send <i class="fa fa-angle-double-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Contact Area End ##### -->

    <script>
           function toggleAudio(element) {
    const audio = element.querySelector('audio');
    const playButton = element.querySelector('.play-button');
    const pauseButton = element.querySelector('.pause-button');

    if (audio.paused) {
        // Pause all other audios
        const allAudios = document.querySelectorAll('audio');
        allAudios.forEach(a => {
            if (!a.paused) {
                a.pause();
                a.currentTime = 0; // Reset to the start
                // Hide all play/pause buttons
                const parent = a.closest('.album-thumb');
                if (parent) {
                    parent.querySelector('.play-button').style.display = 'inline';
                    parent.querySelector('.pause-button').style.display = 'none';
                }
            }
        });
        // Play the selected audio
        audio.play();
        playButton.style.display = 'none';
        pauseButton.style.display = 'inline';
    } else {
        // Pause the audio
        audio.pause();
        playButton.style.display = 'inline';
        pauseButton.style.display = 'none';
    }
}

    

   <?php
   require ('footer.php');
   
   ?>