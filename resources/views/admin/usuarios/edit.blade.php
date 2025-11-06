@extends('layouts.app')

@section('content')
<h4>Editar Usuario</h4>

<div class="card-panel">
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="input-field">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" required>
        </div>

        <div class="input-field">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" required>
        </div>

        <div class="input-field">
            <label for="role">Rol</label>
            <select name="role" id="role" class="browser-default">
                <option value="admin" {{ $usuario->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="editor" {{ $usuario->role == 'editor' ? 'selected' : '' }}>Editor</option>
                <option value="usuario" {{ $usuario->role == 'usuario' ? 'selected' : '' }}>Usuario</option>
            </select>
        </div>

        <div class="right-align">
            <a href="{{ route('usuarios.index') }}" class="btn grey">Cancelar</a>
            <button type="submit" class="btn blue">Actualizar</button>
        </div>
    </form>
</div>
@endsection
