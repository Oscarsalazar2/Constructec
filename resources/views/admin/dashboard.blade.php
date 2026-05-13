@extends('layouts.app')

@section('content')
    <div class="container admin-shell">
        <section class="admin-card admin-hero">
            <div>
                <span class="eyebrow">Panel de administrador</span>
                <h1 class="admin-title" style="margin-top: .5rem;">Control total de tienda, órdenes, descuentos y facturación.
                </h1>
                <p class="admin-copy" style="margin-top: .75rem; max-width: 72ch;">
                    Desde aquí se administran usuarios, catálogo, precios de mayoreo, descuentos y el seguimiento de pedidos
                    con datos fiscales.
                </p>
            </div>
            <div class="section-actions">
                <a class="btn-primary" href="{{ url('/') }}">Ver tienda</a>
                <a class="btn-secondary" href="#admin-products">Ir a productos</a>
            </div>
        </section>

        <div class="metric-grid">
            <div class="metric-card"><strong>{{ $totalProducts }}</strong><span class="muted">Productos</span></div>
            <div class="metric-card"><strong>{{ $totalOrders }}</strong><span class="muted">Órdenes</span></div>
            <div class="metric-card"><strong>{{ $totalUsers }}</strong><span class="muted">Usuarios</span></div>
            <div class="metric-card"><strong>{{ $totalDiscounts }}</strong><span class="muted">Descuentos</span></div>
            <div class="metric-card"><strong>${{ number_format($totalRevenue, 2) }}</strong><span
                    class="muted">Ingresos</span></div>
        </div>

        <div class="admin-layout">
            <aside class="admin-card admin-sidebar">
                <a href="#admin-products">Productos <span>→</span></a>
                <a href="#admin-discounts">Descuentos <span>→</span></a>
                <a href="#admin-users">Usuarios <span>→</span></a>
                <a href="#admin-orders">Órdenes <span>→</span></a>
                <a href="#admin-billing">Facturación <span>→</span></a>
            </aside>

            <div class="admin-content">
                <section id="admin-products" class="admin-section">
                    <div class="admin-section-header">
                        <div>
                            <span class="eyebrow">Catálogo</span>
                            <h2 class="section-title" style="margin-top: .4rem;">Productos y precios de mayoreo</h2>
                        </div>
                    </div>

                    <div class="surface-card">
                        <h3 class="mb-2">Nuevo producto</h3>
                        <form class="mini-form" action="{{ route('admin.products.store') }}" method="POST">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="field-label">Nombre</label>
                                    <input class="form-input" type="text" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="field-label">Precio</label>
                                    <input class="form-input" type="number" step="0.01" min="0" name="price"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="field-label">Stock</label>
                                    <input class="form-input" type="number" min="0" name="stock" required>
                                </div>
                                <div class="form-group">
                                    <label class="field-label">Imagen URL</label>
                                    <input class="form-input" type="text" name="image_url"
                                        placeholder="images/producto.jpg">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="field-label">Descripción</label>
                                <textarea class="form-textarea" name="description"></textarea>
                            </div>
                            <button class="btn-primary" type="submit">Guardar producto</button>
                        </form>
                    </div>

                    <div class="card-grid">
                        @foreach ($products as $product)
                            @php
                                $productImage = $product->image_url
                                    ? asset($product->image_url)
                                    : asset('images/logo.png');
                            @endphp
                            <details class="admin-card admin-product-card">
                                <summary>{{ $product->name }} <span class="muted">#{{ $product->id }}</span></summary>
                                <img src="{{ $productImage }}" alt="{{ $product->name }}"
                                    style="width: 100%; height: 180px; object-fit: contain; border-radius: 18px; background: rgba(245,239,232,.6); padding: 1rem;">
                                <div class="inline-meta">
                                    <span class="pill">Stock: {{ $product->stock }}</span>
                                    <span class="pill warn">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <p class="muted">{{ $product->description ?: 'Sin descripción.' }}</p>

                                <div class="wholesale-list">
                                    @forelse($product->wholesalePrices as $tier)
                                        <div class="wholesale-row">
                                            <span>De {{ $tier->min_quantity }} @if ($tier->max_quantity)
                                                    a {{ $tier->max_quantity }}
                                                @else
                                                    o más
                                                @endif
                                            </span>
                                            <form action="{{ route('admin.products.wholesale.destroy', $tier) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-ghost" type="submit">Eliminar</button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="helper">Sin escalas de mayoreo registradas.</div>
                                    @endforelse
                                </div>

                                <form class="mini-form" action="{{ route('admin.products.update', $product) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="field-label">Nombre</label>
                                        <input class="form-input" type="text" name="name"
                                            value="{{ $product->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="field-label">Descripción</label>
                                        <textarea class="form-textarea" name="description">{{ $product->description }}</textarea>
                                    </div>
                                    <div class="split-fields">
                                        <div class="form-group">
                                            <label class="field-label">Precio</label>
                                            <input class="form-input" type="number" step="0.01" min="0"
                                                name="price" value="{{ $product->price }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="field-label">Stock</label>
                                            <input class="form-input" type="number" min="0" name="stock"
                                                value="{{ $product->stock }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="field-label">Imagen URL</label>
                                        <input class="form-input" type="text" name="image_url"
                                            value="{{ $product->image_url }}">
                                    </div>
                                    <div class="section-actions">
                                        <button class="btn-primary" type="submit">Actualizar</button>
                                    </div>
                                </form>

                                <form class="mini-form" action="{{ route('admin.products.wholesale.store', $product) }}"
                                    method="POST">
                                    @csrf
                                    <h4>Agregar precio de mayoreo</h4>
                                    <div class="split-fields">
                                        <div class="form-group">
                                            <label class="field-label">Cantidad mínima</label>
                                            <input class="form-input" type="number" min="1" name="min_quantity"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label class="field-label">Cantidad máxima</label>
                                            <input class="form-input" type="number" min="1" name="max_quantity">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="field-label">Precio mayoreo</label>
                                        <input class="form-input" type="number" step="0.01" min="0"
                                            name="price" required>
                                    </div>
                                    <button class="btn-secondary" type="submit">Guardar mayoreo</button>
                                </form>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Eliminar producto</button>
                                </form>
                            </details>
                        @endforeach
                    </div>
                </section>

                <section id="admin-discounts" class="admin-section">
                    <div class="admin-section-header">
                        <div>
                            <span class="eyebrow">Promociones</span>
                            <h2 class="section-title" style="margin-top: .4rem;">Descuentos por porcentaje o monto fijo
                            </h2>
                        </div>
                    </div>

                    <div class="surface-card">
                        <form class="mini-form" action="{{ route('admin.discounts.store') }}" method="POST">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="field-label">Código</label>
                                    <input class="form-input" type="text" name="code" required>
                                </div>
                                <div class="form-group">
                                    <label class="field-label">Tipo</label>
                                    <select class="form-select" name="type" required>
                                        <option value="percentage">Porcentaje</option>
                                        <option value="fixed">Monto fijo</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="field-label">Valor</label>
                                    <input class="form-input" type="number" step="0.01" min="0"
                                        name="value" required>
                                </div>
                                <div class="form-group">
                                    <label class="field-label checkbox-row" for="discount_active_new">
                                        <input id="discount_active_new" type="checkbox" name="is_active" value="1"
                                            checked>
                                        Descuento activo
                                    </label>
                                </div>
                            </div>
                            <button class="btn-primary" type="submit">Guardar descuento</button>
                        </form>
                    </div>

                    <div class="card-grid">
                        @foreach ($discounts as $discount)
                            <details class="admin-card admin-record-card">
                                <summary>{{ $discount->code }}</summary>
                                <div class="inline-meta">
                                    <span
                                        class="pill">{{ $discount->type === 'percentage' ? 'Porcentaje' : 'Monto fijo' }}</span>
                                    <span class="pill warn">{{ $discount->value }}</span>
                                    <span
                                        class="pill {{ $discount->is_active ? '' : 'danger' }}">{{ $discount->is_active ? 'Activo' : 'Inactivo' }}</span>
                                </div>

                                <form class="mini-form" action="{{ route('admin.discounts.update', $discount) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="split-fields">
                                        <div class="form-group">
                                            <label class="field-label">Código</label>
                                            <input class="form-input" type="text" name="code"
                                                value="{{ $discount->code }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="field-label">Valor</label>
                                            <input class="form-input" type="number" step="0.01" min="0"
                                                name="value" value="{{ $discount->value }}" required>
                                        </div>
                                    </div>
                                    <div class="split-fields">
                                        <div class="form-group">
                                            <label class="field-label">Tipo</label>
                                            <select class="form-select" name="type" required>
                                                <option value="percentage" @selected($discount->type === 'percentage')>Porcentaje</option>
                                                <option value="fixed" @selected($discount->type === 'fixed')>Monto fijo</option>
                                            </select>
                                        </div>
                                        <label class="field-label checkbox-row" style="align-self: end;"
                                            for="discount_active_{{ $discount->id }}">
                                            <input id="discount_active_{{ $discount->id }}" type="checkbox"
                                                name="is_active" value="1" @checked($discount->is_active)>
                                            Activo
                                        </label>
                                    </div>
                                    <button class="btn-primary" type="submit">Actualizar</button>
                                </form>

                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este descuento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Eliminar descuento</button>
                                </form>
                            </details>
                        @endforeach
                    </div>
                </section>

                <section id="admin-users" class="admin-section">
                    <div class="admin-section-header">
                        <div>
                            <span class="eyebrow">Usuarios</span>
                            <h2 class="section-title" style="margin-top: .4rem;">Gestión de cuentas y roles</h2>
                        </div>
                    </div>

                    <div class="card-grid">
                        @foreach ($users as $user)
                            <details class="admin-card admin-record-card">
                                <summary>{{ $user->name }} <span class="muted">{{ $user->email }}</span></summary>
                                <div class="inline-meta">
                                    <span class="pill">{{ $user->role }}</span>
                                </div>

                                <form class="mini-form" action="{{ route('admin.users.update', $user) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="field-label">Nombre</label>
                                        <input class="form-input" type="text" name="name"
                                            value="{{ $user->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="field-label">Correo</label>
                                        <input class="form-input" type="email" name="email"
                                            value="{{ $user->email }}" required>
                                    </div>
                                    <div class="split-fields">
                                        <div class="form-group">
                                            <label class="field-label">Rol</label>
                                            <select class="form-select" name="role" required>
                                                <option value="user" @selected($user->role === 'user')>Usuario</option>
                                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="field-label">Nueva contraseña</label>
                                            <input class="form-input" type="password" name="password"
                                                placeholder="Opcional">
                                        </div>
                                    </div>
                                    <button class="btn-primary" type="submit">Guardar cambios</button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Eliminar usuario</button>
                                </form>
                            </details>
                        @endforeach
                    </div>
                </section>

                <section id="admin-orders" class="admin-section">
                    <div class="admin-section-header">
                        <div>
                            <span class="eyebrow">Órdenes</span>
                            <h2 class="section-title" style="margin-top: .4rem;">Seguimiento de pedidos</h2>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th>Total</th>
                                    <th>Estatus</th>
                                    <th>Factura</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong><br>
                                            <span class="muted">#{{ $order->id }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <span class="muted">{{ $order->customer_address }}</span>
                                        </td>
                                        <td>
                                            {{ $order->customer_phone }}<br>
                                            <span class="muted">{{ $order->customer_email }}</span>
                                        </td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td><span
                                                class="pill {{ $order->status === 'cancelled' ? 'danger' : 'warn' }}">{{ $order->status }}</span>
                                        </td>
                                        <td>
                                            @if ($order->billingDetail)
                                                <span class="pill">{{ $order->billingDetail->invoice_status }}</span>
                                            @else
                                                <span class="pill danger">Sin factura</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.orders.status', $order) }}" method="POST"
                                                class="mini-form">
                                                @csrf
                                                @method('PUT')
                                                <select class="form-select" name="status">
                                                    <option value="pending" @selected($order->status === 'pending')>Pendiente</option>
                                                    <option value="processing" @selected($order->status === 'processing')>Procesando
                                                    </option>
                                                    <option value="ready" @selected($order->status === 'ready')>Listo</option>
                                                    <option value="completed" @selected($order->status === 'completed')>Completado
                                                    </option>
                                                    <option value="cancelled" @selected($order->status === 'cancelled')>Cancelado
                                                    </option>
                                                </select>
                                                <button class="btn-secondary" type="submit">Actualizar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <section id="admin-billing" class="admin-section">
                    <div class="admin-section-header">
                        <div>
                            <span class="eyebrow">Facturación</span>
                            <h2 class="section-title" style="margin-top: .4rem;">Datos fiscales capturados</h2>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>RFC</th>
                                    <th>Razón social</th>
                                    <th>Régimen</th>
                                    <th>Código postal</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($billingDetails as $billing)
                                    <tr>
                                        <td>{{ $billing->order?->order_number }}</td>
                                        <td>{{ $billing->rfc }}</td>
                                        <td>{{ $billing->business_name }}</td>
                                        <td>{{ $billing->tax_regime }}</td>
                                        <td>{{ $billing->postal_code }}</td>
                                        <td><span class="pill">{{ $billing->invoice_status }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aún no hay datos fiscales registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
