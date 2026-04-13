const labels = {
    "/": "Home",
    "/index.html": "/Home",
    "/about.html": "/About",
    "/directory.html": "/Directory",
    "/portfolio.html": "/Portfolio",
    "/notes.html": "/Notes",
    "/contact.html": "/Contact"
};

const path = window.location.pathname;
const filename = '/' + path.split('/').pop();
const label = labels[filename] ?? filename.replace(/\//g, '').replace('.html', '');
document.getElementById("current").textContent = label;
document.querySelectorAll('nav a[href]').forEach(a => {
    const linkFile = '/' + a.getAttribute('href').split('/').pop();
    if (linkFile === filename) {
        a.classList.add('text-gray-300', 'font-bold');
    }
});