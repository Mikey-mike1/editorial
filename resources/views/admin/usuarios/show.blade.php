@extends('layouts.app')

@section('content')
<h4>Detalles del Usuario</h4>

<div class="card-panel">
    <p><strong>ID:</strong> {{ $usuario->id }}</p>
    <p><strong>Nombre:</strong> {{ $usuario->name }}</p>
    <p><strong>Correo:</strong> {{ $usuario->email }}</p>
    <p><strong>Rol:</strong> {{ ucfirst($usuario->role ?? 'usuario') }}</p>
    <p><strong>Fecha de Registro:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>

    <div class="right-align">
        <a href="{{ route('usuarios.index') }}" class="btn grey">Volver</a>
        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn blue">Editar</a>
    </div>
</div>
@endsection
