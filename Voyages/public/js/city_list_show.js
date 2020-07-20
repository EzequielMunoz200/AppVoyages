let picturesElts = document.querySelectorAll('.picture-result');

let listElts = document.querySelectorAll('.list-percent');
listElts.forEach(function (listElt) {
    listElt.addEventListener('click', function (evt) {
        picturesElts.forEach(function (pictureElt) {
            console.log(pictureElt.dataset.range);
            if (pictureElt.dataset.range == evt.currentTarget.dataset.percent ) {
                pictureElt.dataset.show = "true";
            } else {
                pictureElt.dataset.show = "false";
            }
        });
    })
});


function copyToClipboard() {
    var copyUrl = document.getElementById("urlLinkInput");
    copyUrl.select();
    copyUrl.setSelectionRange(0, 99999)
    document.execCommand("copy");
  }
