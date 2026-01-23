      const video = document.getElementById("intro-video");
      const videoScreen = document.getElementById("video-screen");
      const mainContent = document.getElementById("main-content");

      video.addEventListener("ended", () => {
        videoScreen.style.display = "none";
        mainContent.classList.remove("hidden");
      });
    