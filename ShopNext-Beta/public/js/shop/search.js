document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchResultsContainer = document.getElementById('search-results');

    const performSearch = (query) => {
        if (query.length < 2) {
            searchResultsContainer.style.display = 'none';
            return;
        }

        const fetchURL = `${App.baseUrl}index.php?action=search&term=${encodeURIComponent(query)}`;

        // ✅ "Detective" para ver qué URL estamos usando
        console.log("Enviando petición a:", fetchURL);

        fetch(fetchURL)
            .then(response => response.json())
            .then(data => {
                // ... (el resto de tu código para mostrar los resultados)
            })
            .catch(error => {
                console.error('Error al procesar la búsqueda:', error);
            });
    };

    searchInput.addEventListener('input', () => performSearch(searchInput.value));
    // ... (el resto de tus event listeners)
});