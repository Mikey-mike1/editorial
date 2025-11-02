@extends('layouts.app')

@section('content')
<h4>Editar Proceso</h4>

{{-- INFO BOX --}}
<div class="info-box" style="background:#f2f2f2; border-left:5px solid #bdbdbd; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        En esta pantalla puedes <strong>modificar los datos de un proceso existente</strong>.<br><br>
        - Cambia el <strong>tipo de proceso</strong> si es necesario.<br>
        - Modifica la <strong>descripción</strong> para reflejar correctamente el proceso.<br>
        - Actualiza el <strong>estado</strong> del proceso (En progreso, Finalizado, Pendiente).<br>
        - Ajusta las <strong>fechas de inicio y finalización</strong> si corresponde.<br>
        - Haz clic en <strong>Actualizar</strong> para guardar los cambios o en <strong>Cancelar</strong> para volver al listado sin modificar nada.
    </p>
</div>

@if(session('success'))
<div class="card-panel green lighten-4 green-text text-darken-4">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('procesos.update', $proceso) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="input-field">
        <input type="text" name="tipo" value="{{ old('tipo', $proceso->tipo) }}" required>
        <label class="active">Tipo de Proceso</label>
        @error('tipo')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <textarea name="descripcion" class="materialize-textarea" required>{{ old('descripcion', $proceso->descripcion) }}</textarea>
        <label class="active">Descripción</label>
        @error('descripcion')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <select name="estado" required>
            <option value="Pendiente" {{ old('estado', $proceso->estado)=='Pendiente'?'selected':'' }}>Pendiente</option>
            <option value="Finalizado" {{ old('estado', $proceso->estado)=='Finalizado'?'selected':'' }}>Finalizado</option>
            <option value="Entregado" {{ old('estado', $proceso->estado)=='Entregado'?'selected':'' }}>Entregado</option>
            <option value="En Revision" {{ old('estado', $proceso->estado)=='En Revision'?'selected':'' }}>En Revision</option>
        </select>
        <label>Estado</label>
        @error('estado')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <input type="date" name="fecha_inicio" 
               value="{{ old('fecha_inicio', \Carbon\Carbon::parse($proceso->fecha_inicio)->format('Y-m-d')) }}" required>
        <label class="active">Fecha Inicio</label>
        @error('fecha_inicio')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <input type="date" name="fecha_final" 
               value="{{ old('fecha_final', $proceso->fecha_final ? \Carbon\Carbon::parse($proceso->fecha_final)->format('Y-m-d') : '') }}">
        <label class="active">Fecha Final</label>
        @error('fecha_final')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <button type="submit" class="btn grey darken-2">
        <i class="material-icons left">save</i> Actualizar
    </button>
    <a href="{{ route('procesos.index') }}" class="btn grey lighten-1">Cancelar</a>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    M.AutoInit();
});
</script>
@endpush
