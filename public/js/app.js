let cart = sanitizeCart(
    JSON.parse(localStorage.getItem("construtec_cart")) || [],
);

document.addEventListener("DOMContentLoaded", () => {
    bindCatalogInteractions();
    bindQuantityControls();
    bindCheckoutInteractions();
    updateCartCount();
    renderCart();
    toggleBillingFields();
});

function sanitizeCart(items) {
    if (!Array.isArray(items)) {
        return [];
    }

    return items
        .map((item) => ({
            id: Number(item.id),
            name: item.name,
            price: Number(item.price),
            image: item.image,
            quantity: Number(item.quantity),
        }))
        .filter(
            (item) =>
                Number.isFinite(item.id) &&
                item.id > 0 &&
                typeof item.name === "string" &&
                item.name.trim() !== "" &&
                Number.isFinite(item.price) &&
                item.price >= 0 &&
                typeof item.image === "string" &&
                item.image.trim() !== "" &&
                Number.isFinite(item.quantity) &&
                item.quantity > 0,
        );
}

function bindCatalogInteractions() {
    document.querySelectorAll(".add-to-cart-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const card = this.closest(".product-card");
            const quantityInput = card
                ? card.querySelector("[data-quantity-input], .qty-input")
                : null;
            const quantity = quantityInput
                ? parseInt(quantityInput.value, 10) || 1
                : 1;
            addToCart(
                parseInt(this.dataset.id, 10),
                this.dataset.name,
                parseFloat(this.dataset.price),
                this.dataset.image,
                quantity,
            );
        });
    });

    const searchInput = document.querySelector("[data-product-search]");
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            const term = searchInput.value.trim().toLowerCase();
            document.querySelectorAll("[data-product-card]").forEach((card) => {
                const name = (card.dataset.productName || "").toLowerCase();
                card.style.display = name.includes(term) ? "" : "none";
            });
        });
    }
}

function bindQuantityControls() {
    document.querySelectorAll(".qty-btn-minus").forEach((btn) => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".product-card");
            const input = card
                ? card.querySelector("[data-quantity-input], .qty-input")
                : null;
            if (!input) {
                return;
            }

            const currentValue = parseInt(input.value, 10) || 1;
            input.value = Math.max(1, currentValue - 1);
        });
    });

    document.querySelectorAll(".qty-btn-plus").forEach((btn) => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".product-card");
            const input = card
                ? card.querySelector("[data-quantity-input], .qty-input")
                : null;
            if (!input) {
                return;
            }

            const currentValue = parseInt(input.value, 10) || 1;
            input.value = currentValue + 1;
        });
    });
}

function bindCheckoutInteractions() {
    const billingToggle = document.getElementById("billing_enabled");
    if (billingToggle) {
        billingToggle.addEventListener("change", toggleBillingFields);
    }
}

function toggleBillingFields() {
    const billingToggle = document.getElementById("billing_enabled");
    const billingFields = document.getElementById("billing-fields");
    const show = billingToggle ? billingToggle.checked : false;

    if (billingFields) {
        billingFields.classList.toggle("hidden", !show);
        const inputs = billingFields.querySelectorAll("input");
        inputs.forEach((input) => {
            input.required = show;
        });
    }
}

function addToCart(id, name, price, image, quantity = 1) {
    const item = cart.find((entry) => entry.id === id);
    if (item) {
        item.quantity += quantity;
    } else {
        cart.push({ id, name, price, image, quantity });
    }

    saveCart();
    openCart();
}

