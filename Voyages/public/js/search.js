console.log("Run search.js");

const btnSearch = document.getElementById('button-search');
const inputElt = document.getElementById('field-search');

//Listener submit
btnSearch.addEventListener('submit', function (evt) {
    evt.preventDefault();
    document.querySelector('input').value = '';
}, true)

function createCardSuggestionCity(name, country, urlCity) {
    //cible le template
    let tplElt = document.getElementById('template__elt');
    //fait un clone où je vais insérer du contenu
    let tpl = tplElt.content.cloneNode(true);
    //remplissage des données
    tpl.querySelector(".card-body").innerHTML = '<a class="stretched-link suggestion-text" href="' + urlCity + '"><b>' + name + '</b>, ' + country + '</a>';
    //insertion dans le DOM
    document.getElementById("search-results").appendChild(tpl);
}


function loop(data) {
    console.log(data);
    for (let i = 0; i < data.length; i++) {
        let inputValue = '';
        if (data[i].name) {
            createCardSuggestionCity(data[i].name, data[i].country, data[i].urlCity);
        }

    }
}

inputElt.addEventListener('keyup', handleKeyUp);
let city;
function handleKeyUp(evt) {
    city = evt.target.value;
    if (!city) {
        let searchError = document.getElementById('search-error');
    }
    if (city.length > 1) {
        query(city);
    }
    document.getElementById("search-results").innerHTML = '';
    function query(city) {
        fetch('api/v1/city?city_name=' + city)
            .then(
                (response) => {
                    return response.status == 200 ?
                        (
                            console.log(response.status + ' - opération effectué'),
                            response.json()
                        )

                        :
                        (response.status == 204) ? response.status + ' - pas de resultats'
                            :
                            console.log('L\'opération a échoué')
                })
            .then(
                (data) => {
                    if (data !== 204) {
                        loop(data);
                    }
                }
            );
    }
}

// Clear the result div when clicking somewhere else
/* inputElt.addEventListener('blur', function (evt) { */
inputElt.onblur = function (evt) {
    let target = evt.relatedTarget;

    if (target != null) {
        target.click();
    }
    inputElt.value = '';
    document.getElementById("search-results").innerHTML = '';
}

