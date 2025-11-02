@extends('layouts.app')

@section('content')
<h1 style="text-align:center;">Bienvenido, {{ $user->name }}</h1>

@if ($user->role === 'admin')
    <p style="text-align:center;">Esto solo lo ve el Admin</p>
    <a href="/admin-section" style="display:block; text-align:center;">Sección de Admin</a>
@elseif ($user->role === 'editor')
    <p style="text-align:center;">Esto solo lo ve el Editor</p>
    <a href="/editor-section" style="display:block; text-align:center;">Sección de Editor</a>
@endif

<form action="{{ route('logout') }}" method="POST" style="text-align:center; margin-top:20px;">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>
@endsection
