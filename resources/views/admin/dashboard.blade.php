@extends('layouts.app')
@section('content')
    <div class="container">
        <h2 class="mb-2">Dashboard General</h2>
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total Productos</h3>
                <p>{{ $totalProducts }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Órdenes</h3>
                <p>{{ $totalOrders }}</p>
            </div>
            <div class="stat-card">
                <h3>Ingresos Totales</h3>
                <p>${{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>

        <h3 class="mb-2">Productos en Base de Datos</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Precio</th>
            </tr>
            @foreach($recentProducts as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td><img src="{{ asset($p->image_url) }}" alt="" style="width: 50px; border-radius:4px;"></td>
                <td><strong>{{ $p->name }}</strong></td>
                <td>{{ $p->stock }} uds.</td>
                <td style="color:var(--primary); font-weight: bold;">${{ number_format($p->price, 2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection

