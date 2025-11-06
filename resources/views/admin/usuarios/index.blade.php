@extends('layouts.app')

@push('styles')
<style>
    th { cursor: pointer; }
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
<h4>Listado de Usuarios</h4>

<div class="info-box">
    <p>En esta sección puedes <strong>gestionar los usuarios del sistema</strong>.<br><br>
       - Usa el botón <strong>Agregar Usuario</strong> para registrar un nuevo usuario.<br>
       - Haz clic en <strong>Editar</strong> o <strong>Eliminar</strong> según corresponda.<br>
       - Usa el buscador para encontrar rápidamente usuarios por nombre o correo.
    </p>
</div>

@if(session('success'))
<div class="card-panel green lighten-4 green-text text-darken-4">
    {{ session('success') }}
</div>
@endif

<div class="right-align" style="margin-bottom: 15px;">
    <a href="{{ route('usuarios.create') }}" class="btn grey darken-2">
        <i class="material-icons left">add</i> Agregar Usuario
    </a>
</div>

<!-- Buscador -->
<div class="input-field">
    <input type="text" id="buscadorUsuarios" placeholder="Buscar por nombre o correo...">
</div>

<table id="usuariosTable" class="striped responsive-table">
    <thead>
        <tr>
            <th onclick="sortTable(0)">ID</th>
            <th onclick="sortTable(1)">Nombre</th>
            <th onclick="sortTable(2)">Correo</th>
            <th onclick="sortTable(3)">Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($usuarios as $usuario)
        <tr>
            <td>{{ $usuario->id }}</td>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->role ?? 'Usuario' }}</td>
            <td>
                <a href="{{ route('usuarios.show', $usuario) }}" class="btn-small blue">
                    <i class="material-icons">visibility</i>
                </a>
                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn-small grey darken-1">
                    <i class="material-icons">edit</i>
                </a>
                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn-small red" type="submit" onclick="return confirm('¿Eliminar este usuario?')">
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

function sortTable(colIndex) {
    const table = document.getElementById("usuariosTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);

    let dir = sortDirections[colIndex] === "asc" ? "desc" : "asc";
    sortDirections[colIndex] = dir;

    rows.sort((a, b) => {
        let x = a.cells[colIndex].innerText.trim().toLowerCase();
        let y = b.cells[colIndex].innerText.trim().toLowerCase();
        return dir === "asc" ? x.localeCompare(y) : y.localeCompare(x);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}

// Buscador
document.getElementById("buscadorUsuarios").addEventListener("keyup", function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll("#usuariosTable tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});
</script>
@endpush
