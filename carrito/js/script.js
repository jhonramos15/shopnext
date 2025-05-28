function updateCart() {
  let subtotal = 0;
  const rows = document.querySelectorAll('#cart-items tr');
  const items = [];

  rows.forEach(row => {
    const price = parseInt(row.getAttribute('data-price'));
    const quantity = parseInt(row.querySelector('.quantity-controls span').textContent);
    const title = row.querySelector('.product-info strong')?.textContent;
    const img = row.querySelector('img')?.src;
    const rating = row.querySelector('.product-info small')?.textContent.replace('Rating: ', '');

    const total = price * quantity;
    row.querySelector('.subtotal').childNodes[0].textContent = `$${total} `;
    subtotal += total;

    // Guardamos el producto actual
    items.push({ title, price, quantity, img, rating });
  });

  document.getElementById('subtotal').textContent = `$${subtotal}`;
  document.getElementById('total').textContent = `$${subtotal}`;

  // Guardar carrito en localStorage y sessionStorage
  localStorage.setItem('cartItems', JSON.stringify(items));
  updateSessionCart(items); // NUEVO
}

function updateCartCount() {
  const rows = document.querySelectorAll('#cart-items tr');
  let totalQty = 0;
  rows.forEach(row => {
    const qty = parseInt(row.querySelector('.quantity-controls span').textContent);
    totalQty += qty;
  });
  document.getElementById('cart-count').textContent = totalQty;
}

function changeQty(button, delta) {
  const span = button.parentElement.querySelector('span');
  let current = parseInt(span.textContent);
  current += delta;
  if (current < 1) current = 1;
  span.textContent = current;
  updateCart();
  updateCartCount();
}

function deleteItem(icon) {
  const row = icon.closest('tr');
  row.remove();
  updateCart();
  updateCartCount();
}

function returnToShop() {
  window.location.href = "index.html";
}

document.addEventListener("DOMContentLoaded", () => {
  loadCartFromStorage();
  updateCart();
  updateCartCount();
});

const cartItems = document.getElementById('cart-items');

function addToCart(event) {
  const button = event.currentTarget;
  const title = button.getAttribute('data-title')
  const price = parseFloat(button.getAttribute('data-price'));
  const imgSrc = button.getAttribute('data-img');
  const rating = button.getAttribute('data-rating') || '';

  const existingRow = [...cartItems.querySelectorAll('tr')].find(row => {
    return row.querySelector('.product-info strong')?.textContent.trim() === title;
  });

  if (existingRow) {
    const qtySpan = existingRow.querySelector('.quantity-controls span');
    let qty = parseInt(qtySpan.textContent);
    qty++;
    qtySpan.textContent = qty;
  } else {
    const tr = document.createElement('tr');
    tr.setAttribute('data-price', price);

    tr.innerHTML = `
      <td>
        <div class="product-info">
          <img src="${imgSrc}" alt="${title}">
          <div>
            <strong>${title}</strong><br>
            <small>Rating: ${rating}</small>
          </div>
        </div>
      </td>
      <td>$${price}</td>
      <td>
        <div class="quantity-controls">
          <button onclick="changeQty(this, -1)">&#8722;</button>
          <span>1</span>
          <button onclick="changeQty(this, 1)">&#43;</button>
        </div>
      </td>
      <td class="subtotal">
        <span class="subtotal-amount">$${price}</span>
        <span class="delete-icon" onclick="deleteItem(this)">
          <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" fill="#7e00ff" style="vertical-align: middle;">
            <path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-4.5l-1-1z"/>
          </svg>
        </span>
      </td>
    `;
    cartItems.appendChild(tr);
  }

  updateCart();
  updateCartCount();
}

// Cargar carrito desde localStorage
function loadCartFromStorage() {
  const savedItems = JSON.parse(localStorage.getItem('cartItems')) || [];
  savedItems.forEach(item => {
    const tr = document.createElement('tr');
    tr.setAttribute('data-price', item.price);

    tr.innerHTML = `
      <td>
        <div class="product-info">
          <img src="${item.img}" alt="${item.title}">
          <div>
            <strong>${item.title}</strong><br>
            <small>Rating: ${item.rating}</small>
          </div>
        </div>
      </td>
      <td>$${item.price}</td>
      <td>
        <div class="quantity-controls">
          <button onclick="changeQty(this, -1)">&#8722;</button>
          <span>${item.quantity}</span>
          <button onclick="changeQty(this, 1)">&#43;</button>
        </div>
      </td>

      <td class="subtotal">
        <span class="subtotal-amount">$${item.price * item.quantity}</span>
        <span class="delete-icon" onclick="deleteItem(this)">
          <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" fill="#7e00ff" style="vertical-align: middle;">
            <path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-4.5l-1-1z"/>
          </svg>
        </span>
      </td>
    `;
    cartItems.appendChild(tr);
  });
}

// Asignar eventos a los botones de añadir al carrito
document.querySelectorAll('.add-to-cart-btns, .add-to-cart-btn').forEach(btn => {
  const productCard = btn.closest('.product, .product-card');
  if (productCard) {
    const title = productCard.querySelector('p, .products-title')?.textContent.trim() || 'Producto';
    const priceText = productCard.querySelector('.price, .prices')?.textContent.replace('$', '').trim() || '0';
    const ratingText = productCard.querySelector('.rating, .stars2')?.textContent.trim() || '';
    const img = productCard.querySelector('img');

    btn.setAttribute('data-title', title);
    btn.setAttribute('data-price', priceText);
    btn.setAttribute('data-rating', ratingText);
    btn.setAttribute('data-img', img?.getAttribute('src') || '');

    btn.addEventListener('click', addToCart);
  }
});

// NUEVA FUNCIÓN PARA SESSION STORAGE
function updateSessionCart(items) {
  sessionStorage.setItem('sessionCart', JSON.stringify(items));
}
