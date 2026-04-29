@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-2 text-center" style="font-size: 2rem; color: var(--dark);">Catálogo de Productos</h2>
        <div id="products-grid" class="products-grid">
            @forelse ($products as $product)
                <div class="product-card">
                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="product-image">
                    <div class="product-title">{{ $product->name }}</div>
                    <div class="product-price">${{ number_format($product->price, 2) }}</div>
                    <div style="margin-bottom: 1rem; color: var(--gray-mid);">Stock: {{ $product->stock }} uds.</div>
                    <button class="btn-primary add-to-cart-btn" data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}" data-price="{{ $product->price }}"
                        data-image="{{ asset($product->image_url) }}">
                        Agregar al Carrito
                    </button>
                </div>
            @empty
                <div class="text-center" style="grid-column: 1 / -1; padding: 3rem;">
                    No hay productos disponibles.
                </div>
            @endforelse
        </div>
    </div>
@endsection
