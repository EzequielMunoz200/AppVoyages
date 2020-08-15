
// Translatation of the summary
if (document.querySelector('.city-description') !== null ) {
    let summary = document.querySelector('.city-description').dataset.summary;
    //summary = summary.replace(/(<([^>]+)>)/ig, "");

    console.log(summary)

    let firstTranslation = {};
    fetch('/api/v1/translate/', {
        method: 'POST',
        body: JSON.stringify(summary),
        headers: {
            'Content-type': 'application/json',
        }
    }).then(function (response) {
        if (response.ok) {
            return response.json();
        }
        return Promise.reject(response);
    }).then(function (data) {
        //console.log(data);
        document.querySelector('.city-description').innerHTML = '<br>' + data.Translation;

    }).catch(function (error) {
        console.warn('Something went wrong.', error);
    });
}


if (document.querySelector('.city-like') !== null) {
    let likeElt = document.querySelector('.city-like');
    let cityId = likeElt.dataset.cityid;
    likeElt.addEventListener('click', changeHeart);
    function changeHeart(evt) {
        evt.preventDefault();
        let url = '/api/v1/city/' + cityId + '/like';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(
                (response) => {
                    return response.ok ?
                        (
                            console.log(response.status + ' - opération effectué'),
                            response.json()
                        )

                        :
                        (response.status == 204) ? response.status + ' - pas de résultats'
                            :
                            console.log('L\'opération a échoué')
                })
            .then(
                (data) => {
                    if (data.code == 200) {
                        likeElt.innerHTML = '';
                        likeElt.innerHTML = '<i class="far fa-heart"></i>';
                    } else if (data.code == 201) {
                        likeElt.innerHTML = '';
                        likeElt.innerHTML = '<i class="fas fa-heart"></i>';
                    }
                }
            );
    }

}

