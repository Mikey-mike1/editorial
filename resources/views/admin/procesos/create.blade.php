@extends('layouts.app')

@section('content')
<h4>Agregar Proceso</h4>

{{-- INFO BOX --}}
<div class="info-box" style="background:#f2f2f2; border-left:5px solid #bdbdbd; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        En esta pantalla puedes <strong>crear un nuevo proceso</strong> para un cliente.<br><br>
        - Selecciona el <strong>cliente</strong> al que pertenece el proceso.<br>
        - Selecciona el <strong>editor</strong> responsable del proceso.<br>
        - Define el <strong>tipo de proceso</strong> y una breve <strong>descripción</strong>.<br>
        - Establece el <strong>estado</strong> actual del proceso (En progreso, Finalizado, Pendiente).<br>
        - Indica las <strong>fechas de inicio y finalización</strong> del proceso.<br>
        - Finalmente, haz clic en <strong>Guardar</strong> para registrar el proceso o en <strong>Cancelar</strong> para volver al listado.
    </p>
</div>

@if(session('success'))
<div class="card-panel green lighten-4 green-text text-darken-4">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('procesos.store') }}" method="POST">
    @csrf

    <div class="input-field">
        <select name="cliente_id" required>
            <option value="" disabled selected>Selecciona un cliente</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
            @endforeach
        </select>
        <label>Cliente</label>
        @error('cliente_id')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <select name="editor_id" required>
            <option value="" disabled selected>Selecciona un editor</option>
            @foreach($editores as $editor)
                <option value="{{ $editor->id }}" {{ old('editor_id') == $editor->id ? 'selected' : '' }}>{{ $editor->nombre }}</option>
            @endforeach
        </select>
        <label>Editor</label>
        @error('editor_id')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <input type="text" name="tipo" value="{{ old('tipo') }}" required>
        <label>Tipo de Proceso</label>
        @error('tipo')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <textarea name="descripcion" class="materialize-textarea" required>{{ old('descripcion') }}</textarea>
        <label>Descripción</label>
        @error('descripcion')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <select name="estado" required>
            <option value="" disabled selected>Selecciona estado</option>
            <option value="Pendiente" {{ old('estado')=='Pendiente'?'selected':'' }}>Pendiente</option>
            <option value="Finalizado" {{ old('estado')=='Finalizado'?'selected':'' }}>Finalizado</option>
            <option value="Entregado" {{ old('estado')=='Entregado'?'selected':'' }}>Entregado</option>
            <option value="En Revision" {{ old('estado')=='En_Revision'?'selected':'' }}>En Revision</option>
        </select>
        <label>Estado</label>
        @error('estado')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
        <label>Fecha Inicio</label>
        @error('fecha_inicio')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <div class="input-field">
        <input type="date" name="fecha_final" value="{{ old('fecha_final') }}">
        <label>Fecha Final</label>
        @error('fecha_final')<span class="red-text">{{ $message }}</span>@enderror
    </div>

    <button type="submit" class="btn grey darken-2">
        <i class="material-icons left">save</i> Guardar
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
