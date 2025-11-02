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
<h4>Administración de Clientes</h4>

{{-- Mensaje de éxito --}}
@if(session('success'))
<div class="card-panel green lighten-4 green-text text-darken-4">
    {{ session('success') }}
</div>
@endif

<div class="info-box">
    <p style="margin:0;">
        En este módulo puedes <strong>gestionar los clientes</strong> registrados.<br><br>
        - Usa el botón <strong>“Agregar Cliente”</strong> para crear uno nuevo.<br>
        - Puedes <strong>editar</strong> o <strong>eliminar</strong> los clientes desde la tabla.<br>
        - Haz clic en el nombre del cliente para ver sus <strong>procesos asociados</strong>.
    </p>
</div>

<div class="right-align" style="margin-bottom:20px;">
    <a href="{{ route('clientes.create') }}" class="btn waves-effect waves-light grey darken-2">
        <i class="material-icons left">person_add</i> Agregar Cliente
    </a>
</div>

<!-- Buscador -->
<div class="input-field">
    <input type="text" id="buscadorClientes" placeholder="Buscar por nombre, correo o DNI...">
</div>

<table id="clientesTable" class="striped responsive-table">
    <thead>
        <tr>
            <th onclick="sortTable(0)">ID</th>
            <th onclick="sortTable(1)">DNI</th>
            <th onclick="sortTable(2)">Nombre</th>
            <th onclick="sortTable(3)">Correo</th>
            <th onclick="sortTable(4)">Celular</th>
            <th onclick="sortTable(5)">Fecha Registro</th>
            <th>Acciones</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><input type="date" id="filtroFecha" onchange="filtrarPorFecha()"></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr>
            <td>{{ $cliente->id }}</td>
            <td>{{ $cliente->dni }}</td>
            <td>
                <a href="{{ route('clientes.procesos', $cliente->id) }}">
                    {{ $cliente->nombre }}
                </a>
            </td>
            <td>{{ $cliente->correo }}</td>
            <td>{{ $cliente->celular }}</td>
            <td>{{ $cliente->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn-small grey darken-1">
                    <i class="material-icons">edit</i>
                </a>
                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn-small red" type="submit" onclick="return confirm('¿Eliminar este cliente?')">
                        <i class="material-icons">delete</i>
                    </button>
                </form>
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
    const table = document.getElementById("clientesTable");
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

// Filtro por búsqueda
document.getElementById("buscadorClientes").addEventListener("keyup", function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll("#clientesTable tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});

// Filtro por fecha
function filtrarPorFecha() {
    const filtro = document.getElementById("filtroFecha").value;
    const rows = document.querySelectorAll("#clientesTable tbody tr");

    rows.forEach(row => {
        const fecha = row.cells[5].innerText.trim();
        const fechaRow = parseDate(fecha);
        const visible = filtro ? fechaRow && fechaRow.toISOString().startsWith(filtro) : true;
        row.style.display = visible ? "" : "none";
    });
}
</script>
@endpush
