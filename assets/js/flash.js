const flashElement = document.getElementById('flash');

if (flashElement) {
    setTimeout(() => {
        flashElement.classList.add('fade-in');
    }, 100);

    setTimeout(() => {
        flashElement.classList.remove('fade-in');
        flashElement.classList.add('fade-out');
        setTimeout(() => {
            flashElement.remove();
        }, 500);
    }, 6000);

}

export function flashMessages(label, message) {
    const flashDiv = document.createElement('div');
    flashDiv.id = 'flash';
    flashDiv.className = `alert border border-start-0 border-end-0 border-bottom-0 border-5 border-${label} position-absolute mb-4 mx-auto`;
    flashDiv.style.width = '300px';
    flashDiv.style.left = '0';
    flashDiv.style.right = '0';
    flashDiv.style.textAlign = 'center';
    flashDiv.style.opacity = '0';
    flashDiv.style.transition = 'opacity 0.5s ease-in-out';
    flashDiv.role = 'alert';
    flashDiv.innerHTML = `
        <div class="d-flex flex-row align-items-center">
            <img id="flash-logo" src="/build/images/icons/success.gif" alt="Flash Logo">
            <h5 class="text-white lead m-2">${message}</h5>
        </div>
    `;

    document.body.appendChild(flashDiv);
    setTimeout(() => {
        flashDiv.style.opacity = '1';
    }, 100);
    setTimeout(() => {
        flashDiv.style.opacity = '0';
        setTimeout(() => {
            flashDiv.remove();
        }, 500);
    }, 6000);
}
