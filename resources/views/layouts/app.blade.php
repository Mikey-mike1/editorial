<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Editorial Doctor Tesis') }}</title>

  <!-- Materialize CSS -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <!-- Íconos -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    :root {
      --redcap-primary: #eeeeee;
      --redcap-dark: #212121;
      --redcap-light: #f5f5f5;
      --background: #f0f0f0;
    }

html, body {
  height: 100%;
  margin: 0;
  display: flex;
  flex-direction: column;
  background-color: var(--background);
  color: var(--redcap-dark);
  font-family: 'Roboto', sans-serif;
}

/* El contenido principal crece para empujar el footer hacia abajo */
main {
  flex: 1 0 auto;
  padding: 30px 0;
}

    /* ==========================
       PANEL DE NOTIFICACIONES
       ========================== */
    #notificaciones-panel {
      position: fixed;
      top: 64px;
      right: 0;
      width: 320px;
      height: calc(100vh - 64px);
      background-color: var(--redcap-light);
      border-left: 1px solid #ccc;
      box-shadow: -2px 0 8px rgba(0,0,0,0.2);
      overflow-y: auto;
      z-index: 1005;
      transform: translateX(100%);
      transition: transform 0.3s ease-in-out;
    }
    #notificaciones-panel.active {
      transform: translateX(0);
    }

    .notification-header {
      background-color: var(--redcap-primary);
      padding: 12px;
      font-weight: 600;
      text-align: center;
      border-bottom: 1px solid #ccc;
      color: var(--redcap-dark);
    }

    .notification-item {
      padding: 10px 15px;
      border-bottom: 1px solid #e0e0e0;
      color: var(--redcap-dark);
    }

    .notification-item small {
      color: #757575;
    }

    .notification-item.urgente {
      background-color: #fff3e0;
    }

    /* ==========================
       BOTÓN FLOTANTE FIJO
       ========================== */
    #notificaciones-btn {
      position: fixed;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      background-color: var(--redcap-primary);
      color: var(--redcap-dark);
      border: 1px solid #ccc;
      border-radius: 12px 0 0 12px;
      padding: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      cursor: pointer;
      z-index: 1006;
      display: flex;
      align-items: center;
      transition: background-color 0.3s ease;
    }
    #notificaciones-btn:hover {
      background-color: var(--redcap-dark);
      color: #fff;
    }

    /* ==========================
       NAVBAR
       ========================== */
    nav {
      background-color: var(--redcap-primary) !important;
    }

    nav .brand-logo,
    nav ul li a,
    nav .material-icons {
      color: var(--redcap-dark) !important;
    }

    /* ==========================
       SIDENAV (MENÚ MÓVIL)
       ========================== */
    .sidenav {
      background-color: var(--redcap-light);
    }
    .sidenav a {
      color: var(--redcap-dark) !important;
      font-weight: 500;
    }
    .sidenav .material-icons {
      color: var(--redcap-dark);
      margin-right: 10px;
    }

    /* ==========================
       FOOTER NORMAL
       ========================== */
    footer.page-footer {
      background-color: var(--redcap-dark) !important;
      color: #fff;
      padding: 10px 0;
      text-align: center;
      position: relative;
    
    }

    @media (max-width: 600px) {
  footer.page-footer {
    display: none;
  }
}

  </style>

  @stack('styles')
</head>