function saveCart() {
    cart = sanitizeCart(cart);
    localStorage.setItem("construtec_cart", JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

function updateCartCount() {
    const badge = document.getElementById("cart-count-badge");
    const count = cart.reduce((total, item) => total + item.quantity, 0);

    if (badge) {
        badge.innerText = count;
    }
}

function openCart() {
    const modal = document.getElementById("cart-modal");
    if (modal) {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    }
    renderCart();
}

function closeCart() {
    const modal = document.getElementById("cart-modal");
    if (modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
}

function changeCartItemQuantity(index, delta) {
    const item = cart[index];
    if (!item) {
        return;
    }

    item.quantity += delta;

    if (item.quantity <= 0) {
        cart.splice(index, 1);
    }

    saveCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
}

function renderCart() {
    const list = document.getElementById("cart-items-list");
    const totalEl = document.getElementById("cart-total-price");
    const subtotalEl = document.getElementById("cart-subtotal-price");
    const orderPreview = document.getElementById("order-preview-count");

    if (!list) {
        return;
    }

    let total = 0;
    list.innerHTML = "";

    if (cart.length === 0) {
        list.innerHTML =
            '<div class="empty-state">Tu carrito está vacío. Agrega productos para construir la orden.</div>';
    }

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        list.insertAdjacentHTML(
            "beforeend",
            `
                <div class="mb-5 flex gap-4 border-b border-slate-200 pb-5 last:mb-0 last:border-b-0 last:pb-0">
                    <img src="${item.image}" alt="${item.name}" class="h-20 w-20 rounded-2xl object-cover ring-1 ring-slate-200">
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-slate-900">${item.name}</div>
                        <div class="mt-1 text-sm text-slate-500">$${Number(item.price).toFixed(2)} por pieza</div>
                        <div class="mt-3 flex items-center gap-2">
                            <button type="button" class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-700 transition hover:border-orange-300 hover:bg-orange-50" onclick="changeCartItemQuantity(${index}, -1)">-</button>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">${item.quantity} piezas</span>
                            <button type="button" class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-700 transition hover:border-orange-300 hover:bg-orange-50" onclick="changeCartItemQuantity(${index}, 1)">+</button>
                        </div>
                    </div>
                    <div class="flex flex-col items-end justify-between">
                        <strong class="text-sm font-bold text-slate-900">$${itemTotal.toFixed(2)}</strong>
                        <button type="button" class="rounded-full border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 transition hover:bg-rose-50" onclick="removeFromCart(${index})">Eliminar</button>
                    </div>
                </div>
            `,
        );
    });

    if (totalEl) {
        totalEl.innerText = `$${total.toFixed(2)}`;
    }

    if (subtotalEl) {
        subtotalEl.innerText = `$${total.toFixed(2)}`;
    }

    if (orderPreview) {
        orderPreview.innerText = `${cart.reduce((totalItems, item) => totalItems + item.quantity, 0)} artículos`;
    }
}

function collectCheckoutPayload() {
    const form = document.getElementById("checkout-form");
    if (!form) {
        return null;
    }

    const payload = {
        cart,
        discount_code: form
            .querySelector('[name="discount_code"]')
            ?.value.trim(),
        notes: form.querySelector('[name="notes"]')?.value.trim(),
        billing_enabled: form.querySelector('[name="billing_enabled"]')?.checked
            ? 1
            : 0,
        rfc: form.querySelector('[name="rfc"]')?.value.trim(),
        business_name: form
            .querySelector('[name="business_name"]')
            ?.value.trim(),
        tax_regime: form.querySelector('[name="tax_regime"]')?.value.trim(),
        billing_postal_code: form
            .querySelector('[name="billing_postal_code"]')
            ?.value.trim(),
        fiscal_address: form
            .querySelector('[name="fiscal_address"]')
            ?.value.trim(),
        cfdi_usage: form.querySelector('[name="cfdi_usage"]')?.value.trim(),
    };

    return payload;
}

async function doCheckout() {
    if (cart.length === 0) {
        alert("El carrito está vacío.");
        return;
    }

    const payload = collectCheckoutPayload();
    if (!payload) {
        alert("No se encontró el formulario de pedido.");
        return;
    }

    const checkoutButton = document.getElementById("checkout-submit-button");
    if (checkoutButton) {
        checkoutButton.disabled = true;
        checkoutButton.innerText = "Generando orden...";
    }

    try {
        const response = await fetch("/orders/checkout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();
        if (data.status === "success") {
            alert(
                `Orden generada: ${data.order_number}\nTotal: $${data.total}`,
            );
            cart = [];
            saveCart();
            const form = document.getElementById("checkout-form");
            if (form) {
                form.reset();
            }
            toggleBillingFields();
            closeCart();
        } else {
            alert(data.message || "Ocurrió un error al procesar la orden.");
        }
    } catch (error) {
        alert("Ocurrió un error al procesar la orden.");
    } finally {
        if (checkoutButton) {
            checkoutButton.disabled = false;
            checkoutButton.innerText = "Generar orden";
        }
    }
}

window.addToCart = addToCart;
window.openCart = openCart;
window.closeCart = closeCart;
window.removeFromCart = removeFromCart;
window.changeCartItemQuantity = changeCartItemQuantity;
window.doCheckout = doCheckout;
window.toggleBillingFields = toggleBillingFields;
