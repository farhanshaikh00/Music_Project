<?php
include "config.php";

if ($_SESSION['loggedin'] == true) {
    $login = true;
}else{
    header("location:login.php");
}

if (isset($_GET['song'])) {
    $id = $_GET['song'];
} else {
    header("Location:index.php");
}

        $sql = "SELECT * FROM `songs`
        LEFT JOIN artist ON songs.artist = artist.artist_id
        WHERE song_id = '$id'";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);

// Fetch the next and previous song IDs
$nextSongQuery = "SELECT song_id FROM songs WHERE song_id > '$id' ORDER BY song_id ASC LIMIT 1";
$prevSongQuery = "SELECT song_id FROM songs WHERE song_id < '$id' ORDER BY song_id DESC LIMIT 1";

$nextSongRes = mysqli_query($con, $nextSongQuery);
$prevSongRes = mysqli_query($con, $prevSongQuery);

$nextSongId = mysqli_fetch_assoc($nextSongRes);
$prevSongId = mysqli_fetch_assoc($prevSongRes);

$nextSongId = $nextSongId ? $nextSongId['song_id'] : null; 
$prevSongId = $prevSongId ? $prevSongId['song_id'] : null; 


include "header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/player.css">
    <title>Musically</title>
</head>
<body>
<div class="main full" style="background-image: url('../Admin/uploads/<?php echo $row['image']; ?>');">
    <div class="overlay"></div>
    <div class="left">
        <img id="trackImage" src="../Admin/uploads/<?php echo $row['thumbnail']; ?>" alt="Track Image">
        <div class="volume">
            <p id="volume_show">70</p>
            <i class="fa fa-volume-up" aria-hidden="true" onclick="mute_sound()" id="volume_icon"></i>
            <input type="range" min="0" max="100" value="70" onchange="volume_change()" id="volume">  
        </div>
        
        <!-- Star Rating -->
       <!-- Star Rating -->
   
     <!-- Star Rating -->
     <div class="star-rating" id="starRatingContainer">
            <h2 class="text-center text-light">Rate this song</h2>
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <input type="text" id="ratingComment" placeholder="Leave a comment" style="display:none;">
        <button id="submitRating" type="button" style="display:none;">Submit</button>
        <div id="alreadyRatedMessage" style="display: none; color: red;">You have already rated this song.</div>


    </div>
    <div class="right">
        <div class="song_detail">
            <p id="title"><?php echo $row['title']; ?></p>
            <p id="artist" data-singer="1"><?php echo $row['artist_name']; ?></p>
        </div>
        <div class="middle">
            <button onclick="previous_song()" id="pre"><i class="fa fa-step-backward" aria-hidden="true"></i></button>
            <button onclick="togglePlay()" id="play"><i class="fa fa-play" aria-hidden="true"></i></button>
            <button onclick="next_song()" id="next"><i class="fa fa-step-forward" aria-hidden="true"></i></button>
        </div>
        <div class="duration" style="color:white;">
            <span id="current_time">0:00&nbsp;&nbsp;</span> / &nbsp;&nbsp; <span id="total_duration">&nbsp;&nbsp;0:00</span>
            <input type="range" min="0" max="100" value="0" id="duration_slider" onchange="change_duration()">
        </div>
    </div>
</div>

        
<audio id="audio" preload="auto" controls style="display:none;"></audio>
<script>
  // Select DOM elements
const audio = document.getElementById('audio');
const volumeSlider = document.getElementById('volume');
const volumeDisplay = document.getElementById('volume_show');
const volumeIcon = document.getElementById('volume_icon');
const currentTimeDisplay = document.getElementById('current_time');
const totalDurationDisplay = document.getElementById('total_duration');
const durationSlider = document.getElementById('duration_slider');
const stars = document.querySelectorAll('.star');
const ratingComment = document.getElementById('ratingComment');
const submitRating = document.getElementById('submitRating');

let isPlaying = false;
let isMuted = false;
let selectedRating = 0;

// Fetch song and navigation IDs from PHP
const songId = <?php echo json_encode($id); ?>;
const nextSongId = <?php echo json_encode($nextSongId); ?>;
const prevSongId = <?php echo json_encode($prevSongId); ?>;

// Set audio source
audio.src = `../Admin/uploads/music/<?php echo $row['music']; ?>`;

// Event listeners for star rating
stars.forEach(star => {
    star.addEventListener('click', function () {
        selectedRating = this.getAttribute('data-value');
        
        // Highlight selected stars
        stars.forEach(s => {
            s.classList.remove('selected');
            if (s.getAttribute('data-value') <= selectedRating) {
                s.classList.add('selected');
            }
        });
        
        // Show input field and submit button
        ratingComment.style.display = 'block';
        submitRating.style.display = 'block';
    });
});

submitRating.addEventListener('click', function (event) {
    event.preventDefault(); // Prevent default form submission
    const comment = ratingComment.value;
    const userId = <?php echo json_encode($_SESSION['user_id']); ?>;

    fetch('save_rating.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ songId, rating: selectedRating, comment, username: userId })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Log the response for debugging
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thank you!',
                text: 'Your rating has been submitted.',
            }).then(() => {
                // Clear input fields and reset stars
                ratingComment.value = '';
                stars.forEach(s => s.classList.remove('selected'));
                selectedRating = 0; // Reset selected rating
                ratingComment.style.display = 'none';
                submitRating.style.display = 'none';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'There was an error saving your rating.',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'There was an unexpected error.',
        });
    });
});

// Check if the user has already rated the song when the page loads
fetch('check_rating.php?song=' + songId + '&username=' + <?php echo json_encode($_SESSION['user_id']); ?>)
    .then(response => response.json())
    .then(data => {
        if (data.hasRated) {
            document.getElementById('alreadyRatedMessage').style.display = 'block';
            // Hide rating stars and input fields
            document.getElementById('starRatingContainer').style.display = 'none';
            ratingComment.style.display = 'none';
            submitRating.style.display = 'none';
        } else {
            document.getElementById('alreadyRatedMessage').style.display = 'none';
        }
    })
    .catch(error => console.error('Error:', error));


// Additional audio player functionalities
volumeSlider.addEventListener('input', function () {
    audio.volume = this.value / 100;
    volumeDisplay.textContent = this.value;
});

volumeIcon.addEventListener('click', function () {
    isMuted = !isMuted;
    audio.muted = isMuted;
    volumeIcon.classList.toggle('fa-volume-up', !isMuted);
    volumeIcon.classList.toggle('fa-volume-off', isMuted);
});

// Update current time and total duration
audio.addEventListener('loadedmetadata', function () {
    totalDurationDisplay.textContent = formatTime(audio.duration);
});

audio.addEventListener('timeupdate', function () {
    currentTimeDisplay.textContent = formatTime(audio.currentTime);
    durationSlider.value = (audio.currentTime / audio.duration) * 100;
});

// Format time function
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

// Duration slider change event
durationSlider.addEventListener('input', function () {
    audio.currentTime = (this.value / 100) * audio.duration;
});

// Play/Pause functionality
function togglePlay() {
    if (isPlaying) {
        audio.pause();
    } else {
        audio.play();
    }
    isPlaying = !isPlaying;
}

// Navigation functions for previous and next song
function previous_song() {
    if (prevSongId) {
        window.location.href = `song.php?song=${prevSongId}`;
    }
}

function next_song() {
    if (nextSongId) {
        window.location.href = `song.php?song=${nextSongId}`;
    }
}

</script>


<script src="js/player.js"></script>
</body>
</html>
