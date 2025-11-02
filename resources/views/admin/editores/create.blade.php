@extends('layouts.app')

@section('content')
<h4>Crear Editor</h4>

@if ($errors->any())
<div class="card-panel red lighten-4 red-text text-darken-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.editores.store') }}" method="POST">
    @csrf
    <div class="input-field">
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
        <label for="nombre" class="active">Nombre del Editor</label>
    </div>
    <button class="btn green">Guardar</button>
    <a href="{{ route('admin.editores.index') }}" class="btn grey">Cancelar</a>
</form>
@endsection
