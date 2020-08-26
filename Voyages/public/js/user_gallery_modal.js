  //Script pour afficher le modal
    // Get the modal

    console.log("Run user_gallery_modal.js");
    var modal = document.getElementById("myModal");
    var modalImg = document.getElementById("img01");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var galleryImages = document.querySelectorAll(".img-gallery img");
    galleryImages.forEach((image) => {
        image.addEventListener("click", handleClick);
    });
    function handleClick(evt) {
        modal.style.display = "flex";
        modalImg.src = this.src;
        //captionText.innerHTML = this.alt;
        evt.currentTarget
    }
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    modal.onclick = function () {
        modal.style.display = "none";
    }