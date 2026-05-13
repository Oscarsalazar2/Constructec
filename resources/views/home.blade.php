@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section
            class="relative overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#1a2332_0%,#2D3436_52%,#1E272E_100%)] px-6 py-16 text-center text-white shadow-[0_30px_80px_rgba(15,23,42,0.24)] sm:px-10 lg:py-20">
            <div
                class="pointer-events-none absolute -right-24 -top-24 h-[28rem] w-[28rem] rounded-full bg-[radial-gradient(circle,rgba(255,149,0,0.14)_0%,transparent_70%)]">
            </div>
            <div
                class="pointer-events-none absolute -left-10 -bottom-20 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(255,149,0,0.09)_0%,transparent_70%)]">
            </div>

            <div class="relative mx-auto max-w-5xl">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-orange-200 sm:text-sm">Dashboard</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Tu Catálogo de
                    Productos</h1>
                <p class="mx-auto mt-5 max-w-3xl text-base leading-8 text-slate-200 sm:text-lg">Selecciona y gestiona tus
                    productos favoritos. Agrega cantidades, aplica descuentos y genera órdenes con facturación automática.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="#catalogo"
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5">Ver
                        Catálogo</a>
                    <button
                        class="inline-flex items-center justify-center rounded-xl border border-orange-300/80 bg-white/10 px-6 py-3 text-sm font-semibold text-orange-100 backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15"
                        onclick="openCart()">Abrir Carrito</button>
                </div>

                <div class="mx-auto mt-10 grid max-w-4xl gap-4 border-t border-white/15 pt-8 sm:grid-cols-3">
                    <div class="rounded-2xl bg-white/5 px-5 py-4 backdrop-blur-sm transition hover:-translate-y-1">
                        <div class="text-3xl font-extrabold text-orange-200">{{ count($products) }}</div>
                        <div class="mt-1 text-sm text-slate-200">Productos disponibles</div>
                    </div>
                    <div class="rounded-2xl bg-white/5 px-5 py-4 backdrop-blur-sm transition hover:-translate-y-1">
                        <div class="text-3xl font-extrabold text-orange-200">✓</div>
                        <div class="mt-1 text-sm text-slate-200">Autenticado y listo</div>
                    </div>
                    <div class="rounded-2xl bg-white/5 px-5 py-4 backdrop-blur-sm transition hover:-translate-y-1">
                        <div class="text-3xl font-extrabold text-orange-200">⚡</div>
                        <div class="mt-1 text-sm text-slate-200">Compra rápida y segura</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-8 grid gap-6 lg:grid-cols-3">
            <div
                class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-100 text-4xl">📦
                </div>
                <h2 class="text-lg font-bold text-slate-900">Selecciona productos</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Agrega piezas individuales o por mayoreo con precios
                    automáticos y descuentos según volumen.</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-100 text-4xl">📋
                </div>
                <h2 class="text-lg font-bold text-slate-900">Captura datos</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Tus datos fiscales ya están guardados en tu perfil durante
                    el registro.</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-100 text-4xl">✅
                </div>
                <h2 class="text-lg font-bold text-slate-900">Genera la orden</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Se crea el número de orden, total calculado y factura
                    automática en PDF.</p>
            </div>
        </section>

        <section id="catalogo" class="mt-8 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Productos Disponibles</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm text-slate-600 sm:text-base">Usa la búsqueda del encabezado para
                    filtrar el catálogo en tiempo real.</p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @forelse ($products as $product)
                    @php
                        $productImage = $product->image_url ? asset($product->image_url) : asset('images/logo.png');
                    @endphp
                    <article
                        class="product-card overflow-hidden rounded-3xl bg-slate-50 shadow-[0_12px_30px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 transition hover:-translate-y-2 hover:bg-white hover:shadow-[0_20px_50px_rgba(15,23,42,0.14)]"
                        data-product-card
                        data-product-name="{{ strtolower($product->name . ' ' . ($product->description ?? '')) }}">
                        <div class="relative">
                            <span
                                class="absolute right-3 top-3 rounded-full bg-slate-950/80 px-3 py-1 text-xs font-semibold text-white shadow-lg">Stock
                                {{ $product->stock }}</span>
                            <img src="{{ $productImage }}" alt="{{ $product->name }}"
                                class="h-56 w-full object-cover transition duration-300 hover:scale-105">
                        </div>
                        <div class="p-5">
                            <div class="text-xs font-bold uppercase tracking-[0.18em] text-orange-500">
                                {{ $product->description ? 'Con descripción' : 'Producto' }}</div>
                            <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $product->name }}</h3>
                            <div class="mt-1 text-2xl font-extrabold text-orange-500">
                                ${{ number_format($product->price, 2) }}</div>
                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                {{ $product->description ?: 'Producto de calidad para tus proyectos de construcción.' }}</p>
                            <p class="mt-3 text-xs font-medium text-slate-400">📊 No tiene precio de mayoreo definido.</p>
                            <div class="mt-4 flex items-center justify-center gap-2">
                                <button type="button"
                                    class="qty-btn-minus inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-lg font-semibold text-slate-700 transition hover:border-orange-300 hover:bg-orange-50"
                                    data-id="{{ $product->id }}">−</button>
                                <input type="number"
                                    class="qty-input w-16 rounded-xl border border-slate-200 px-2 py-2 text-center text-sm font-semibold text-slate-700 outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                    data-id="{{ $product->id }}" value="1" min="1">
                                <button type="button"
                                    class="qty-btn-plus inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-lg font-semibold text-slate-700 transition hover:border-orange-300 hover:bg-orange-50"
                                    data-id="{{ $product->id }}">+</button>
                            </div>
                            <button
                                class="add-to-cart-btn mt-4 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5 hover:shadow-orange-500/40"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}" data-image="{{ asset($product->image_url) }}">Agregar
                                al Carrito</button>
                        </div>
                    </article>
                @empty
                    <div
                        class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
                        No hay productos disponibles.</div>
                @endforelse
            </div>
        </section>
    </div>

@endsection
