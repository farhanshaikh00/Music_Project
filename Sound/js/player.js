
function togglePlay() {
    if (isPlaying) {
        audio.pause();
        document.getElementById('play').innerHTML = '<i class="fa fa-play" aria-hidden="true"></i>';
    } else {
        audio.play();
        document.getElementById('play').innerHTML = '<i class="fa fa-pause" aria-hidden="true"></i>';
    }
    isPlaying = !isPlaying;
}

function volume_change() {
    const volumeValue = volumeSlider.value;
    audio.volume = volumeValue / 100; // Set audio volume
    volumeDisplay.textContent = volumeValue; // Update displayed volume
}

function change_duration() {
    audio.currentTime = audio.duration * (durationSlider.value / 100);
}

audio.addEventListener('timeupdate', function() {
    const position = audio.currentTime / audio.duration;
    durationSlider.value = position * 100;
    currentTimeDisplay.textContent = formatTime(audio.currentTime); // Update current time display
});

audio.addEventListener('loadedmetadata', function() {
    audio.volume = volumeSlider.value / 100; // Initialize volume
    totalDurationDisplay.textContent = formatTime(audio.duration); // Update total duration display
});

function mute_sound() {
    if (isMuted) {
        audio.muted = false;
        volumeIcon.classList.remove('fa-volume-off');
        volumeIcon.classList.add('fa-volume-up');
        isMuted = false;
    } else {
        audio.muted = true;
        volumeIcon.classList.remove('fa-volume-up');
        volumeIcon.classList.add('fa-volume-off');
        isMuted = true;
    }
}

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

function next_song() {
    if (nextSongId) {
        window.location.href = `player.php?song=${nextSongId}`;
    } else {
        window.location.href = 'index.php';
    }
}

function previous_song() {
    if (prevSongId) {
        window.location.href = `player.php?song=${prevSongId}`;
    } else {
        window.location.href = 'index.php';
    }
}