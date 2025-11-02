@extends('layouts.app')

@push('styles')
<style>
    th {
        cursor: pointer;
    }

    input[type="text"], input[type="date"] {
        background-color: #fff !important;
        color: #000 !important;
        border: 1px solid #ccc !important;
        border-radius: 4px;
        padding: 3px 6px;
        font-size: 0.9rem;
        width: 100%;
        box-sizing: border-box;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(0);
        cursor: pointer;
    }

    input[type="text"]:focus, input[type="date"]:focus {
        border-color: #1976d2 !important;
        outline: none;
        box-shadow: 0 0 2px #1976d2;
    }

    thead tr:nth-child(2) th {
        padding: 4px 8px;
    }

    .info-box {
        background:#f2f2f2;
        border-left:5px solid #bdbdbd;
        padding:15px;
        border-radius:6px;
        color:#424242;
        margin-bottom:20px;
    }
</style>
@endpush

@section('content')
<h4>Listado de Cambios</h4>

@if(session('success'))
<div class="card-panel green lighten-4 green-text text-darken-4">
    {{ session('success') }}
</div>
@endif

<div class="info-box">
    <p style="margin:0;">
        En esta pantalla puedes <strong>visualizar todos los cambios</strong> registrados.<br><br>
        - Usa la barra de <strong>búsqueda</strong> para filtrar por proceso o nombre del editor.<br>
        - Haz clic en los encabezados para <strong>ordenar</strong> las columnas.<br>
        - Puedes <strong>filtrar por fechas</strong> usando los campos de Fecha Inicio y Fecha Final.<br>
        - Puedes <strong>editar</strong>, <strong>eliminar</strong> o <strong>ver</strong> cada cambio.
    </p>
</div>

<div class="right-align" style="margin-bottom: 15px;">
    <a href="{{ route('cambios.create') }}" class="btn grey darken-2">
        <i class="material-icons left">add</i> Agregar Cambio
    </a>
</div>

<!-- Buscador -->
<div class="row" style="margin-bottom: 10px;">
    <div class="">
        <input type="text" id="searchInput" placeholder="Buscar por proceso o editor..." onkeyup="buscarCambios()">
    </div>
</div>

<table id="cambiosTable" class="striped responsive-table">
    <thead>
        <tr>
            <th onclick="sortTable(0)">ID</th>
            <th onclick="sortTable(1)">Proceso</th>
            <th onclick="sortTable(2)">Editor</th>
            <th onclick="sortTable(3)">Estado</th>
            <th onclick="sortTable(4)">Fecha Inicio</th>
            <th onclick="sortTable(5)">Fecha Final</th>
            <th onclick="sortTable(6)">Observaciones</th>
            <th>Acciones</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><input type="date" id="fechaInicioFiltro" onchange="filtrarFechas()" /></th>
            <th><input type="date" id="fechaFinalFiltro" onchange="filtrarFechas()" /></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($cambios as $cambio)
        <tr>
            <td>{{ $cambio->id }}</td>
            <td>
                <a href="{{ route('procesos.show', $cambio->proceso->id) }}">
                    {{ $cambio->proceso->tipo }} - {{ $cambio->proceso->cliente->nombre }}
                </a>
            </td>
            <td>{{ $cambio->editor?->nombre ?? '-' }}</td>
            <td>{{ $cambio->estado }}</td>
            <td>{{ $cambio->fecha_inicio }}</td>
            <td>{{ $cambio->fecha_final ?? '-' }}</td>
            <td>{{ $cambio->observaciones ?? '-' }}</td>
            <td>
                <a href="{{ route('cambios.edit', $cambio) }}" class="btn-small grey darken-1">
                    <i class="material-icons">edit</i>
                </a>
                <form action="{{ route('cambios.destroy', $cambio) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn-small red" onclick="return confirm('¿Eliminar este cambio?')">
                        <i class="material-icons">delete</i>
                    </button>
                </form>
                <a href="{{ route('cambios.show', $cambio) }}" class="btn-small blue">
                    <i class="material-icons">visibility</i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@push('scripts')
<script>
let sortDirections = {};

function parseDate(value) {
    if (!value || value === '-') return null;
    const date = new Date(value);
    return isNaN(date) ? null : date;
}

function sortTable(colIndex) {
    const table = document.getElementById("cambiosTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);

    let dir = sortDirections[colIndex] === "asc" ? "desc" : "asc";
    sortDirections[colIndex] = dir;

    rows.sort((a, b) => {
        let x = a.cells[colIndex].innerText.trim();
        let y = b.cells[colIndex].innerText.trim();

        const numX = parseFloat(x);
        const numY = parseFloat(y);
        if (!isNaN(numX) && !isNaN(numY)) return dir === "asc" ? numX - numY : numY - numX;

        const dateX = parseDate(x);
        const dateY = parseDate(y);
        if (dateX && dateY) return dir === "asc" ? dateX - dateY : dateY - dateX;

        return dir === "asc" ? x.localeCompare(y) : y.localeCompare(x);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}

function filtrarFechas() {
    const inicioFiltro = document.getElementById("fechaInicioFiltro").value;
    const finalFiltro = document.getElementById("fechaFinalFiltro").value;
    const table = document.getElementById("cambiosTable");
    const rows = Array.from(table.tBodies[0].rows);

    rows.forEach(row => {
        const fechaInicio = row.cells[4].innerText.trim();
        const fechaFinal = row.cells[5].innerText.trim();
        const fechaInicioDate = parseDate(fechaInicio);
        const fechaFinalDate = parseDate(fechaFinal);

        let visible = true;
        if (inicioFiltro && fechaInicioDate) visible = visible && (fechaInicioDate >= new Date(inicioFiltro));
        if (finalFiltro && fechaFinalDate) visible = visible && (fechaFinalDate <= new Date(finalFiltro));

        row.style.display = visible ? "" : "none";
    });
}

function buscarCambios() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("cambiosTable");
    const rows = Array.from(table.tBodies[0].rows);

    rows.forEach(row => {
        const proceso = row.cells[1].innerText.toLowerCase();
        const editor = row.cells[2].innerText.toLowerCase();
        row.style.display = proceso.includes(input) || editor.includes(input) ? "" : "none";
    });
}
</script>
@endpush
