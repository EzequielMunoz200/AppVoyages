//Script to report a user
if (document.querySelector('.report-profile') !== null) {
    let reportProfileElt = document.querySelector('.report-profile');

    reportProfileElt.addEventListener('click', handleUserReport);
    function handleUserReport(evt) {
        evt.preventDefault();
        const url = this.href;
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(
            (response) => {
                if (response.ok) {
                    console.log(response.status)
                }
                else {
                    console.log(response.status)
                }
            })
            .then(
                (data) => {
                    evt.target.textContent = 'Profil signalé!';
                    evt.target.style.color = 'crimson';
                }
            );
    }
}

