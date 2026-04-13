/* the title stuff */
const labels = {
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

/* the mascto wave animation stuff */
const frames = ['wav1.png', 'wav2.png'];
let current = 0;

const mascot = document.querySelectorAll('#mascot, #mascotdir');
setInterval(() => {
    mascot.forEach(m => {
        m.src = frames[current];
    });
    current = (current + 1) % frames.length;
}, 500);

/* motd-ish stuff */
const taglines = [
    "It's nothing more than a name",
    "funny text",
    "I'll be honest, this website has no purpose",
    "This website is a mess, but it's my mess",
    "Made in Sweden",
    "BayoBayo is the best mascot",
    "No cookies, I promise, or do I?",
    "Don't look at the source code, it's chaos",
    "I don't know what to put here",
];

document.getElementById('motd').textContent = taglines[Math.floor(Math.random() * taglines.length)];