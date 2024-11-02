 <!-- ##### Footer Area Start ##### -->
 <footer class="footer-area">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-12 col-md-6">
                    <!-- <a href="#"><img src="img/core-img/logo.png" alt=""></a> -->
                    <p class="copywrite-text"><a href="#"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This Project is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="" target="_blank">Farhan Shaikh.</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                </div>

                <!-- <div class="col-12 col-md-6">
                    <div class="footer-nav">
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Albums</a></li>
                            <li><a href="#">Events</a></li>
                            <li><a href="#">News</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
    </footer>
    <!-- ##### Footer Area Start ##### -->

    <!-- ##### All Javascript Script ##### -->
    <!-- jQuery-2.2.4 js -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="js/plugins/plugins.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>
    <script>
function toggleAudio(element) {
    const audio = element.querySelector('audio');
    const playButton = element.querySelector('.play-button');
    const pauseButton = element.querySelector('.pause-button');

    if (audio.paused) {
        // Pause all other audios
        const allAudios = document.querySelectorAll('audio');
        allAudios.forEach(a => {
            a.pause();
            a.currentTime = 0; // Reset to the start
            // Hide all play/pause buttons
            const parent = a.closest('.album-thumb');
            if (parent) {
                parent.querySelector('.play-button').style.display = 'inline';
                parent.querySelector('.pause-button').style.display = 'none';
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
</script>

<script>
    document.getElementById('search-toggle').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        var searchForm = document.getElementById('search-form');
        searchForm.classList.toggle('active'); // Toggle the active class
    });
</script>

  
</body>

</html>