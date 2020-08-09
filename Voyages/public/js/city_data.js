if( document.querySelector('.city-like') !== null ){
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
                        (response.status == 204) ? response.status + ' - pas de resultats'
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