<body>
@if(!request()->routeIs('consulta.form') && !request()->routeIs('consulta.resultados'))
  {{-- NAVBAR --}}
  @if(Auth::check() && Auth::user()->role === 'admin')
  <div class="navbar-fixed">
    <nav class="z-depth-2">
      <div class="nav-wrapper container">
        <a href="{{ url('/') }}" class="brand-logo">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:40px; vertical-align:middle;">
        </a>
        <a href="#" data-target="mobile-menu" class="sidenav-trigger">
          <i class="material-icons">menu</i>
        </a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a href="{{ route('dashboard') }}"><i class="material-icons left">dashboard</i>Dashboard</a></li>
           <li><a href="{{ route('usuarios.index') }}"><i class="material-icons left">add</i>Usuarios</a></li>
          <li><a href="{{ route('clientes.index') }}"><i class="material-icons left">people</i>Clientes</a></li>
          <li><a href="{{ route('procesos.index')}}"><i class="material-icons left">folder_open</i>Procesos</a></li>
          <li><a href="{{ route('cambios.index')}}"><i class="material-icons left">swap_horiz</i>Cambios</a></li>
          <li><a href="{{ route('admin.editores.index') }}"><i class="material-icons left">edit</i>Editores</a></li>
          <li><a href="{{ route('logout') }}"><i class="material-icons left">exit_to_app</i>Salir</a></li>
          <li><a href="{{ route('admin.cronometro') }}"><i class="material-icons left">timer</i>Cronómetro</a></li>
        </ul>
      </div>
    </nav>
  </div>

  <!-- MENÚ LATERAL MÓVIL -->
  <ul class="sidenav" id="mobile-menu">
    <li><a href="{{ route('dashboard') }}"><i class="material-icons">dashboard</i>Dashboard</a></li>
    <li><a href="{{ route('usuarios.index') }}"><i class="material-icons left">add</i>Usuarios</a></li>
    <li><a href="{{ route('clientes.index') }}"><i class="material-icons">people</i>Clientes</a></li>
    <li><a href="{{route('procesos.index')}}"><i class="material-icons">folder_open</i>Procesos</a></li>
    <li><a href="{{ route('cambios.index')}}"><i class="material-icons">swap_horiz</i>Cambios</a></li>
    <li><a href="{{ route('admin.editores.index') }}"><i class="material-icons left">edit</i>Editores</a></li>
    <li><a href="{{ route('logout') }}"><i class="material-icons">exit_to_app</i>Salir</a></li>
    <li><a href="{{ route('admin.cronometro') }}"><i class="material-icons left">timer</i>Cronómetro</a></li>
  </ul>
  @endif

  <!-- PANEL DE NOTIFICACIONES -->
@if(!request()->routeIs('login'))
<div id="notificaciones-panel" style="width: 350px; border: 1px solid #ccc; border-radius: 8px; padding: 10px; background: #f9f9f9; font-family: Arial, sans-serif;">
    <div class="notification-header" style="font-weight: bold; font-size: 18px; margin-bottom: 10px; display: flex; align-items: center;">
        🔔 Notificaciones
    </div>

    @php
        $user = auth()->user();
        $notificaciones = \App\Models\CustomNotification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();
    @endphp

    @forelse($notificaciones as $n)
        <div class="notification-item {{ $n->type }}" style="border-bottom: 1px solid #ddd; padding: 8px 5px; margin-bottom: 5px; background: {{ $n->type == 'warning' ? '#fff3cd' : '#e2e3e5' }}; border-radius: 5px; position: relative;">
            <strong style="display: block; font-size: 14px; margin-bottom: 3px;">{{ $n->title }}</strong>
            <p style="margin: 0 0 5px 0; font-size: 13px; line-height: 1.3;">
                {{ $n->message }}

                @if($n->proceso_id)
                    <a href="{{ route('procesos.show', $n->proceso_id) }}" style="color: #007bff; text-decoration: none;">Ver proceso</a>
                @elseif($n->cambio_id)
                    <a href="{{ route('cambios.show', $n->cambio_id) }}" style="color: #007bff; text-decoration: none;">Ver cambio</a>
                @endif
            </p>
            <small style="color: #555; font-size: 11px;">{{ $n->created_at->diffForHumans() }}</small>

            <form action="{{ route('notifications.destroy', $n->id) }}" method="POST" style="position: absolute; top: 5px; right: 5px; margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 16px; line-height: 1;">
                    ✖
                </button>
            </form>
        </div>
    @empty
        <div class="notification-item" style="padding: 10px; text-align: center; color: #777;">No tienes notificaciones</div>
    @endforelse
</div>

  <!-- BOTÓN FLOTANTE SIEMPRE VISIBLE -->
  <div id="notificaciones-btn">
    <i class="material-icons">notifications</i>
  </div>

@endif
 @endif

  <!-- CONTENIDO PRINCIPAL -->
  <main class="container">
    <div class="card z-depth-1 white">
      <div class="card-content">
        @yield('content')
      </div>
    </div>
  </main>

 
  <!-- FOOTER -->
  <footer class="page-footer">
    <div class="container">
      <small>Doctor Tesis © {{ date('Y') }}</small>
    </div>
  </footer>

  <!-- SCRIPTS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      M.AutoInit();
      $('.sidenav').sidenav();

      const btn = document.getElementById('notificaciones-btn');
      const panel = document.getElementById('notificaciones-panel');

      btn.addEventListener('click', function() {
        panel.classList.toggle('active');
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
