let cities = document.querySelectorAll('.city-card');
let cityName = '';
let cityId = '';
cities.forEach(
    function (city) {
        let cityName = city.dataset.name;
        //cityName = cityName.replace(/\s/g,'');
        cityName = cityName.replace(/\s/g, '-');
        let cityId = city.dataset.geonameid;
        // Récupération des images de ville
        fetch('/api/v1/image/' + cityName)
            .then(
                (response) => {
                    return response.status == 200 ?
                        (
                            //console.log(response.status + ' - opération effectué'),
                            response.json()
                        )
                        :
                        (response.status === 204) ? response.status + ' - pas de resultats'
                            :
                            console.log('L\'opération a échoué')
                })
            .then(
                (data) => {
                    console.log(data);
                    let imageElement = city.querySelector('img');
                    let imageElt = city.querySelector('source');
                    imageElt.setAttribute('srcset', data.urlImage.regular);
                    imageElement.setAttribute('src', data.urlImage.full);
                    //console.log(data.thumb);
                }
            );
    }
)