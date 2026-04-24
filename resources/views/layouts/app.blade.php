<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConstruTec - Tu Ferretería de Confianza</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .nav-links form { margin: 0; }
        .nav-links button { background: none; border: none; color: white; cursor: pointer; font-size: 0.9rem; }
        .nav-links button:hover { color: var(--primary); }
    </style>
</head>
<body>
    <header>
        <a href="{{ url('/') }}" class="logo"><img src="{{ asset('images/logo.png') }}" alt="ConstruTec"></a>
        <div class="search-bar">
            <input type="text" placeholder="Buscar productos (Ej. Martillo)...">
            <button>Buscar</button>
        </div>
        <div class="nav-links">
            <div id="nav-auth-links" style="display: flex; gap: 1rem; align-items: center;">
                @auth
                    <span>Hola, <strong>{{ Auth::user()->name }}</strong></span>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ url('/admin') }}">Panel Admin</a>
                    @endif
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Iniciar Sesión</a>
                @endauth
            </div>
            <div class="cart-icon" onclick="openCart()">
                🛒 <span style="color: white">Carrito</span> <span id="cart-count-badge" class="cart-count">0</span>
            </div>
        </div>
    </header>

    <!-- Cart Modal -->
    <div id="cart-modal" class="cart-modal">
        <div class="cart-header">
            <h3>Tu Carrito</h3>
            <button class="close-cart" onclick="closeCart()">&times;</button>
        </div>
        <div class="cart-items" id="cart-items-list"></div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cart-total-price">$0.00</span>
            </div>
            <button class="btn-primary" style="width: 100%" onclick="doCheckout()">Finalizar Compra</button>
        </div>
    </div>

    @yield('content')

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
