@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-2 text-center" style="font-size: 2rem; color: var(--dark);">Catálogo de Productos</h2>
    <div id="products-grid" class="products-grid">
        <div class="text-center" style="grid-column: 1 / -1; padding: 3rem;">
            Cargando productos de inventario...
        </div>
    </div>
</div>
@endsection
