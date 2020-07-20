//affichage du filename dans le label inputfile
const inputElt = document.getElementById("user_avatar");
inputElt.addEventListener('change', function (evt) {
    let filename = evt.currentTarget.value.split("\\").pop();
    document.querySelector('.custom-file-label').textContent = filename;
});