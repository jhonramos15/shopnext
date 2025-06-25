document.addEventListener('DOMContentLoaded', () => {
  const currentPage = window.location.pathname.split('/').pop();
  const menuLinks = document.querySelectorAll('.menu li a');

  menuLinks.forEach(link => {
    const linkPage = link.getAttribute('href').split('/').pop();
    if (linkPage === currentPage || (currentPage === 'ingresos.html' && linkPage === 'ingresos.html')) {
      link.parentElement.classList.add('active');
    } else {
      link.parentElement.classList.remove('active');
    }
  });

  lucide.createIcons();

  // Dropdown de perfil
  const userProfileBtn = document.querySelector('.user');
  const profileDropdownMenu = document.getElementById('profileDropdownMenu');
  const profileArrow = userProfileBtn?.querySelector('.profile-arrow');

  if (userProfileBtn) {
    userProfileBtn.addEventListener('click', () => {
      profileDropdownMenu?.classList.toggle('show');
      profileArrow?.classList.toggle('rotate');
    });

    window.addEventListener('click', (event) => {
      if (!userProfileBtn.contains(event.target) && !profileDropdownMenu.contains(event.target)) {
        profileDropdownMenu?.classList.remove('show');
        profileArrow?.classList.remove('rotate');
      }
    });
  }

  // Botón Editar
document.querySelectorAll(".action-icon[title='Editar']").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const row = btn.closest("tr");
    const id = row.dataset.id;

    const cells = row.querySelectorAll("td");

    document.getElementById("edit-id").value = id;
    document.getElementById("edit-nombre").value = cells[0].innerText;
    document.getElementById("edit-direccion").value = cells[1].innerText;
    document.getElementById("edit-email").value = cells[2].innerText;

    document.getElementById("edit-form-row").style.display = "table-row";
  });
});

  // Cancelar edición
  document.getElementById("cancel-edit").addEventListener("click", () => {
    document.getElementById("edit-form-row").style.display = "none";
  });

  // Guardar cambios
document.getElementById("edit-form").addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("edit-id").value;
  const nombre = document.getElementById("edit-nombre").value;
  const direccion = document.getElementById("edit-direccion").value;
  const correo = document.getElementById("edit-email").value;

  const data = new FormData();
  data.append("accion", "editar");
  data.append("id", id);
  data.append("nombre", nombre);
  data.append("direccion", direccion);
  data.append("correo", correo);

  const res = await fetch("../../../controllers/clienteController.php", {
    method: "POST",
    body: data
  });

  const response = await res.text();

  if (response === "ok" || response === "sin cambios") {
    const row = document.querySelector(`tr[data-id='${id}']`);
    row.children[0].innerText = nombre;
    row.children[1].innerText = direccion;
    row.children[2].innerText = correo;

    document.getElementById("edit-form-row").style.display = "none";
  } else {
    alert("Error al guardar cambios");
    console.error("Respuesta del servidor:", response);
  }
});


// Eliminar
document.querySelectorAll(".action-icon[title='Eliminar']").forEach((btn) => {
  btn.addEventListener("click", async (e) => {
    e.preventDefault();
    if (!confirm("¿Seguro que deseas eliminar este cliente?")) return;

    const row = btn.closest("tr");
    const idCliente = row.dataset.id; // ✅ Aquí tomamos el ID real del cliente

    const data = new FormData();
    data.append("accion", "eliminar");
    data.append("id", idCliente); // ✅ Usamos el ID correcto

    const res = await fetch("../../../controllers/clienteController.php", {
      method: "POST",
      body: data
    });

    const result = await res.text();
    if (result === "eliminado") {
      row.remove();
    } else {
      alert("No se pudo eliminar.");
    }
  });
});
});
