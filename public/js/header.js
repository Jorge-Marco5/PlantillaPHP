const btnOpen = document.getElementById('btn-open');
const btnClose = document.getElementById('btn-close');
const sidebar = document.getElementById('main-nav');
const overlay = document.getElementById('overlay');

function toggleMenu() {
  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
}

btnOpen.addEventListener('click', toggleMenu);
btnClose.addEventListener('click', toggleMenu);
overlay.addEventListener('click', toggleMenu); // Cerrar al tocar fuera