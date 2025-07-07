document.addEventListener('DOMContentLoaded', () => {

    // Lógica para añadir productos al carrito (sin cambios)
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const productoEl = btn.closest('.product');
            const nombre = productoEl.querySelector('.product-title').textContent.trim();
            const precioTexto = productoEl.querySelector('.price').childNodes[0].nodeValue;
            const precio = parseInt(precioTexto.replace(/\D/g, ''));
            const imagen = productoEl.querySelector('img').getAttribute('src');

            fetch('php/carrito_api.php?action=agregar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `producto=${encodeURIComponent(nombre)}&precio=${precio}&cantidad=1&imagen=${encodeURIComponent(imagen)}`
            }).then(() => {
                alert("Producto agregado al carrito");
            });
        });
    });

    // --- LÓGICA CORREGIDA PARA AÑADIR A WISHLIST ---
    document.querySelectorAll('.fa-heart').forEach(heartIcon => {
        heartIcon.addEventListener('click', () => {
            const productoEl = heartIcon.closest('.product');
            const nombre = productoEl.querySelector('.product-title').textContent.trim();
            const precioTexto = productoEl.querySelector('.price').childNodes[0].nodeValue;
            const precio = parseInt(precioTexto.replace(/\D/g, ''));
            const imagen = productoEl.querySelector('img').getAttribute('src');

            // Llamada a la API para agregar a la lista de deseos
            fetch('php/wishlist_api.php?action=agregar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `producto=${encodeURIComponent(nombre)}&precio=${precio}&imagen=${encodeURIComponent(imagen)}`
            })
            .then(res => res.json())
            .then(data => {
                // Simplemente mostramos una alerta al usuario. No se actualiza ningún contador.
                alert(data.message);
            });
        });
    });
});