import {flashMessages} from "./flash.js";

const playlistButtons = document.getElementsByClassName('playlist-popup');
Array.from(playlistButtons).forEach(function (element) {
    element.addEventListener('click', openPlaylistPopup);
});

let popup;

function openPlaylistPopup(e) {
    e.preventDefault();

    let url = this.dataset.playlistUrl;

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
            AddToPlaylistId();
            newPlaylist();
        })
        .catch(error => {
            console.error('An error occurred:', error);
        });
}

function closePlaylistPopup() {
    if (popup) {
        popup.remove();
    }
}

document.addEventListener('click', closePlaylistPopup);

function AddToPlaylistId() {
    let addToPlaylistElements = document.getElementsByClassName('playlist-collection');
    Array.from(addToPlaylistElements).forEach(function (element) {
        if (element.dataset.playlistIdUrl) {
            element.addEventListener("click", addToPlaylist);
        }
    });

    function addToPlaylist(e) {
        e.preventDefault();
        let url = this.dataset.playlistIdUrl;
        try {
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    closePlaylistPopup();
                    flashMessages('success', 'Ajouté à la playlist !');
                });
        } catch (error) {
            alert(error);
        }
    }
}

function newPlaylist() {
    const playlistNewButton = document.getElementById('playlist-popup-new');
    playlistNewButton.addEventListener('click', newPlaylistPopup);

    function newPlaylistPopup(e) {
        e.preventDefault();

        let url = this.dataset.playlistNewUrl;

        fetch(url)
            .then(response => response.text())
            .then(data => {
                popup.innerHTML = data;
                newPlaylistAdd()
            })
            .catch(error => {
                console.error('An error occurred:', error);
            });
    }
}

function newPlaylistAdd() {
    const playlistNewAddButton = document.getElementById('playlist-new-add');
    playlistNewAddButton.addEventListener('click', newAddSong);

    function newAddSong(e) {
        e.preventDefault();

        let form = document.getElementById('createPlaylistForm');
        let formData = new FormData(form);
        let url = this.dataset.playlistNewAddUrl;

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                closePlaylistPopup();
                flashMessages('success', 'Ajouté à la playlist !');
            })
            .catch(error => {
                console.error('An error occurred:', error);
            });
    }
}

