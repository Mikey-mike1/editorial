@extends('layouts.app')

@section('content')
<h4>Agregar Cambio</h4>
<div class="info-box" style="background:#f2f2f2; border-left:5px solid #64b5f6; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        En esta pantalla puedes <strong>crear un nuevo cambio</strong> para un proceso.<br><br>
        - Selecciona el <strong>proceso</strong> al que pertenece el cambio.<br>
        - Define el <strong>estado</strong> del cambio (En progreso, Finalizado, Pendiente).<br>
        - Indica las <strong>fechas de inicio y finalización</strong>.<br>
        - Escribe las <strong>observaciones</strong> que consideres importantes.<br>
        - Asigna un <strong>editor</strong> responsable del cambio.<br>
        - Haz clic en <strong>Guardar</strong> para registrar el cambio o en <strong>Cancelar</strong> para volver al listado.
    </p>
</div>


<form action="{{ route('cambios.store') }}" method="POST">
    @csrf

    <div class="input-field">
        <select name="proceso_id" required>
            <option value="" disabled selected>Selecciona un proceso</option>
            @foreach($procesos as $proceso)
                <option value="{{ $proceso->id }}">
                    {{ $proceso->tipo }} - {{ $proceso->cliente->nombre }}
                </option>
            @endforeach
        </select>
        <label>Proceso</label>
    </div>

    <div class="input-field">
        <select name="editor_id" required>
            <option value="" disabled selected>Selecciona un editor</option>
            @foreach($editores as $editor)
                <option value="{{ $editor->id }}">{{ $editor->nombre }}</option>
            @endforeach
        </select>
        <label>Editor</label>
    </div>

    <div class="input-field">
        <select name="estado" required>
            <option value="Pendiente" {{ old('estado')=='Pendiente'?'selected':'' }}>Pendiente</option>
            <option value="Finalizado" {{ old('estado')=='Finalizado'?'selected':'' }}>Finalizado</option>
            <option value="Entregado" {{ old('estado')=='Entregado'?'selected':'' }}>Entregado</option>
            <option value="En Revision" {{ old('estado')=='En_Revision'?'selected':'' }}>En Revision</option>
        </select>
        <label>Estado</label>
    </div>

    <div class="input-field">
        <input type="date" name="fecha_inicio" required>
        <label>Fecha Inicio</label>
    </div>

    <div class="input-field">
        <input type="date" name="fecha_final">
        <label>Fecha Final</label>
    </div>

    <div class="input-field">
        <textarea name="observaciones" class="materialize-textarea"></textarea>
        <label>Observaciones</label>
    </div>

    <button type="submit" class="btn grey darken-2">
        Guardar
    </button>
    <a href="{{ route('cambios.index') }}" class="btn grey lighten-1">Cancelar</a>
</form>
@endsection
