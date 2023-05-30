function attachEventListeners()
{
    setupSongRows();
}

function fetchContent(event)
{
    event.preventDefault();

    fetch(this.getAttribute('href'), {
        method: 'GET'
    })
        .then(response => response.text())
        .then(data => {
            document.getElementById('favoriteContent').innerHTML = data;
            setupSongRows();

            let showPlaylist = document.getElementById('playlist');
            if (showPlaylist) {
                showPlaylist.addEventListener('click', fetchContent);
            }
        })
        .catch(error => {
            console.log('An error occurred:', error);
        });
}

document.getElementById('favorites').addEventListener('click', fetchContent);
document.getElementById('playlists').addEventListener('click', fetchContent);

attachEventListeners();
