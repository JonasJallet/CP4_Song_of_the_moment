const playlistButton = document.getElementById('addToPlaylist');
let url = document.querySelector('#addToPlaylist').dataset.playlistUrl;
let popup;

function openPlaylistPopup()
{
    fetch(url)
        .then(response => response.text())
        .then(data => {

            popup = document.createElement('div');
            popup.classList.add('popup');
            popup.innerHTML = data;


            popup.addEventListener('click', function (event) {
                event.stopPropagation();
            });

            document.body.appendChild(popup);

            initializeAddToPlaylistId();
        })
        .catch(error => {
            console.error('Une erreur s\'est produite :', error);
        });
}

function closePlaylistPopup()
{
    if (popup) {
        popup.remove();
    }
}

playlistButton.addEventListener('click', openPlaylistPopup);
document.addEventListener('click', closePlaylistPopup);


function initializeAddToPlaylistId()
{
    let addToPlaylistElements = document.querySelectorAll('.addToPlaylistClass');
    addToPlaylistElements.forEach(function (element) {
        if (element.dataset.playlistIdUrl) {
            element.addEventListener("click", addToPlaylist);
        }
    });

    function addToPlaylist(e)
    {
        e.preventDefault();

        let url = this.dataset.playlistIdUrl;

        try {
            fetch(url)
                // Extract the JSON from the response
                .then(res => res.json())
                .then(data => {

                });
        } catch (error) {
            alert(error);
        }
    }
}

