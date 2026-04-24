let cart = JSON.parse(localStorage.getItem('construtec_cart')) || [];

document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    const productGrid = document.getElementById('products-grid');
    if(productGrid) {
        fetchProducts();
    }
});

async function fetchProducts() {
    try {
        const response = await fetch('/api/products');
        const res = await response.json();
        
        if(res.status === 'success') {
            const productsGrid = document.getElementById('products-grid');
            productsGrid.innerHTML = '';
            
            res.data.forEach(p => {
                productsGrid.innerHTML += `
                    <div class="product-card">
                        <img src="${p.image_url}" alt="${p.name}" class="product-image">
                        <div class="product-title">${p.name}</div>
                        <div class="product-price">$${p.price}</div>
                        <button class="btn-primary" onclick="addToCart(${p.id}, '${p.name}', ${p.price}, '${p.image_url}')">Agregar al Carrito</button>
                    </div>
                `;
            });
        }
    } catch (error) {
        console.error("Error cargando productos", error);
    }
}

function addToCart(id, name, price, image) {
    const item = cart.find(i => i.id === id);
    if(item) {
        item.quantity++;
    } else {
        cart.push({ id, name, price, image, quantity: 1 });
    }
    saveCart();
    openCart();
}

function saveCart() {
    localStorage.setItem('construtec_cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

function updateCartCount() {
    const count = cart.reduce((acc, curr) => acc + curr.quantity, 0);
    const badge = document.getElementById('cart-count-badge');
    if(badge) badge.innerText = count;
}

function openCart() {
    document.getElementById('cart-modal').classList.add('active');
    renderCart();
}

function closeCart() {
    document.getElementById('cart-modal').classList.remove('active');
}

function renderCart() {
    const list = document.getElementById('cart-items-list');
    const totalEl = document.getElementById('cart-total-price');
    if(!list) return;

    list.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.quantity;
        list.innerHTML += `
            <div class="cart-item">
                <img src="${item.image}" alt="">
                <div style="flex:1; margin-left: 10px;">
                    <div>${item.name}</div>
                    <div style="font-weight:bold; color:var(--primary)">$${item.price} x ${item.quantity}</div>
                </div>
                <button onclick="removeFromCart(${index})" style="background:none; border:none; color: red; cursor:pointer; font-weight:bold;">X</button>
            </div>
        `;
    });

    totalEl.innerText = `$${total.toFixed(2)}`;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
}

async function doCheckout() {
    if(cart.length === 0) {
        alert("El carrito está vacío");
        return;
    }

    const res = await fetch('/api/orders/checkout', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken 
        },
        body: JSON.stringify({ cart })
    });
    
    try {
        const data = await res.json();
        if(data.status === 'success') {
            alert("Compra realizada con éxito! Tu número de orden es: " + data.order_id);
            cart = [];
            saveCart();
            closeCart();
        } else {
            alert(data.message);
            if(data.message.includes('iniciar sesión')) {
                window.location.href = '/login';
            }
        }
    } catch(e) {
        alert("Ocurrió un error al procesar la compra.");
    }
}
