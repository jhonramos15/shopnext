// Carrusel
let indice = 0;
const slides = document.querySelectorAll('.slide');

function cambiarSlide(direccion) {
  slides[indice].classList.remove('activo');
  indice = (indice + direccion + slides.length) % slides.length;
  slides[indice].classList.add('activo');
}