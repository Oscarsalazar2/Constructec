<section class="hero">
    <div class="hero-panel">
        <span class="eyebrow">Pedidos sin pago en línea</span>
        <h1>{{ $headline }}</h1>
        <p>{{ $description }}</p>

        <div class="hero-actions" style="margin-top: 1.4rem;">
            <a href="#catalogo" class="btn-primary">Explorar catálogo</a>
            <button type="button" class="btn-secondary" onclick="openCart()">Abrir carrito</button>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <strong>{{ number_format($products->count()) }}</strong>
                <span class="muted">Productos activos</span>
            </div>
            <div class="hero-stat">
                <strong>Folio</strong>
                <span class="muted">Cada pedido genera orden</span>
            </div>
            <div class="hero-stat">
                <strong>PDF</strong>
                <span class="muted">Factura o pre-factura</span>
            </div>
        </div>
    </div>

    <div class="hero-side">
        <div class="surface-card feature-card">
            <div class="eyebrow">Flujo de compra</div>
            <div class="feature-grid" style="margin-top: 1rem;">
                <div class="feature-row">
                    <div class="feature-icon"><i class="fa-solid fa-cart-plus"></i></div>
                    <div>
                        <strong>1. Selecciona productos</strong>
                        <div class="muted">Agrega piezas individuales o por mayoreo.</div>
                    </div>
                </div>
                <div class="feature-row">
                    <div class="feature-icon"><i class="fa-solid fa-file-signature"></i></div>
                    <div>
                        <strong>2. Captura datos</strong>
                        <div class="muted">Guarda nombre, dirección, contacto y datos fiscales.</div>
                    </div>
                </div>
                <div class="feature-row">
                    <div class="feature-icon"><i class="fa-solid fa-receipt"></i></div>
                    <div>
                        <strong>3. Genera la orden</strong>
                        <div class="muted">Se crea el número de orden y el total calculado.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="surface-card feature-card">
            <div class="eyebrow">Control comercial</div>
            <p class="section-copy" style="margin-top: 0.85rem;">
                Administra usuarios, productos, descuentos, precios de mayoreo y facturación desde un solo panel.
            </p>
            <div class="section-actions" style="margin-top: 1rem;">
                @auth
                    @if (Auth::user()->role === 'admin')
                        <a class="btn-primary" href="{{ url('/admin') }}">Ir al panel admin</a>
                    @endif
                @endauth
                <button type="button" class="btn-ghost" onclick="openCart()">Revisar carrito</button>
            </div>
        </div>
    </div>
</section>

<section id="catalogo">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Catálogo</span>
            <h2 class="section-title" style="margin-top: .45rem;">Productos disponibles</h2>
        </div>
        <p class="section-copy">Usa la búsqueda del encabezado para filtrar el catálogo en tiempo real.</p>
    </div>

    <div class="products-grid">
        @forelse ($products as $product)
            @php
                $productImage = $product->image_url ? asset($product->image_url) : asset('images/logo.png');
            @endphp
            <article class="product-card" data-product-card
                data-product-name="{{ strtolower($product->name . ' ' . ($product->description ?? '')) }}">
                <div class="product-media">
                    <span class="product-badge">Stock {{ $product->stock }}</span>
                    <img src="{{ $productImage }}" alt="{{ $product->name }}" class="product-image">
                </div>
                <div class="inline-meta">
                    <span class="pill">{{ $product->description ? 'Con descripción' : 'Producto' }}</span>
                    @if ($product->wholesalePrices->isNotEmpty())
                        <span class="pill warn">Mayoreo activo</span>
                    @endif
                </div>
                <div class="product-title">{{ $product->name }}</div>
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
                <div class="product-info">
                    {{ $product->description ?: 'Sin descripción registrada.' }}
                </div>

                <div class="wholesale-list">
                    @forelse($product->wholesalePrices as $tier)
                        <div class="wholesale-row">
                            <span>De {{ $tier->min_quantity }} @if ($tier->max_quantity)
                                    a {{ $tier->max_quantity }}
                                @else
                                    o más
                                @endif
                            </span>
                            <strong>${{ number_format($tier->price, 2) }}</strong>
                        </div>
                    @empty
                        <div class="helper">No tiene precio de mayoreo definido.</div>
                    @endforelse
                </div>

                <div class="qty-control">
                    <button type="button"
                        onclick="const input=this.parentElement.querySelector('[data-quantity-input]'); input.stepDown(); if (parseInt(input.value,10) < 1) input.value = 1;">-</button>
                    <input data-quantity-input type="number" min="1" value="1" class="form-input"
                        aria-label="Cantidad">
                    <button type="button"
                        onclick="const input=this.parentElement.querySelector('[data-quantity-input]'); input.stepUp();">+</button>
                </div>

                <button class="btn-primary add-to-cart-btn" data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}" data-price="{{ $product->price }}"
                    data-image="{{ $productImage }}">
                    Agregar al carrito
                </button>
            </article>
        @empty
            <div class="surface-card" style="grid-column: 1 / -1; padding: 2rem; text-align: center;">
                No hay productos disponibles.
            </div>
        @endforelse
    </div>
</section>
