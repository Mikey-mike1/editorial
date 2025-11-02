@extends('layouts.app')

@push('styles')
<style>
th { cursor: pointer; }

input[type="text"] {
    background-color: #fff !important;
    color: #000 !important;
    border: 1px solid #ccc !important;
    border-radius: 4px;
    padding: 3px 6px;
    font-size: 0.9rem;
    width: 100%;
    box-sizing: border-box;
}

input[type="text"]:focus {
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
<h4>Listado de Editores</h4>

@if(session('success'))
<div class="card-panel green lighten-4 green-text text-darken-4">
    {{ session('success') }}
</div>
@endif

<div class="info-box">
    <p style="margin:0;">
        En esta pantalla puedes <strong>visualizar todos los editores</strong> registrados.<br><br>
        - Usa la barra de <strong>búsqueda</strong> para filtrar por nombre de editor.<br>
        - Haz clic en los encabezados para <strong>ordenar</strong> las columnas.<br>
        - Puedes <strong>editar</strong>, <strong>eliminar</strong> o ver detalles de procesos y cambios asignados.
    </p>
</div>

<div class="right-align" style="margin-bottom: 15px;">
    <a href="{{ route('admin.editores.create') }}" class="btn grey darken-2">
        <i class="material-icons left">add</i> Agregar Editor
    </a>
</div>

<div class="row" style="margin-bottom: 10px;">
    <div class="">
        <input type="text" id="searchInput" placeholder="Buscar por nombre de editor..." onkeyup="buscarEditor()">
    </div>
</div>

<table id="editorTable" class="striped responsive-table">
    <thead>
        <tr>
            <th onclick="sortTable(0)">ID</th>
            <th onclick="sortTable(1)">Nombre</th>
            <th>Procesos Asignados</th>
            <th>Cambios Asignados</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($editores as $editor)
        <tr>
            <td>{{ $editor->id }}</td>
            <td>{{ $editor->nombre }}</td>
            <td>
                @if($editor->procesos->count() > 0)
                    <a href="#modal-procesos-{{ $editor->id }}" class="modal-trigger">
                        {{ $editor->procesos->count() }} procesos
                    </a>

                    <!-- Modal de procesos -->
                    <div id="modal-procesos-{{ $editor->id }}" class="modal">
                        <div class="modal-content">
                            <h5>Procesos de {{ $editor->nombre }}</h5>
                            <ul>
                                @foreach($editor->procesos as $proceso)
                                    <li>
                                        <strong>{{ $proceso->tipo }}</strong> - Cliente: {{ $proceso->cliente->nombre }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close btn grey">Cerrar</a>
                        </div>
                    </div>
                @else
                    <em>0</em>
                @endif
            </td>
            <td>
                @if($editor->cambios->count() > 0)
                    <a href="#modal-cambios-{{ $editor->id }}" class="modal-trigger">
                        {{ $editor->cambios->count() }} cambios
                    </a>

                    <!-- Modal de cambios -->
                    <div id="modal-cambios-{{ $editor->id }}" class="modal">
                        <div class="modal-content">
                            <h5>Cambios de {{ $editor->nombre }}</h5>
                            <ul>
                                @foreach($editor->cambios as $cambio)
                                    <li>
                                        Cambio #{{ $cambio->id }} - Proceso: {{ $cambio->proceso->tipo }} - 
                                        Estado: {{ $cambio->estado }} - 
                                        Inicio: {{ $cambio->fecha_inicio }} - 
                                        Final: {{ $cambio->fecha_final ?? '-' }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close btn grey">Cerrar</a>
                        </div>
                    </div>
                @else
                    <em>0</em>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.editores.edit', $editor) }}" class="btn-small grey darken-1">
                    <i class="material-icons">edit</i>
                </a>

                @if($editor->procesos->count() > 0 || $editor->cambios->count() > 0)
                    <a href="#modal-warning-{{ $editor->id }}" class="btn-small red modal-trigger">
                        <i class="material-icons">delete</i>
                    </a>

                    <div id="modal-warning-{{ $editor->id }}" class="modal">
                        <div class="modal-content">
                            <h5>¡No se puede eliminar!</h5>
                            <p>Este editor tiene <strong>{{ $editor->procesos->count() }} procesos</strong> y <strong>{{ $editor->cambios->count() }} cambios</strong> asignados. 
                               Debes reasignar estos elementos antes de poder eliminarlo.</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close btn grey">Cerrar</a>
                        </div>
                    </div>
                @else
                    <form action="{{ route('admin.editores.destroy', $editor) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-small red" onclick="return confirm('¿Eliminar este editor?')">
                            <i class="material-icons">delete</i>
                        </button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    M.Modal.init(elems, {dismissible: true});
});

let sortDirections = {};

function sortTable(colIndex) {
    const table = document.getElementById("editorTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);

    let dir = sortDirections[colIndex] === "asc" ? "desc" : "asc";
    sortDirections[colIndex] = dir;

    rows.sort((a, b) => {
        let x = a.cells[colIndex].innerText.trim();
        let y = b.cells[colIndex].innerText.trim();
        return dir === "asc" ? x.localeCompare(y) : y.localeCompare(x);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}

function buscarEditor() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("editorTable");
    const rows = Array.from(table.tBodies[0].rows);

    rows.forEach(row => {
        const nombre = row.cells[1].innerText.toLowerCase();
        row.style.display = nombre.includes(input) ? "" : "none";
    });
}
</script>
@endpush
