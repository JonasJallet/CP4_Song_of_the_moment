let favoriteButtons = document.getElementsByClassName("favorite");

if (favoriteButtons) {
    Array.from(favoriteButtons).forEach(function (element) {
        element.addEventListener('click', addToFavorite);
    });
}

function addToFavorite(e)
{
    e.preventDefault();

    const favoriteLink = e.currentTarget;
    const link = favoriteLink.href;

    try {
        fetch(link)
            .then(res => res.json())
            .then(data => {
                const favoriteIcon = favoriteLink.firstElementChild;
                if (data.isInFavorite) {
                    favoriteIcon.classList.remove("bi-heart");
                    favoriteIcon.classList.add("bi-heart-fill");
                } else {
                    favoriteIcon.classList.remove("bi-heart-fill");
                    favoriteIcon.classList.add("bi-heart");
                }
            });
    } catch (error) {
        alert(error);
    }
}

let deleteButtons = document.getElementsByClassName("favorite-delete");
let favoriteLengthElement = document.getElementById('favorite-length');
let favoriteLength = favoriteLengthElement ? parseInt(favoriteLengthElement.innerText) : 0;

if (deleteButtons) {
    Array.from(deleteButtons).forEach(function (element) {
        element.addEventListener('click', removeToFavorite);
    });
}

function removeToFavorite(e)
{
    e.preventDefault();

    const favoriteLink = e.currentTarget;
    const link = favoriteLink.href;
    const trParent = favoriteLink.closest('tr');
    try {
        fetch(link)
            .then(res => res.json())
            .then(data => {
                trParent.classList.add('delete-fade-out');
                setTimeout(() => {
                    trParent.remove();
                    favoriteLength--;
                    favoriteLengthElement.innerText = favoriteLength.toString();
                }, 500);
            });
    } catch (error) {
        alert(error);
    }
}
