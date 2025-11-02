@extends('layouts.app')

@push('styles')
<style>
    th {
        cursor: pointer;
    }

    /* Estilo claro para los inputs de fecha */
    input[type="date"] {
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

    input[type="date"]:focus {
        border-color: #757575 !important;
        outline: none;
        box-shadow: 0 0 2px #9e9e9e;
    }

    thead tr:nth-child(2) th {
        padding: 4px 8px;
    }

    /* Caja de información gris */
    .info-box {
        background: #f2f2f2;
        border-left: 5px solid #bdbdbd;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        color: #424242;
    }

    .info-box strong {
        color: #212121;
    }
</style>
@endpush

@section('content')
<h4>Procesos de {{ $cliente->nombre }}</h4>

<!-- Descripción de la pantalla -->
    <p >
        Bienvenido al panel de administración. Aquí puedes ver todos los procesos de los clientes.<br><br>
        - Puedes <strong>buscar</strong> por nombre de cliente o tipo de proceso usando el cuadro de búsqueda.<br>
        - Las columnas se pueden <strong>ordenar</strong> haciendo clic en el encabezado.<br>
        - En la columna <strong># Procesos del Cliente</strong>, puedes hacer clic en el número para ver todos los procesos de ese cliente.<br>
        - En la columna <strong># Cambios</strong> se muestra cuántos cambios tiene cada proceso.
    </p>

<!-- Cuadro de búsqueda -->
<form method="GET">
    <div class="input-field">
        <input id="searchInput" type="text" placeholder="Buscar por tipo, estado o editor..." onkeyup="filtrarTabla()">
        <label for="searchInput" class="active">Buscar</label>
    </div>
</form>

<table id="procesosClienteTable" class="striped responsive-table">
    <thead>
        <tr>
            <th onclick="sortTable(0)">Tipo</th>
            <th onclick="sortTable(1)">Descripción</th>
            <th onclick="sortTable(2)">Estado</th>
            <th onclick="sortTable(3)">Fecha Inicio</th>
            <th onclick="sortTable(4)">Fecha Final</th>
            <th onclick="sortTable(5)">Editor</th>
            <th onclick="sortTable(6)"># Cambios</th>
        </tr>
        <!-- Filtros de fecha -->
        <tr>
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
        @foreach($cliente->procesos as $proceso)
        <tr>
            <td>{{ $proceso->tipo }}</td>
            <td>{{ $proceso->descripcion ?? '-' }}</td>
            <td>{{ $proceso->estado }}</td>
            <td>{{ $proceso->fecha_inicio }}</td>
            <td>{{ $proceso->fecha_final ?? '-' }}</td>
            <td>{{ $proceso->editor->nombre ?? '-' }}</td>
            <td>{{ $proceso->cambios->count() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('dashboard') }}" class="btn">Volver al Dashboard</a>

@push('scripts')
<script>
let sortDirections = {};

function parseDate(value) {
    if (!value || value === '-') return null;
    const date = new Date(value);
    return isNaN(date) ? null : date;
}

// === ORDENAR POR COLUMNA ===
function sortTable(colIndex) {
    const table = document.getElementById("procesosClienteTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);

    let dir = sortDirections[colIndex] === "asc" ? "desc" : "asc";
    sortDirections[colIndex] = dir;

    rows.sort((a, b) => {
        let x = a.cells[colIndex].innerText.trim();
        let y = b.cells[colIndex].innerText.trim();

        const numX = parseFloat(x);
        const numY = parseFloat(y);
        if (!isNaN(numX) && !isNaN(numY)) {
            return dir === "asc" ? numX - numY : numY - numX;
        }

        const dateX = parseDate(x);
        const dateY = parseDate(y);
        if (dateX && dateY) {
            return dir === "asc" ? dateX - dateY : dateY - dateX;
        }

        return dir === "asc" ? x.localeCompare(y) : y.localeCompare(x);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}

// === FILTRO POR FECHAS ===
function filtrarFechas() {
    const inicioFiltro = document.getElementById("fechaInicioFiltro").value;
    const finalFiltro = document.getElementById("fechaFinalFiltro").value;
    const table = document.getElementById("procesosClienteTable");
    const rows = Array.from(table.tBodies[0].rows);

    rows.forEach(row => {
        const fechaInicio = row.cells[3].innerText.trim();
        const fechaFinal = row.cells[4].innerText.trim();

        const fechaInicioDate = parseDate(fechaInicio);
        const fechaFinalDate = parseDate(fechaFinal);

        let visible = true;

        if (inicioFiltro && fechaInicioDate) {
            visible = visible && (fechaInicioDate >= new Date(inicioFiltro));
        }
        if (finalFiltro && fechaFinalDate) {
            visible = visible && (fechaFinalDate <= new Date(finalFiltro));
        }

        row.style.display = visible ? "" : "none";
    });
}

// === FILTRO DE BÚSQUEDA GENERAL ===
function filtrarTabla() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("procesosClienteTable");
    const rows = Array.from(table.tBodies[0].rows);

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
}
</script>
@endpush

@endsection
