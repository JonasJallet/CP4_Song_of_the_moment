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
            const buttonElement = approvedButton.parentNode;
            if (response.data.isApproved) {
                // Add fade-out animation to the cardElement
                buttonElement.style.opacity = '0';
                buttonElement.style.transition = 'opacity 0.5s ease-in-out';
                // Remove the cardElement after the animation is finished
                setTimeout(() => {
                    buttonElement.remove();
                }, 500);
            }
        })
        .catch(error => {
            alert(error);
        });
}
