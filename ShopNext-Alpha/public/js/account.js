let currentSlide = 0;
    function slide(direction) {
      const slider = document.getElementById('slider');
      const totalSlides = slider.children.length;
      currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
      slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    }

function toggleDropdown() {
  const menu = document.getElementById("dropdownMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Cierra el menú si se da clic fuera
window.onclick = function(event) {
  if (!event.target.matches('.user-icon')) {
    const dropdown = document.getElementById("dropdownMenu");
    if (dropdown && dropdown.style.display === "block") {
      dropdown.style.display = "none";
    }
  }
}

