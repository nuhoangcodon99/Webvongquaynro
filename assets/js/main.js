var toggleIcon = document.querySelector(".navbar-toggler-icon");
var petImages = document.querySelectorAll("#listPET .btn-choose-pet img");
var petActive = document.querySelector("#petActive .pet-active");
var currentIndex = 0;
var intervalTime = 1000;
toggleIcon.addEventListener("click", (event) => {
  var navbarHeader = document.querySelector("#navbarHeader");
  if (Array.from(navbarHeader.classList).includes("show")) {
    navbarHeader.classList.remove("show");
  }
  else {
    navbarHeader.classList.add("show");
  }
})
if (petActive) {
  function changeImage() {
    petActive.classList.remove("show-pet");

    petImages.forEach(function (image) {
      if (image.classList.contains("show-pet")) {
        image.classList.remove("show-pet");
      }
    });
    petActive.src = petImages[currentIndex].src.slice(0, -6) + ".webp";
    petImages[currentIndex].classList.add("show-pet");

    currentIndex++;
    if (currentIndex >= petImages.length) {
      currentIndex = 0;
    }
  }
  setInterval(changeImage, intervalTime);
}



