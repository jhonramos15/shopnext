document.addEventListener('DOMContentLoaded', () => {
    const placeOrderBtn = document.querySelector('.place-order-btn');
    const applyCouponBtn = document.querySelector('.apply-coupon-btn');
    const form = document.getElementById('checkout-form');

    if (placeOrderBtn && form) {
        placeOrderBtn.addEventListener('click', (event) => {
            // Detenemos el envío para poder validar
            event.preventDefault(); 
            
            const requiredFields = form.querySelectorAll('[required]');
            let firstInvalidField = null;

            // Revisamos cada campo requerido
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    // Si el campo está vacío, lo marcamos en rojo
                    field.style.borderColor = 'red';
                    // Guardamos una referencia al primer campo inválido que encontremos
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    // Si el campo es válido, restauramos su borde
                    field.style.borderColor = '#ced4da';
                }
            });

            // Si encontramos un campo inválido...
            if (firstInvalidField) {
                // ...ponemos el foco sobre él para que el usuario sepa cuál es.
                firstInvalidField.focus();
            } else {
                // Si todo es válido, enviamos el formulario.
                form.submit();
            }
        });
    }

    // Lógica para el botón de cupón (se queda igual)
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', () => {
            const couponInput = document.getElementById('coupon-code');
            if (couponInput.value.trim() !== '') {
                alert(`Cupón "${couponInput.value}" aplicado. (simulación)`);
            } else {
                alert('Por favor, introduce un código de cupón.');
            }
        });
    }
});