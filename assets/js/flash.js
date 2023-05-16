const flashElement = document.getElementById('flash');

if (flashElement) {
    setTimeout(() => {
        flashElement.classList.add('slide-in');
    }, 100);

    setTimeout(() => {
        flashElement.classList.add('fade-out');
    }, 6000);

    setTimeout(() => {
        flashElement.remove();
    }, 7000);
}
