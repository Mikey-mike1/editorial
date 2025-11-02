@extends('layouts.app')

@section('content')
<h4>Consulta de Procesos y Cambios</h4>

<div class="info-box">
    <p>Ingresa tu DNI y tu número de celular sin guiones para consultar tus procesos y cambios.</p>
</div>

@if($errors->any())
    <div class="card-panel red lighten-4 red-text text-darken-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('consulta.resultados') }}" method="POST">
    @csrf
    <div class="input-field">
        <input type="text" name="dni" value="{{ old('dni') }}" required maxlength="13">
        <label>DNI</label>
    </div>
    <div class="input-field">
        <input type="text" name="celular" value="{{ old('celular') }}" required maxlength="8">
        <label>Celular</label>
    </div>
    <button type="submit" class="btn grey darken-2">
        <i class="material-icons left">search</i> Consultar
    </button>
</form>
@endsection
