document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    // Valida que todos los elementos existan antes de continuar
    if (!searchForm || !searchInput || !searchResults) {
        console.error('Error: No se encontró uno de los elementos del buscador (form, input o results).');
        return;
    }

    // Evento que se dispara al escribir en el buscador
    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();

        // Solo busca si hay al menos 2 caracteres
        if (query.length > 1) {
            fetch('/shopnext/ShopNext-Beta/controllers/searchController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `query=${encodeURIComponent(query)}`
            })
            .then(response => response.text())
            .then(data => {
                searchResults.innerHTML = data;
                searchResults.style.display = 'block'; // Muestra el contenedor de resultados
            })
            .catch(error => console.error('Error:', error));
        } else {
            searchResults.style.display = 'none'; // Oculta si la búsqueda es muy corta
            searchResults.innerHTML = '';
        }
    });

    // Oculta los resultados al hacer clic en cualquier otra parte de la página
    document.addEventListener('click', function(event) {
        if (!searchForm.contains(event.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Evita que el formulario recargue la página al presionar Enter
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
    });
});