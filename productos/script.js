document.addEventListener('DOMContentLoaded', function() {

    // --- Lógica para la Galería de Imágenes del producto principal ---
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail-img');

    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                mainImage.src = this.dataset.src;
            });
        });
    }

    // --- Lógica para los selectores de la vista de producto ---
    setupOptionSelectors('.size-btn');
    setupOptionSelectors('.color-swatch');

    function setupOptionSelectors(selector) {
        const options = document.querySelectorAll(selector);
        options.forEach(option => {
            option.addEventListener('click', function() {
                options.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // --- Lógica para el contador de cantidad ---
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const quantityInput = document.getElementById('quantity');

    if (decreaseBtn && increaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        increaseBtn.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });
    }

    // --- Lógica para hacer funcionales los botones de las tarjetas de producto ---
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const addToCartBtn = card.querySelector('.add-to-cart-btn');
        const heartIcon = card.querySelector('.fa-heart');
        const eyeIcon = card.querySelector('.fa-eye');
        const productTitle = card.querySelector('.product-title').textContent;

        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', () => {
                console.log(`Producto "${productTitle}" añadido al carrito.`);
                alert(`"${productTitle}" fue añadido al carrito.`);
            });
        }

        if (heartIcon) {
            heartIcon.parentElement.addEventListener('click', (event) => {
                event.preventDefault();
                heartIcon.classList.toggle('far');
                heartIcon.classList.toggle('fas');

                if (heartIcon.classList.contains('fas')) {
                    console.log(`Producto "${productTitle}" añadido a la lista de deseos.`);
                    heartIcon.style.color = '#8E06C2';
                } else {
                    console.log(`Producto "${productTitle}" eliminado de la lista de deseos.`);
                    heartIcon.style.color = '';
                }
            });
        }

        if (eyeIcon) {
            eyeIcon.parentElement.addEventListener('click', (event) => {
                event.preventDefault();
                console.log(`Mostrando vista rápida para "${productTitle}".`);
                alert(`Vista rápida para "${productTitle}".`);
            });
        }
    });

});


// --- Lógica para el Carrusel de Artículos Relacionados ---
let currentIndex = 0;

function slide(direction) {
    const slider = document.getElementById('related-items-slider');
    if (!slider) return;

    const cards = slider.querySelectorAll('.product-card');
    if (cards.length === 0) return;

    const totalCards = cards.length;
    const sliderWrapper = slider.parentElement;
    const cardWidth = cards[0].offsetWidth;
    const gap = parseFloat(window.getComputedStyle(slider).gap) || 24; // 24px de fallback
    const visibleCards = Math.floor(sliderWrapper.offsetWidth / (cardWidth + gap));
    const maxIndex = Math.max(0, totalCards - visibleCards);

    currentIndex += direction;

    if (currentIndex < 0) {
        currentIndex = 0;
    } else if (currentIndex > maxIndex) {
        currentIndex = maxIndex;
    }

    const offset = currentIndex * (cardWidth + gap);
    slider.style.transform = `translateX(-${offset}px)`;
}