@extends('layouts.app')

@section('content')
<h4>Editar Cambio</h4>
<div class="info-box" style="background:#f2f2f2; border-left:5px solid #ff9800; padding:15px; border-radius:6px; color:#424242; margin-bottom:20px;">
    <p style="margin:0;">
        En esta pantalla puedes <strong>editar un cambio existente</strong>.<br><br>
        - Modifica el <strong>proceso</strong> asociado si es necesario.<br>
        - Cambia el <strong>estado</strong> y las <strong>fechas de inicio y finalización</strong>.<br>
        - Actualiza las <strong>observaciones</strong> si corresponde.<br>
        - Cambia el <strong>editor</strong> responsable si es necesario.<br>
        - Haz clic en <strong>Actualizar</strong> para guardar los cambios o en <strong>Cancelar</strong> para volver al listado.
    </p>
</div>


<form action="{{ route('cambios.update', $cambio) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="input-field">
        <select name="proceso_id" required>
            @foreach($procesos as $proceso)
                <option value="{{ $proceso->id }}" {{ $cambio->proceso_id == $proceso->id ? 'selected' : '' }}>
                    {{ $proceso->tipo }} - {{ $proceso->cliente->nombre }}
                </option>
            @endforeach
        </select>
        <label>Proceso</label>
    </div>

    <div class="input-field">
        <select name="editor_id" required>
            @foreach($editores as $editor)
                <option value="{{ $editor->id }}" {{ $cambio->editor_id == $editor->id ? 'selected' : '' }}>
                    {{ $editor->nombre }}
                </option>
            @endforeach
        </select>
        <label>Editor</label>
    </div>

    <div class="input-field">
        <select name="estado" required>
            <option value="Pendiente" {{ old('estado', $proceso->estado)=='Pendiente'?'selected':'' }}>Pendiente</option>
            <option value="Finalizado" {{ old('estado', $proceso->estado)=='Finalizado'?'selected':'' }}>Finalizado</option>
            <option value="Entregado" {{ old('estado', $proceso->estado)=='Entregado'?'selected':'' }}>Entregado</option>
            <option value="En Revision" {{ old('estado', $proceso->estado)=='En Revision'?'selected':'' }}>En Revision</option>
        </select>
        <label>Estado</label>
    </div>

    <div class="input-field">
        <input type="date" name="fecha_inicio" value="{{ $cambio->fecha_inicio }}" required>
        <label>Fecha Inicio</label>
    </div>

    <div class="input-field">
        <input type="date" name="fecha_final" value="{{ $cambio->fecha_final }}">
        <label>Fecha Final</label>
    </div>

    <div class="input-field">
        <textarea name="observaciones" class="materialize-textarea">{{ $cambio->observaciones }}</textarea>
        <label>Observaciones</label>
    </div>

    <button type="submit" class="btn grey darken-2">Actualizar</button>
    <a href="{{ route('cambios.index') }}" class="btn grey lighten-1">Cancelar</a>
</form>
@endsection
