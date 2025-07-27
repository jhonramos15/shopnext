// Se ejecuta cuando todo el contenido de la página se ha cargado.
document.addEventListener('DOMContentLoaded', function () {

    // 1. Obtenemos los elementos del HTML con los que vamos a trabajar.
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchResultsContainer = document.getElementById('search-results');

    // Verificación por si algún elemento no se encuentra.
    if (!searchForm || !searchInput || !searchResultsContainer) {
        console.error("Error: No se encontró uno de los elementos del buscador (form, input o results).");
        return;
    }

    /**
     * Función que realiza la búsqueda asíncrona al servidor.
     * @param {string} query - El texto que se va a buscar.
     */
    const performSearch = (query) => {
        // Si la búsqueda es muy corta, oculta los resultados y no hace nada más.
        if (query.length < 2) {
            searchResultsContainer.style.display = 'none';
            return;
        }

        // URL del controlador PHP que procesa la búsqueda.
        const fetchURL = `../controllers/searchController.php?query=${encodeURIComponent(query)}`;

        fetch(fetchURL)
            .then(response => response.json()) // Convierte la respuesta del servidor a JSON.
            .then(data => {
                searchResultsContainer.innerHTML = ''; // Limpia resultados anteriores.

                // Si la respuesta contiene datos, crea la lista de resultados.
                if (data && data.length > 0) {
                    data.forEach(product => {
                        const item = document.createElement('a');
                        
                        // Ruta a la página de detalle del producto.
                        item.href = `../views/pages/productoDetalle.php?id=${product.id_producto}`;
                        item.classList.add('search-result-item');

                        // Ruta a la imagen del producto (con una imagen por defecto).
                        const imagePath = product.ruta_imagen 
                            ? `uploads/products/${product.ruta_imagen}` 
                            : 'img/default.png';

                        // Crea el HTML para cada item del resultado.
                        item.innerHTML = `
                            <img src="${imagePath}" alt="${product.nombre_producto}" style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="info">
                                <div class="product-name">${product.nombre_producto}</div>
                                <div class="product-price">$${new Intl.NumberFormat('es-CO').format(product.precio)}</div>
                            </div>
                        `;
                        searchResultsContainer.appendChild(item);
                    });
                } else {
                    // Si no hay resultados, muestra un mensaje.
                    searchResultsContainer.innerHTML = '<div class="search-no-results">No se encontraron productos.</div>';
                }
                
                // Muestra el contenedor de resultados.
                searchResultsContainer.style.display = 'block';
            })
            .catch(error => {
                // Captura cualquier error de red o de procesamiento.
                console.error('Error al procesar los resultados de la búsqueda:', error);
                searchResultsContainer.innerHTML = '<div class="search-no-results">Error al mostrar resultados.</div>';
                searchResultsContainer.style.display = 'block';
            });
    };

    // 2. Asignamos los eventos a los elementos.

    // Evento para buscar mientras el usuario escribe.
    searchInput.addEventListener('keyup', () => {
        performSearch(searchInput.value);
    });

    // Evento para buscar cuando el usuario presiona "Enter".
    searchForm.addEventListener('submit', (event) => {
        event.preventDefault(); // ¡Crucial! Evita que la página se recargue.
        performSearch(searchInput.value);
        return false; // Asegura la cancelación del envío del formulario.
    });

    // Evento para ocultar los resultados si el usuario hace clic fuera del buscador.
    document.addEventListener('click', (event) => {
        if (!searchForm.contains(event.target)) {
            searchResultsContainer.style.display = 'none';
        }
    });

});