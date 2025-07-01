document.addEventListener('DOMContentLoaded', function () {

  // ⏱ Reloj para Flash Sales
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
      }
    }, 1000);
  }

  startFlashCountdown(4 * 24 * 60 * 60); // 4 días

  // ❤️ Iconos de corazón y ojo
  document.querySelectorAll('.fa-heart').forEach(icon => {
    icon.addEventListener('click', () => {
      icon.classList.toggle('active');
    });
  });

  document.querySelectorAll('.fa-eye').forEach(icon => {
    icon.addEventListener('click', () => {
      alert('Ver detalles del producto');
    });
  });

  // 🔁 Botones scroll Flash Sales
  const scrollLeftBtn = document.getElementById('scrollLeftBtn');
  const scrollRightBtn = document.getElementById('scrollRightBtn');
  const productsContainer = document.querySelector('.products-container');
  const scrollAmount = 300;

  scrollRightBtn.addEventListener('click', () => {
    productsContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  });

  scrollLeftBtn.addEventListener('click', () => {
    productsContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  });

  // 📱 Carrusel de secciones (íconos)
  const carrusel = document.querySelector('.secciones-carrusel');
  const secciones = document.querySelectorAll('.seccion-item');
  const prevBtn = document.querySelector('.prev-seccion');
  const nextBtn = document.querySelector('.next-seccion');

  let posicionActual = 0;
  const anchoSeccion = secciones[0]?.offsetWidth + 20 || 120;
  let seccionesVisibles = 4;

  function actualizarCarrusel() {
    if (carrusel)
      carrusel.style.transform = `translateX(-${posicionActual * anchoSeccion}px)`;
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      if (posicionActual < secciones.length - seccionesVisibles) {
        posicionActual++;
        actualizarCarrusel();
      }
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      if (posicionActual > 0) {
        posicionActual--;
        actualizarCarrusel();
      }
    });
  }

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

  // 🎵 Contador de música
  const targetDate = new Date();
  targetDate.setDate(targetDate.getDate() + 5);
  targetDate.setHours(targetDate.getHours() + 23);
  targetDate.setMinutes(targetDate.getMinutes() + 59);
  targetDate.setSeconds(targetDate.getSeconds() + 35);

  const countdownEl = document.querySelector('.left-section .countdown');

  function updateMusicCountdown() {
    const now = new Date();
    const diff = targetDate - now;

    const days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
    const hours = String(Math.floor((diff / (1000 * 60 * 60)) % 24)).padStart(2, '0');
    const minutes = String(Math.floor((diff / (1000 * 60)) % 60)).padStart(2, '0');
    const seconds = String(Math.floor((diff / 1000) % 60)).padStart(2, '0');

    if (countdownEl) {
      countdownEl.innerHTML = `
        <div class="time-box"><span>${hours}</span>Horas</div>
        <div class="time-box"><span>${days}</span>Días</div>
        <div class="time-box"><span>${minutes}</span>Minutos</div>
        <div class="time-box"><span>${seconds}</span>Segundos</div>
      `;
    }
  }

  updateMusicCountdown();
  setInterval(updateMusicCountdown, 1000);

  // 🏆 Slider sección productos más vendidos
  let currentSlide = 0;
  window.slide = function (direction) {
    const slider = document.getElementById('slider');
    const totalSlides = slider.children.length;
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
  };

  // 👤 Menú de usuario
  window.toggleDropdown = function () {
    const menu = document.getElementById("dropdownMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  };

  window.onclick = function (event) {
    if (!event.target.matches('.user-icon')) {
      const dropdown = document.getElementById("dropdownMenu");
      if (dropdown && dropdown.style.display === "block") {
        dropdown.style.display = "none";
      }
    }
  };

  // ✅ Swiper.js Carrusel principal
  const swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    }
  });

});
