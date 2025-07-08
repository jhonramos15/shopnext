// Mensaje de diagnóstico para confirmar que el script se está cargando.
console.log("Cargando script del carrito desde archivo externo - VERSIÓN FINAL Y COMPLETA");

// Función para dar formato de moneda a los precios
function formatearPrecio(valor) {
    const numero = parseFloat(valor);
    if (isNaN(numero)) {
        return '$ 0,00';
    }
    // VERSIÓN CORREGIDA (sin decimales)
    const opciones = {
        style: 'decimal',
        useGrouping: true,
        minimumFractionDigits: 0, // <-- Corregido
        maximumFractionDigits: 0, // <-- Corregido
    };
    return '$ ' + new Intl.NumberFormat('es-ES', opciones).format(numero);
}

// Función para actualizar el contador del carrito en el header
function actualizarContadorCarrito() {
    fetch('../php/carrito_api.php?action=contar')
        .then(res => res.json())
        .then(data => {
            const contadorHeader = document.getElementById('cart-header-counter');
            if (contadorHeader) {
                contadorHeader.textContent = data.total;
            }
        })
        .catch(error => console.error("Error al actualizar contador del carrito:", error));
}


// Función que carga los productos en el carrito desde la API
function cargarCarrito() {
    fetch('../php/carrito_api.php?action=listar')
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById('cart-items');
            contenedor.innerHTML = '';
            let totalGeneral = 0;

            if (data.length === 0) {
                contenedor.innerHTML = '<p class="empty-cart">Your cart is empty.</p>';
                document.getElementById('subtotal').textContent = formatearPrecio(0);
                document.getElementById('total').textContent = formatearPrecio(0);
                actualizarContadorCarrito(); // Actualizar contador a 0
                return;
            }

            data.forEach(item => {
                const subtotal = item.precio * item.cantidad;
                totalGeneral += subtotal;

                const productRow = document.createElement('div');
                productRow.className = 'cart-item';

                productRow.innerHTML = `
                    <div class="product-details">
                        <div class="product-image">
                            <img src="../${item.imagen}" alt="${item.producto}">
                            <div class="delete-product" onclick="eliminarProducto(${item.id})">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                        <span class="product-name">${item.producto}</span>
                    </div>
                    <div class="product-price">${formatearPrecio(item.precio)}</div>
                    <div class="product-quantity">
                        <div class="quantity-selector">
                            <button class="quantity-btn" onclick="disminuirCantidad(${item.id})">-</button>
                            <input type="number" id="quantity-input-${item.id}" value="${item.cantidad}" min="1" onchange="actualizarCantidad(${item.id}, this.value)">
                            <button class="quantity-btn" onclick="aumentarCantidad(${item.id})">+</button>
                        </div>
                    </div>
                    <div class="product-subtotal">${formatearPrecio(subtotal)}</div>
                `;
                contenedor.appendChild(productRow);
            });

            document.getElementById('subtotal').textContent = formatearPrecio(totalGeneral);
            document.getElementById('total').textContent = formatearPrecio(totalGeneral);
            actualizarContadorCarrito();
        });
}

// Aumenta la cantidad de un producto
function aumentarCantidad(id) {
    const input = document.getElementById(`quantity-input-${id}`);
    let cantidadActual = parseInt(input.value);
    cantidadActual++;
    input.value = cantidadActual;
    actualizarCantidad(id, cantidadActual);
}

// Disminuye la cantidad de un producto
function disminuirCantidad(id) {
    const input = document.getElementById(`quantity-input-${id}`);
    let cantidadActual = parseInt(input.value);
    if (cantidadActual > 1) {
        cantidadActual--;
        input.value = cantidadActual;
        actualizarCantidad(id, cantidadActual);
    }
}

// Llama a la API para actualizar la cantidad en la base de datos
function actualizarCantidad(id, nuevaCantidad) {
    if (nuevaCantidad < 1) {
        nuevaCantidad = 1;
    }
    fetch('../php/carrito_api.php?action=actualizar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&cantidad=${nuevaCantidad}`
    }).then(() => {
        setTimeout(cargarCarrito, 200);
    });
}

// Llama a la API para eliminar un producto
function eliminarProducto(id) {
    fetch('../php/carrito_api.php?action=eliminar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
    }).then(() => cargarCarrito());
}

// Redirige al usuario a la tienda
function returnToShop() {
    window.location.href = '../index.html';
}

// Recarga los datos del carrito
function updateCart() {
    cargarCarrito();
    alert('Cart updated!');
}

// Carga el carrito cuando la página se abre por primera vez
window.onload = () => {
    cargarCarrito();
};