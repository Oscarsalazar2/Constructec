<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConstruTec - Tu Ferretería de Confianza</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body
    class="min-h-screen min-w-screen bg-[radial-gradient(circle_at_top_left,rgba(229,107,32,0.12),transparent_28%),radial-gradient(circle_at_top_right,rgba(15,118,110,0.08),transparent_24%),linear-gradient(180deg,#fffaf4_0%,#f3efe8_100%)] font-sans text-slate-800">
    <header
        class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 text-white shadow-[0_20px_50px_rgba(15,23,42,0.28)] backdrop-blur-xl">
        <div
            class="mx-auto flex w-full max-w-7xl flex-col gap-4 px-4 py-4 lg:flex-row lg:items-center lg:justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 self-start">
                <img src="{{ asset('images/logo.png') }}" alt="ConstruTec"
                    class="h-12 w-auto transition duration-300 hover:scale-105">
            </a>
            <div class="flex w-full flex-1 items-center gap-2 lg:max-w-xl">
                <input type="text" placeholder="Buscar productos (Ej. Martillo)..." data-product-search
                    class="w-full rounded-full border border-white/10 bg-white/95 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15">
                <button
                    class="rounded-full bg-gradient-to-r from-orange-500 to-orange-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5 hover:shadow-orange-500/40">Buscar</button>
            </div>
            <div class="flex items-center gap-4 text-sm font-medium text-white/90">
                @auth
                    <span class="hidden md:inline">Hola, <strong
                            class="font-semibold text-white">{{ Auth::user()->name }}</strong></span>
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ url('/admin') }}"
                            class="rounded-full px-3 py-2 transition hover:bg-white/10 hover:text-orange-300">Panel
                            Admin</a>
                    @endif
                    <form action="{{ url('/logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit"
                            class="rounded-full px-3 py-2 transition hover:bg-white/10 hover:text-orange-300">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-full px-3 py-2 transition hover:bg-white/10 hover:text-orange-300">Iniciar Sesión</a>
                    <a href="{{ route('register') }}"
                        class="rounded-full px-3 py-2 transition hover:bg-white/10 hover:text-orange-300">Registrarse</a>
                @endauth
                <button type="button" onclick="openCart()"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 transition hover:bg-white/10 hover:text-orange-300">
                    <i class="fa-solid fa-cart-shopping fa-spin"></i>
                    <span>Carrito</span>
                    <span id="cart-count-badge"
                        class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white shadow-lg shadow-orange-500/30">0</span>
                </button>
            </div>
        </div>
    </header>

    <div id="cart-modal" class="cart-overlay">
        <div class="cart-drawer">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4 md:px-6">
                <h3 class="text-lg font-bold text-slate-900">Tu Carrito</h3>
                <button
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full text-2xl text-slate-500 transition hover:bg-slate-200 hover:text-slate-900"
                    onclick="closeCart()">&times;</button>
            </div>
            <div class="flex-1 overflow-y-auto p-5 md:p-6">
                <div id="cart-items-list"></div>
                <div class="mt-8">
                    <h4 class="mb-4 text-base font-bold text-slate-900">Confirmar Orden</h4>
                    @auth
                        <form id="checkout-form" onsubmit="event.preventDefault(); doCheckout();" class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700" for="discount_code">Código de
                                    descuento</label>
                                <input id="discount_code" name="discount_code"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                    type="text" placeholder="(Opcional)">
                            </div>
                            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <input id="billing_enabled" name="billing_enabled" type="checkbox"
                                    onchange="toggleBillingFields()"
                                    class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                <label class="text-sm font-semibold text-slate-700" for="billing_enabled">Generar
                                    factura</label>
                            </div>
                            <div id="billing-fields"
                                class="hidden space-y-4 rounded-3xl border border-orange-100 bg-orange-50/60 p-4">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700"
                                        for="rfc">RFC</label>
                                    <input id="rfc" name="rfc"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                        type="text" placeholder="RFC">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="business_name">Razón
                                        Social</label>
                                    <input id="business_name" name="business_name"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                        type="text">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="tax_regime">Régimen
                                        Fiscal</label>
                                    <input id="tax_regime" name="tax_regime"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                        type="text">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700"
                                        for="billing_postal_code">CP Fiscal</label>
                                    <input id="billing_postal_code" name="billing_postal_code"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                        type="text">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700"
                                        for="fiscal_address">Domicilio Fiscal</label>
                                    <input id="fiscal_address" name="fiscal_address"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                        type="text">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="cfdi_usage">Uso
                                        CFDI</label>
                                    <input id="cfdi_usage" name="cfdi_usage"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                        type="text">
                                </div>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700"
                                    for="notes">Notas</label>
                                <textarea id="notes" name="notes"
                                    class="min-h-24 w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                                    placeholder="Notas adicionales"></textarea>
                            </div>
                        </form>
                    @else
                        <div
                            class="rounded-3xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100 p-6 text-center">
                            <p class="text-slate-800"><strong>Debes iniciar sesión</strong> para completar tu compra.</p>
                            <a href="{{ route('login') }}"
                                class="mt-4 inline-flex rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5">Iniciar
                                Sesión</a>
                        </div>
                    @endauth
                </div>
            </div>
            <div class="border-t border-slate-200 bg-slate-50 px-5 py-4 md:px-6">
                <div class="mb-3 flex items-center justify-between text-sm font-semibold text-slate-700">
                    <span>Subtotal:</span>
                    <span id="cart-subtotal-price">$0.00</span>
                </div>
                <div class="mb-4 flex items-center justify-between text-lg font-bold text-slate-900">
                    <span>Total:</span>
                    <span id="cart-total-price">$0.00</span>
                </div>
                <button id="checkout-submit-button" onclick="doCheckout()"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5 hover:shadow-orange-500/40">Generar
                    Orden</button>
            </div>
        </div>
    </div>

    <main class="mx-auto w-full max-w-7xl px-0">
        @yield('content')
    </main>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
