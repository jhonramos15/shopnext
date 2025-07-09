document.addEventListener('DOMContentLoaded', () => {
    const placeOrderBtn = document.querySelector('.place-order-btn');
    const applyCouponBtn = document.querySelector('.apply-coupon-btn');
    const form = document.getElementById('checkout-form');

    // Evento para el botón "Place Order"
    placeOrderBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional
        
        // Simple validación para asegurar que los campos requeridos no estén vacíos
        const requiredFields = form.querySelectorAll('[required]');
        let allFieldsValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                allFieldsValid = false;
                // Opcional: Resaltar campos inválidos
                field.style.borderColor = 'red';
            } else {
                field.style.borderColor = '#e0e0e0';
            }
        });

        if (allFieldsValid) {
            alert('¡Pedido realizado con éxito!');
            // Aquí iría la lógica para enviar los datos del formulario a un servidor
            // form.submit(); 
        } else {
            alert('Por favor, completa todos los campos obligatorios (*).');
        }
    });

    // Evento para el botón "Apply Coupon"
    applyCouponBtn.addEventListener('click', () => {
        const couponInput = document.getElementById('coupon-code');
        if (couponInput.value.trim() !== '') {
            alert(`Cupón "${couponInput.value}" aplicado.`);
            // Aquí iría la lógica para validar y aplicar el cupón
        } else {
            alert('Por favor, introduce un código de cupón.');
        }
    });
});