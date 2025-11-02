@extends('layouts.app')

@section('content')
<h4>Resultados de {{ $cliente->nombre }}</h4>

<p><strong>DNI:</strong> {{ $cliente->dni }} | <strong>Celular:</strong> {{ $cliente->celular }}</p>

<h5>Procesos</h5>
@if($procesos->isEmpty())
    <p>No hay procesos activos.</p>
@else
    <table class="striped responsive-table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($procesos as $proceso)
            <tr>
                <td>{{ $proceso->tipo }}</td>
                <td>{{ $proceso->descripcion }}</td>
                <td>{{ $proceso->estado }}</td>
                <td>{{ $proceso->fecha_inicio }}</td>
                <td>{{ $proceso->fecha_final }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

<h5>Cambios</h5>
@if($cambios->isEmpty())
    <p>No hay cambios registrados.</p>
@else
    <table class="striped responsive-table">
        <thead>
            <tr>
                <th>Proceso ID</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Estado</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cambios as $cambio)
            <tr>
                <td>{{ $cambio->proceso_id }}</td>
                <td>{{ $cambio->fecha_inicio }}</td>
                <td>{{ $cambio->fecha_final }}</td>
                <td>{{ $cambio->estado }}</td>
                <td>{{ $cambio->observaciones }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

<a href="{{ route('consulta.form') }}" class="btn grey lighten-1">Nueva Consulta</a>
@endsection
