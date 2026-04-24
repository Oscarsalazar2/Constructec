@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2 class="text-center mb-2" style="color: var(--primary);">Bienvenido a ConstruTec</h2>
    <p class="text-center mb-2" style="color: var(--gray-mid); font-size: 0.9em;">Inicia sesión para comprar.</p>
    <form id="login-form">
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" id="email" class="form-control" placeholder="admin@construtec.com" required>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" id="password" class="form-control" placeholder="admin123" required>
        </div>
        <button type="submit" class="btn-primary" style="width:100%; font-size: 1.1rem; padding: 1rem;">Iniciar Sesión</button>
    </form>
    <div id="login-error" style="color: red; margin-top: 15px; text-align: center; font-weight: bold;"></div>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ email, password })
    });
    
    try {
        const data = await res.json();
        if(data.status === 'success') {
            if(data.role === 'admin') window.location.href = '/admin';
            else window.location.href = '/';
        } else {
            document.getElementById('login-error').innerText = data.message;
        }
    } catch(err) {
        document.getElementById('login-error').innerText = "Error en servidor";
    }
});
</script>
@endsection
