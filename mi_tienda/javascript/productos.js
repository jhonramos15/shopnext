// Espera a que el contenido del DOM esté completamente cargado antes de ejecutar el script.
document.addEventListener('DOMContentLoaded', () => {

    // Función para formatear los precios en la página de productos
    function formatearPrecio(valor) {
        const numero = parseFloat(valor);
        if (isNaN(numero)) {
            return '$ 0';
        }
        // Usamos 'es-CO' para el formato de peso colombiano.
        return '$' + new Intl.NumberFormat('es-CO').format(numero);
    }

    // Formatear los precios que ya están en la página al cargarla.
    document.querySelectorAll('.price').forEach(priceElement => {
        // Se extrae el texto del precio principal, ignorando el precio antiguo.
        const precioTexto = priceElement.childNodes[0].nodeValue; 
        const oldPriceElement = priceElement.querySelector('.old-price');
        let oldPriceContent = '';

        if (oldPriceElement) {
            oldPriceContent = ' ' + oldPriceElement.outerHTML;
        }

        // Se limpia el texto del precio para convertirlo en un número.
        const precioNumerico = parseFloat(precioTexto.replace(/\$|\./g, ''));
        // Se reescribe el HTML del elemento con el precio ya formateado.
        priceElement.innerHTML = formatearPrecio(precioNumerico) + oldPriceContent;
    });

    // Lógica para añadir productos al carrito
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const producto = btn.closest('.product');
            const nombre = producto.querySelector('.product-title').textContent.trim();
            const imagen = producto.querySelector('img').getAttribute('src');

            // 1. Seleccionamos solo el nodo de texto del precio principal.
            const precioTexto = producto.querySelector('.price').childNodes[0].nodeValue.trim();
            // 2. Eliminamos todos los caracteres no numéricos (como '$' y '.') y lo convertimos a un entero.
            const precio = parseInt(precioTexto.replace(/\D/g, ''));

            // Se realiza la llamada a la API para agregar el producto.
            fetch('php/carrito_api.php?action=agregar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `producto=${encodeURIComponent(nombre)}&precio=${precio}&cantidad=1&imagen=${encodeURIComponent(imagen)}`
            }).then(res => res.text()).then(data => {
                alert("Producto agregado al carrito");
            });
        });
    });
});