@extends('layouts.app')

@section('content')
<h4>Agregar Nuevo Usuario</h4>

<div class="card-panel">
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="input-field">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div class="input-field">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div class="input-field">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="input-field">
            <label for="role">Rol</label>
            <select name="role" id="role" class="browser-default">
                <option value="admin">Administrador</option>
                <option value="editor">Editor</option>
                <option value="usuario">Usuario</option>
            </select>
        </div>

        <div class="right-align">
            <a href="{{ route('usuarios.index') }}" class="btn grey">Cancelar</a>
            <button type="submit" class="btn blue">Guardar</button>
        </div>
    </form>
</div>
@endsection
