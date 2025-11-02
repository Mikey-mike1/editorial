@extends('layouts.app')

@section('content')
<h4>Detalles del Cambio</h4>

<div class="info-box" style="background:#f2f2f2; border-left:5px solid #4caf50; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        Aquí puedes <strong>ver toda la información del cambio</strong>.<br><br>
        - Observa el <strong>ID</strong>, <strong>proceso</strong> al que pertenece, <strong>editor</strong>, <strong>estado</strong> y <strong>observaciones</strong>.<br>
        - Revisa las <strong>fechas de inicio y finalización</strong> del cambio.<br>
        - Esta vista te permite tener un registro completo y cronológico del cambio realizado.
    </p>
</div>

<div style="display:flex; flex-wrap:wrap; gap:20px; margin-bottom:20px;">
    <div style="flex:1; min-width:200px; background:#e3f2fd; padding:15px; border-radius:6px;">
        <h5>ID del Cambio</h5>
        <p>{{ $cambio->id }}</p>
    </div>

    <div style="flex:2; min-width:200px; background:#fce4ec; padding:15px; border-radius:6px;">
        <h5>Proceso</h5>
        <p>{{ $cambio->proceso->tipo }} - {{ $cambio->proceso->cliente->nombre }}</p>
    </div>

    <div style="flex:1; min-width:200px; background:#fff3e0; padding:15px; border-radius:6px;">
        <h5>Editor</h5>
        <p>{{ $cambio->editor?->nombre ?? '-' }}</p>
    </div>

    <div style="flex:1; min-width:200px; background:#e8f5e9; padding:15px; border-radius:6px;">
        <h5>Estado</h5>
        <p>{{ $cambio->estado }}</p>
    </div>

    <div style="flex:1; min-width:200px; background:#f3e5f5; padding:15px; border-radius:6px;">
        <h5>Fecha Inicio</h5>
        <p>{{ $cambio->fecha_inicio }}</p>
    </div>

    <div style="flex:1; min-width:200px; background:#fbe9e7; padding:15px; border-radius:6px;">
        <h5>Fecha Final</h5>
        <p>{{ $cambio->fecha_final ?? '-' }}</p>
    </div>

    <div style="flex:3; min-width:200px; background:#fffde7; padding:15px; border-radius:6px;">
        <h5>Observaciones</h5>
        <p>{{ $cambio->observaciones ?? '-' }}</p>
    </div>
</div>

<a href="{{ route('cambios.index') }}" class="btn grey lighten-1">Volver al listado</a>
@endsection
