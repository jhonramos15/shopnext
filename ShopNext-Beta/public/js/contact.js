// Lógica para el menú desplegable del perfil de usuario
function toggleDropdown() {
  const menu = document.getElementById("dropdownMenu");
  if (menu) {
    menu.classList.toggle("show");
  }
}

// Cierra el menú si se hace clic fuera de él
window.onclick = function(event) {
  if (!event.target.matches('.user-icon')) {
    const dropdown = document.getElementById("dropdownMenu");
    if (dropdown && dropdown.classList.contains('show')) {
      dropdown.classList.remove('show');
    }
  }
}

// Lógica para el menú hamburguesa
function toggleMenu() {
    const navMenu = document.getElementById("navMenu");
    if (navMenu) {
        navMenu.classList.toggle("active");
    }
}
