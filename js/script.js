// Función de carrusel slider (sin cambios)
let index = 0;
function moveSlide() {
    const slides = document.querySelector('.carousel');
    const totalSlides = document.querySelectorAll('.slide').length;
    index = (index + 1) % totalSlides;
    slides.style.transform = `translateX(${-index * 100}%)`;
    updateDots();
}

function createDots() {
    const dotsContainer = document.querySelector('.dots');
    dotsContainer.innerHTML = ''; // Limpia los dots previos
    const totalSlides = document.querySelectorAll('.slide').length;

    for (let j = 0; j < totalSlides; j++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        dot.addEventListener('click', () => {
            index = j;
            document.querySelector('.carousel').style.transform = `translateX(${-index * 100}%)`;
            updateDots();
        });
        dotsContainer.appendChild(dot);
    }
    updateDots();
}

function updateDots() {
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
}

createDots();
setInterval(moveSlide, 3000);


// Función de reloj para la sección de Flash Sales
function startFlashCountdown(duration) {
    let timer = duration;
    const daysElement = document.getElementById("days");
    const hoursElement = document.getElementById("hours");
    const minutesElement = document.getElementById("minutes");
    const secondsElement = document.getElementById("seconds");

    const intervalId = setInterval(() => {
        const days = Math.floor(timer / (60 * 60 * 24));
        const hours = Math.floor((timer % (60 * 60 * 24)) / 3600);
        const minutes = Math.floor((timer % 3600) / 60);
        const seconds = Math.floor(timer % 60);

        if (daysElement) daysElement.textContent = days.toString().padStart(2, '0');
        if (hoursElement) hoursElement.textContent = hours.toString().padStart(2, '0');
        if (minutesElement) minutesElement.textContent = minutes.toString().padStart(2, '0');
        if (secondsElement) secondsElement.textContent = seconds.toString().padStart(2, '0');

        if (--timer < 0) {
            clearInterval(intervalId);
            // Aquí puedes añadir lógica cuando el contador llega a cero
        }
    }, 1000);
}

// Iniciar el contador de Flash Sales (4 días)
startFlashCountdown(4 * 24 * 60 * 60);


// Heart and Eye icon functionality (sin cambios)
document.querySelectorAll('.fa-heart').forEach(icon => {
    icon.addEventListener('click', () => {
        icon.classList.toggle('active');
    });
});

document.querySelectorAll('.fa-eye').forEach(icon => {
    icon.addEventListener('click', () => {
        alert('View product details');
    });
});

// Scroll functionality (sin cambios)
const scrollLeftBtn = document.getElementById('scrollLeftBtn');
const scrollRightBtn = document.getElementById('scrollRightBtn');
const productsContainer = document.querySelector('.products-container');

const scrollAmount = 300;

scrollRightBtn.addEventListener('click', () => {
    productsContainer.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
});

scrollLeftBtn.addEventListener('click', () => {
    productsContainer.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
    });
});


// Catalogo secciones (sin cambios)
document.addEventListener('DOMContentLoaded', function() {
    const carrusel = document.querySelector('.secciones-carrusel');
    const secciones = document.querySelectorAll('.seccion-item');
    const prevBtn = document.querySelector('.prev-seccion');
    const nextBtn = document.querySelector('.next-seccion');

    let posicionActual = 0;
    const anchoSeccion = secciones[0].offsetWidth + 20;
    let seccionesVisibles = 4;

    function actualizarCarrusel() {
        carrusel.style.transform = `translateX(-${posicionActual * anchoSeccion}px)`;
    }

    nextBtn.addEventListener('click', function() {
        if (posicionActual < secciones.length - seccionesVisibles) {
            posicionActual++;
            actualizarCarrusel();
        }
    });

    prevBtn.addEventListener('click', function() {
        if (posicionActual > 0) {
            posicionActual--;
            actualizarCarrusel();
        }
    });

    function ajustarResponsive() {
        const ancho = window.innerWidth;
        if (ancho <= 576) {
            seccionesVisibles = 1;
        } else if (ancho <= 768) {
            seccionesVisibles = 2;
        } else if (ancho <= 992) {
            seccionesVisibles = 3;
        } else {
            seccionesVisibles = 4;
        }
    }

    window.addEventListener('resize', ajustarResponsive);
    ajustarResponsive();
});


// Contador para la sección "Enhance Your Music Experience"
const targetDate = new Date();
targetDate.setDate(targetDate.getDate() + 5); // 5 días desde ahora
targetDate.setHours(targetDate.getHours() + 23);
targetDate.setMinutes(targetDate.getMinutes() + 59);
targetDate.setSeconds(targetDate.getSeconds() + 35);

const countdownEl = document.querySelector('.left-section .countdown'); // Selecciona el contador dentro de la sección izquierda

function updateMusicCountdown() {
    const now = new Date();
    const diff = targetDate - now;

    const days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
    const hours = String(Math.floor((diff / (1000 * 60 * 60)) % 24)).padStart(2, '0');
    const minutes = String(Math.floor((diff / (1000 * 60)) % 60)).padStart(2, '0');
    const seconds = String(Math.floor((diff / 1000) % 60)).padStart(2, '0');

    if (countdownEl) {
        countdownEl.innerHTML = `
            <div class="time-box"><span>${hours}</span>Hours</div>
            <div class="time-box"><span>${days}</span>Days</div>
            <div class="time-box"><span>${minutes}</span>Minutes</div>
            <div class="time-box"><span>${seconds}</span>Seconds</div>
        `;
    }
}

updateMusicCountdown();
setInterval(updateMusicCountdown, 1000);



let currentSlide = 0;
    function slide(direction) {
      const slider = document.getElementById('slider');
      const totalSlides = slider.children.length;
      currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
      slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    }