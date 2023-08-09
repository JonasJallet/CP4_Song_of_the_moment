import axios from 'axios';

const approvedButtons = document.getElementsByClassName('approved');
for (let i = 0; i < approvedButtons.length; i++) {
    const button = approvedButtons[i];
    button.addEventListener('click', addToApproved);
}

function addToApproved(e)
{
    e.preventDefault();

    const approvedButton = e.currentTarget;
    const link = approvedButton.parentElement.action;

    axios.post(link, { id: approvedButton.previousElementSibling.value })
        .then(response => {
            const cardElement = approvedButton.closest('.card-admin'); // Get the parent card element
            if (response.data.isApproved) {
                cardElement.style.transition = 'opacity 1s ease-in-out';
                cardElement.style.opacity = '0';
                cardElement.addEventListener('transitionend', () => {
                    cardElement.remove(); // Remove the card after the transition ends
                });
            }
        })
        .catch(error => {
            alert(error);
        });
}
