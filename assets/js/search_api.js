const cards = document.querySelectorAll('.card-api');
cards.forEach(card => {
    card.addEventListener('click', function () {
        this.classList.toggle('selected');
    });
});

const addButton = document.getElementById('add-selected');

if (addButton) {
    addButton.addEventListener('click', function () {
        const selectedCards = document.querySelectorAll('.card-api.selected');
        if (selectedCards.length > 0) {
            const data = Array.from(selectedCards).map(card => ({
                title: card.getAttribute('data-title'),
                cover: card.getAttribute('data-cover'),
                artist: card.getAttribute('data-artist'),
                album: card.getAttribute('data-album'),
            }));

            fetch('/api/song/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ songs: data }),
            })
            .then(response => {
                console.log(response);
                if (!response.ok) {
                    throw new Error('Network error for persist song');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                if (data.success) {
                    window.location.replace(data.route);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
}

