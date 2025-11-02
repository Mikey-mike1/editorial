@extends('layouts.app')

@push('styles')
<style>
    th {
        cursor: pointer;
    }

    input[type="date"], input[type="text"] {
        background-color: #fff !important;
        color: #000 !important;
        border: 1px solid #ccc !important;
        border-radius: 4px;
        padding: 3px 6px;
        font-size: 0.9rem;
        width: 100%;
        box-sizing: border-box;
    }

    input[type="date"]:focus, input[type="text"]:focus {
        border-color: #1976d2 !important;
        outline: none;
        box-shadow: 0 0 2px #1976d2;
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
<h4>Listado de Procesos</h4>

{{-- INFO BOX --}}
<div class="info-box">
    <p style="margin:0;">
        En esta pantalla puedes <strong>visualizar todos los procesos</strong> registrados.<br><br>
        - Haz clic en <strong>Agregar Proceso</strong> para crear un nuevo proceso.<br>
        - Haz clic en el nombre del <strong>cliente</strong> para ver sus procesos asociados.<br>
        - Puedes <strong>editar</strong> o <strong>eliminar</strong> los procesos.<br>
        - Usa los filtros o el buscador para encontrar rápidamente la información.
    </p>
</div>

@if(session('success'))
<div class="card-panel green lighten-4 green-text text-darken-4">
    {{ session('success') }}
</div>
@endif

<div class="right-align" style="margin-bottom: 15px;">
    <a href="{{ route('procesos.create') }}" class="btn grey darken-2">
        <i class="material-icons left">add</i> Agregar Proceso
    </a>
</div>

<!-- Buscador -->
<div class="input-field">
    <input type="text" id="buscadorProcesos" placeholder="Buscar por tipo, cliente o estado...">
</div>

<table id="procesosTable" class="striped responsive-table">
    <thead>
        <tr>
            <th onclick="sortTable(0)">ID</th>
            <th onclick="sortTable(1)">Tipo</th>
            <th onclick="sortTable(2)">Cliente</th>
            <th onclick="sortTable(3)">Estado</th>
            <th onclick="sortTable(4)">Fecha Inicio</th>
            <th onclick="sortTable(5)">Fecha Final</th>
            <th>Acciones</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><input type="date" id="filtroFechaInicio" onchange="filtrarPorFechas()"></th>
            <th><input type="date" id="filtroFechaFinal" onchange="filtrarPorFechas()"></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($procesos as $proceso)
        <tr>
            <td>{{ $proceso->id }}</td>
            <td>{{ $proceso->tipo }}</td>
            <td>
                <a href="{{ route('clientes.procesos', $proceso->cliente->id) }}">
                    {{ $proceso->cliente->nombre }}
                </a>
            </td>
            <td>{{ $proceso->estado }}</td>
            <td>{{ $proceso->fecha_inicio }}</td>
            <td>{{ $proceso->fecha_final ?? '-' }}</td>
            <td>
                <a href="{{ route('procesos.edit', $proceso) }}" class="btn-small grey darken-1">
                    <i class="material-icons">edit</i>
                </a>
                <form action="{{ route('procesos.destroy', $proceso) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn-small red" type="submit" onclick="return confirm('¿Eliminar este proceso?')">
                        <i class="material-icons">delete</i>
                    </button>
                </form>
                <a href="{{ route('procesos.show', $proceso) }}" class="btn-small blue">
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

// Ordenar columnas
function sortTable(colIndex) {
    const table = document.getElementById("procesosTable");
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

// Buscador en tiempo real
document.getElementById("buscadorProcesos").addEventListener("keyup", function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll("#procesosTable tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});

// Filtro por fechas
function filtrarPorFechas() {
    const inicioFiltro = document.getElementById("filtroFechaInicio").value;
    const finalFiltro = document.getElementById("filtroFechaFinal").value;
    const rows = document.querySelectorAll("#procesosTable tbody tr");

    rows.forEach(row => {
        const fechaInicio = row.cells[4].innerText.trim();
        const fechaFinal = row.cells[5].innerText.trim();

        const fechaInicioDate = parseDate(fechaInicio);
        const fechaFinalDate = parseDate(fechaFinal);

        let visible = true;

        if (inicioFiltro && fechaInicioDate)
            visible = visible && (fechaInicioDate >= new Date(inicioFiltro));
        if (finalFiltro && fechaFinalDate)
            visible = visible && (fechaFinalDate <= new Date(finalFiltro));

        row.style.display = visible ? "" : "none";
    });
}
</script>
@endpush
