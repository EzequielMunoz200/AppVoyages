//affichage du filename dans le label inputfile
const inputElt = document.getElementById("review_imageFile");
inputElt.addEventListener('change', function (evt) {
    let filename = evt.currentTarget.value.split("\\").pop();
    document.querySelector('.custom-file-label').textContent = filename;
});

let latitude = document.getElementById('mapid').dataset.latitude;
let longitude = document.getElementById('mapid').dataset.longitude;
//console.log(latitude + ' ' + longitude);
//https://leafletjs.com/examples/quick-start/
let mymap = L.map('mapid').setView([latitude, longitude], 10);
//console.log(mymap)
// Token here : https://account.mapbox.com/
L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoid2VseXdlbG9vIiwiYSI6ImNrYmFmYXdyYjBubGwycW84ZWJxYWk1MXAifQ.0HxMbJuY3rmSYfS2hmhcVw', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoid2VseXdlbG9vIiwiYSI6ImNrYmFmYXdyYjBubGwycW84ZWJxYWk1MXAifQ.0HxMbJuY3rmSYfS2hmhcVw'
}).addTo(mymap);
let marker = L.marker([latitude, longitude]).addTo(mymap);

//Script pour afficher le modal
// Get the modal
var modal = document.getElementById("myModal");
var modalImg = document.getElementById("img01");
// Get the image and insert it inside the modal - use its "alt" text as a caption
var galleryImages = document.querySelectorAll(".img-gallery img");
galleryImages.forEach((image) => {
    image.addEventListener("click", handleClick);
});
function handleClick(evt) {
    modal.style.display = "block";
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


