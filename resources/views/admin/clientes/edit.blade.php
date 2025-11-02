@extends('layouts.app')

@section('content')
<h4>Editar Cliente</h4>

<div class="info-box" style="background:#f2f2f2; border-left:5px solid #bdbdbd; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        Actualiza la información del cliente. Haz clic en <strong>Guardar Cambios</strong> cuando termines.
    </p>
</div>

<form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="input-field">
        <input type="text" name="dni" id="dni" value="{{ old('dni', $cliente->dni) }}" required>
        <label for="dni" class="active">DNI</label>
    </div>

    <div class="input-field">
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
        <label for="nombre" class="active">Nombre Completo</label>
    </div>

    <div class="input-field">
        <input type="email" name="correo" id="correo" value="{{ old('correo', $cliente->correo) }}" required>
        <label for="correo" class="active">Correo Electrónico</label>
    </div>

    <div class="input-field">
        <input type="text" name="celular" id="celular" value="{{ old('celular', $cliente->celular) }}" required>
        <label for="celular" class="active">Celular</label>
    </div>

    <div class="right-align">
        <a href="{{ route('clientes.index') }}" class="btn grey lighten-1 black-text">Cancelar</a>
        <button type="submit" class="btn grey darken-2">Guardar Cambios</button>
    </div>
</form>
@endsection
