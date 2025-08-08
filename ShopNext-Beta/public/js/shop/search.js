document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchResultsContainer = document.getElementById('search-results');

    if (!searchForm || !searchInput || !searchResultsContainer) {
        console.error("Error: No se encontró uno de los elementos del buscador.");
        return;
    }

    const performSearch = (query) => {
        if (query.length < 2) {
            searchResultsContainer.style.display = 'none';
            return;
        }

        // ✅ --- CORRECCIÓN AQUÍ --- ✅
        // 1. Apuntamos a nuestro enrutador principal con la acción correcta.
        // 2. Usamos 'term' como nombre del parámetro para que coincida con el controlador.
        const fetchURL = `index.php?action=search&term=${encodeURIComponent(query)}`;

        fetch(fetchURL)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                searchResultsContainer.innerHTML = ''; 

                if (data && data.length > 0) {
                    data.forEach(product => {
                        const item = document.createElement('a');
                        // Asegúrate que la ruta al detalle del producto sea correcta
                        item.href = `index.php?action=product-detail&id=${product.id_producto}`;
                        item.classList.add('search-result-item');

                        // La ruta de la imagen debe ser relativa a la raíz pública
                        const imagePath = `uploads/products/${product.ruta_imagen}`;

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
                    searchResultsContainer.innerHTML = '<div class="search-no-results">No se encontraron productos.</div>';
                }

                searchResultsContainer.style.display = 'block';
            })
            .catch(error => {
                console.error('Error al procesar la búsqueda:', error);
                searchResultsContainer.innerHTML = '<div class="search-no-results">Error al mostrar resultados.</div>';
                searchResultsContainer.style.display = 'block';
            });
    };

    searchInput.addEventListener('keyup', () => {
        performSearch(searchInput.value);
    });

    searchForm.addEventListener('submit', (event) => {
        event.preventDefault(); 
        performSearch(searchInput.value);
    });

    document.addEventListener('click', (event) => {
        if (!searchResultsContainer.contains(event.target) && !searchInput.contains(event.target)) {
            searchResultsContainer.style.display = 'none';
        }
    });
});