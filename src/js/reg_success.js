const REDIRECT_URL = "/TrackIT/src/php/LOGIN/enter_cred.php";
const COUNTDOWN_SEC = 5;
const SOUND_URL = "/TrackIT/src/audio/success.wav"; 

const audio = new Audio(SOUND_URL);
audio.play().catch(err => {
    console.warn("Audio playback failed:", err);
});

let seconds = COUNTDOWN_SEC;
const countdownEl = document.getElementById("countdown");

const interval = setInterval(() => {
    seconds--;
    countdownEl.textContent = seconds;

    if (seconds <= 0) {
        clearInterval(interval);
        window.location.href = REDIRECT_URL;
    }
}, 1000);