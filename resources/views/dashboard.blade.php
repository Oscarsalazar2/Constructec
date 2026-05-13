@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Catálogo de Productos</h2>
            <p class="mx-auto mt-3 max-w-2xl text-sm text-slate-600 sm:text-base">Vista rápida del catálogo para usuarios
                autenticados.</p>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($products as $product)
                <article
                    class="product-card overflow-hidden rounded-3xl bg-white shadow-[0_12px_30px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 transition hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(15,23,42,0.14)]"
                    data-product-card
                    data-product-name="{{ strtolower($product->name . ' ' . ($product->description ?? '')) }}">
                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}"
                        class="h-56 w-full object-cover transition duration-300 hover:scale-105">
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-slate-900">{{ $product->name }}</h3>
                        <div class="mt-1 text-2xl font-extrabold text-orange-500">${{ number_format($product->price, 2) }}
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Stock: {{ $product->stock }} uds.</p>
                        <button
                            class="add-to-cart-btn mt-4 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5 hover:shadow-orange-500/40"
                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}" data-image="{{ asset($product->image_url) }}">Agregar al
                            Carrito</button>
                    </div>
                </article>
            @empty
                <div
                    class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
                    No hay productos disponibles.</div>
            @endforelse
        </div>
    </div>
@endsection
