@extends('layouts.app')

@section('content')
<h4>Detalles del Proceso</h4>

{{-- INFO BOX --}}
<div class="info-box" style="background:#f2f2f2; border-left:5px solid #bdbdbd; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        Visualiza todos los detalles del proceso y los cambios asociados en orden cronológico.
    </p>
</div>

{{-- DATOS DEL PROCESO --}}
<div style="display:flex; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
    <div style="flex:1; min-width:150px; background:#e3f2fd; padding:10px; border-radius:6px;">
        <strong>ID:</strong> {{ $proceso->id }}
    </div>
    <div style="flex:2; min-width:150px; background:#fce4ec; padding:10px; border-radius:6px;">
        <strong>Tipo:</strong> {{ $proceso->tipo }}
    </div>
    <div style="flex:2; min-width:150px; background:#fff3e0; padding:10px; border-radius:6px;">
        <strong>Descripción:</strong> {{ $proceso->descripcion }}
    </div>
    <div style="flex:1; min-width:150px; background:#e8f5e9; padding:10px; border-radius:6px;">
        <strong>Estado:</strong> {{ $proceso->estado }}
    </div>
    <div style="flex:1; min-width:150px; background:#f3e5f5; padding:10px; border-radius:6px;">
        <strong>Fecha Inicio:</strong> {{ $proceso->fecha_inicio }}
    </div>
    <div style="flex:1; min-width:150px; background:#f3e5f5; padding:10px; border-radius:6px;">
        <strong>Fecha Final:</strong> {{ $proceso->fecha_final ?? '-' }}
    </div>
    <div style="flex:2; min-width:150px; background:#e0f7fa; padding:10px; border-radius:6px;">
        <strong>Cliente:</strong> {{ $proceso->cliente->nombre }}
    </div>
    <div style="flex:2; min-width:150px; background:#e0f7fa; padding:10px; border-radius:6px;">
        <strong>Editor:</strong> {{ $proceso->editor?->nombre ?? '-' }}
    </div>
</div>

{{-- CAMBIOS --}}
<h5>Cambios del Proceso</h5>
@if($proceso->cambios->isEmpty())
    <p>No hay cambios registrados para este proceso.</p>
@else
    <div style="display:flex; flex-direction:column; gap:15px;">
        @foreach($proceso->cambios as $cambio)
        <div style="background:#fffde7; padding:15px; border-radius:6px; border-left:5px solid #ffeb3b; display:flex; flex-wrap:wrap; gap:10px;">
            <div style="flex:1; min-width:120px; background:#fff9c4; padding:8px; border-radius:4px;">
                <strong>ID Cambio:</strong> {{ $cambio->id }}
            </div>
            <div style="flex:2; min-width:150px; background:#fff9c4; padding:8px; border-radius:4px;">
                <strong>Observaciones:</strong> {{ $cambio->observaciones ?? '-' }}
            </div>
            <div style="flex:1; min-width:120px; background:#fff9c4; padding:8px; border-radius:4px;">
                <strong>Estado:</strong> {{ $cambio->estado }}
            </div>
            <div style="flex:1; min-width:120px; background:#fff9c4; padding:8px; border-radius:4px;">
                <strong>Fecha Inicio:</strong> {{ $cambio->fecha_inicio }}
            </div>
            <div style="flex:1; min-width:120px; background:#fff9c4; padding:8px; border-radius:4px;">
                <strong>Fecha Final:</strong> {{ $cambio->fecha_final ?? '-' }}
            </div>
            <div style="flex:2; min-width:150px; background:#fff9c4; padding:8px; border-radius:4px;">
                <strong>Editor:</strong> {{ $cambio->editor?->nombre ?? '-' }}
            </div>
        </div>
        @endforeach
    </div>
@endif

<div style="margin-top:20px;">
    <a href="{{ route('procesos.index') }}" class="btn grey lighten-1">Volver al listado</a>
</div>
@endsection
