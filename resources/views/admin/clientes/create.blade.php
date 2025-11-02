@extends('layouts.app')

@section('content')
<h4>Agregar Cliente</h4>

<div class="info-box" style="background:#f2f2f2; border-left:5px solid #bdbdbd; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        Completa los datos del nuevo cliente. Todos los campos son obligatorios.
    </p>
</div>

<form action="{{ route('clientes.store') }}" method="POST">
    @csrf

    <div class="input-field">
        <input type="text" name="dni" id="dni" value="{{ old('dni') }}" required>
        <label for="dni">DNI</label>
    </div>

    <div class="input-field">
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
        <label for="nombre">Nombre Completo</label>
    </div>

    <div class="input-field">
        <input type="email" name="correo" id="correo" value="{{ old('correo') }}" required>
        <label for="correo">Correo Electrónico</label>
    </div>

    <div class="input-field">
        <input type="text" name="celular" id="celular" value="{{ old('celular') }}" required>
        <label for="celular">Celular</label>
    </div>

    <div class="right-align">
        <a href="{{ route('clientes.index') }}" class="btn grey lighten-1 black-text">Cancelar</a>
        <button type="submit" class="btn grey darken-2">Guardar</button>
    </div>
</form>
@endsection
