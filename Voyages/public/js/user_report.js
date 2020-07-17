//Script to report a user

let reportProfileElt = document.querySelector('.report-profile');
 /*  reviewButtons.forEach(
    function (reviewButton) { */
        reportProfileElt.addEventListener('click', handleUserReport);
 /*    }
  ) */
  function handleUserReport(evt) {
    evt.preventDefault();
    const url = this.href;
    let feedbackMessage = evt.target.closest('.feedback-user-report');
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    }).then(
      (response) => {
        if (response.status == 202) {
          console.log(response.status + ' - opération effectuée')
        }
        else {
          console.log(response.status)
        }
      })
      .then(
        (data) => {
            evt.target.textContent = 'L\'profil est signalé.';
            evt.target.style.color = 'red';
        }
      );
  }
