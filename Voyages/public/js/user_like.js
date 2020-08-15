//User Like Script
console.log('Run user_like.js');
if (document.querySelector('.user-like') !== null) {
    let likeElt = document.querySelector('.user-like');
    let connectedUserId = likeElt.dataset.connecteduser;
    let targetedUserId = likeElt.dataset.targeteduser;
    likeElt.addEventListener('click', changeHeart);
    function changeHeart(evt) {
        evt.preventDefault();
        let url = '/api/v1/user/' + targetedUserId;
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
