@extends('layouts.app')

@push('styles')
<style>
  /* Centrar el login vertical y horizontal */
  main.container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
    background-color: var(--background);
  }

  .login-card {
    max-width: 400px;
    width: 100%;
    padding: 30px;
    border-radius: 10px;
    background-color: var(--redcap-light);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .login-card h5 {
    color: var(--redcap-dark);
    margin-bottom: 30px;
    text-align: center;
  }

  .btn.full-width {
    width: 100%;
    margin-top: 20px;
  }

  .login-card .forgot-password {
    margin-top: 15px;
    display: block;
    text-align: center;
    color: var(--redcap-dark);
    text-decoration: underline;
  }

  .login-errors {
    color: #e53935;
    margin-bottom: 15px;
    font-size: 0.9rem;
  }

  .input-field input:focus + label {
    color: var(--redcap-dark) !important;
  }

  .input-field input:focus {
    border-bottom: 2px solid var(--redcap-dark) !important;
    box-shadow: none !important;
  }
</style>
@endpush

@section('content')
<div class="login-card card z-depth-2">
  <div class="card-content">
    <h5>Iniciar Sesión</h5>

    <!-- Mostrar errores -->
    @if ($errors->any())
      <div class="login-errors">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    @if(session('success'))
      <div class="green-text center-align" style="margin-bottom:15px;">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      <div class="input-field">
        <input id="email" type="email" name="email" required autofocus value="{{ old('email') }}">
        <label for="email" class="active">Correo electrónico</label>
      </div>

      <div class="input-field">
        <input id="password" type="password" name="password" required>
        <label for="password" class="active">Contraseña</label>
      </div>

      <p>
        <label>
          <input type="checkbox" name="remember" />
          <span>Recordarme</span>
        </label>
      </p>

      <button class="btn waves-effect waves-light full-width" type="submit">
        Ingresar
      </button>

      <a href="{{ route('login') }}" class="forgot-password">¿Olvidaste tu contraseña?</a>
    </form>
  </div>
</div>
@endsection
