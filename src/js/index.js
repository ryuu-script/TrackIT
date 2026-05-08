// ************** LOADING BAR ug ZOOM-IN para angas kaayog intro guys ********************
const progress = document.querySelector(".progress");
const loadingText = document.getElementById("loading-text");
const loader = document.getElementById("loader-container");
const introWrapper = document.getElementById("intro-wrapper");

let percent = 0;

const interval = setInterval(() => {

    percent++;

    progress.style.width = percent + "%";
    loadingText.textContent = percent + "%";

    if (percent >= 100) {

        clearInterval(interval);

        setTimeout(() => {

            loader.style.display = "none";
            introWrapper.style.display = "flex";

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    introWrapper.classList.add("zoom-in");
                });
            });

            setTimeout(() => {
                introWrapper.classList.add("zoom-out");

                setTimeout(() => {
                    window.location.href = "/TrackIT/src/php/LOGIN/enter_cred.php";
                }, 500); 

            }, 2000); // Delay for Sinoy Technologies intro

        }, 100); // Delay for progress bar
    }
    
}, 15);
// ****************************************************************************************