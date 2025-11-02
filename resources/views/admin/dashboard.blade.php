@extends('layouts.app')

@push('styles')
<style>
    th {
        cursor: pointer;
    }

    /* Estilo para los inputs de fecha */
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
        filter: invert(0); /* asegúrate de que el icono sea visible */
        cursor: pointer;
    }

    input[type="date"]:focus {
        border-color: #1976d2 !important; /* azul */
        outline: none;
        box-shadow: 0 0 2px #1976d2;
    }

    thead tr:nth-child(2) th {
        padding: 4px 8px;
    }

    /* Estilo de info box */
    .info-box {
        background:#f2f2f2; 
        border-left:5px solid #bdbdbd; 
        padding:15px; 
        border-radius:6px; 
        color:#424242; 
        margin-bottom:20px;
    }

    .cambio-card {
        padding:10px; 
        margin-bottom:10px; 
        background:#f1f8e9; 
        border-radius:6px;
    }
</style>
@endpush

@section('content')
<h4>Dashboard Administrador</h4>

<!-- Info Box -->
<div class="info-box">
    <p style="margin:0;">
        Bienvenido al panel de administración. Aquí puedes ver todos los procesos de los clientes.<br>
        - Puedes <strong>buscar</strong> por cliente o tipo de proceso.<br>
        - Ordena las columnas haciendo clic en el encabezado.<br>
        - Haz clic en <strong># Cambios</strong> para ver los cambios de cada proceso en un modal.<br>
        - Haz clic en <strong># Procesos del Cliente</strong> para ver los procesos asociados a ese cliente.
    </p>
</div>

<!-- Buscador -->
<form method="GET" action="{{ route('dashboard') }}">
    <div class="input-field">
        <input id="search" type="text" name="search" value="{{ $search ?? '' }}">
        <label for="search" class="active">Buscar por cliente o tipo de proceso</label>
    </div>
    <button class="btn" type="submit">Buscar</button>
</form>

<!-- Tabla de procesos -->
<table id="procesosTable" class="striped responsive-table">
    <thead>
        <tr>
            <th onclick="sortTable(0)">Cliente</th>
            <th onclick="sortTable(1)">Tipo de Proceso</th>
            <th onclick="sortTable(2)">Estado</th>
            <th onclick="sortTable(3)">Fecha Inicio</th>
            <th onclick="sortTable(4)">Fecha Final</th>
            <th onclick="sortTable(5)"># Procesos del Cliente</th>
            <th onclick="sortTable(6)"># Cambios</th>
            <th>Flujograma</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>
                <input type="date" id="fechaInicioFiltro" onchange="filtrarFechas()" />
            </th>
            <th>
                <input type="date" id="fechaFinalFiltro" onchange="filtrarFechas()" />
            </th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($procesos as $proceso)
        <tr>
            <td>{{ $proceso->cliente->nombre }}</td>
            <td>{{ $proceso->tipo }}</td>
            <td>{{ $proceso->estado }}</td>
            <td>{{ $proceso->fecha_inicio }}</td>
            <td>{{ $proceso->fecha_final ?? '-' }}</td>
            <td>
                <a href="{{ route('clientes.procesos', $proceso->cliente->id) }}">
                    {{ $proceso->cliente->procesos->count() }}
                </a>
            </td>
            <td>
                @if($proceso->cambios->count() > 0)
                <a href="#modal-cambios-{{ $proceso->id }}" class="modal-trigger">
                    {{ $proceso->cambios->count() }}
                </a>
                @else
                    0
                @endif
            </td>
            <td>
    <a href="{{ route('procesos.flujograma', $proceso->id) }}" 
       class="btn-small blue darken-2 waves-effect waves-light" 
       style="border-radius:6px;">
        <i class="material-icons left">timeline</i> Ver
    </a>
</td>

        </tr>

        <!-- Modal de cambios -->

<!-- Modal de cambios -->
<div id="modal-cambios-{{ $proceso->id }}" class="modal" style="max-width:900px; border-radius:16px; overflow:hidden;">
  <div class="modal-content" style="padding:30px; background:#f9fafb;">
    <h4 style="margin-bottom:20px; font-weight:700; color:#222;">
      Detalles del Proceso: <span style="color:#1976d2;">{{ $proceso->tipo }} - {{ $proceso->cliente->nombre }}</span>
    </h4>

    @if($proceso->cambios->isEmpty())
      <p style="font-size:16px; color:#666;">No hay cambios registrados para este proceso.</p>
    @else
      <div style="background:#f1f8e9; border-left:6px solid #8bc34a; padding:16px 20px; border-radius:10px; margin-bottom:25px;">
        <p style="margin:0; color:#333; line-height:1.6;">
          Aquí puedes ver toda la información de los cambios realizados.<br>
          <b>-</b> Observa el ID, proceso, editor, estado y fechas.<br>
          <b>-</b> Consulta las observaciones y el progreso.
        </p>
      </div>

      <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));
        gap:20px;
      ">
        @foreach($proceso->cambios->sortByDesc('fecha_inicio') as $cambio)
          <div style="
            background:white;
            border-radius:14px;
            box-shadow:0 4px 10px rgba(0,0,0,0.08);
            padding:20px;
            display:flex;
            flex-direction:column;
            gap:12px;
            transition: all 0.25s ease;
          "
          onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 18px rgba(0,0,0,0.12)';"
          onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.08)';">

            <h5 style="margin:0; font-size:18px; font-weight:600; color:#1976d2;">
              ID del Cambio: {{ $cambio->id }}
            </h5>

            <div style="background:#fce4ec; padding:10px 14px; border-radius:8px;">
              <b>Proceso:</b> {{ $cambio->proceso->id ?? '-' }}
            </div>

            <div style="background:#fff3e0; padding:10px 14px; border-radius:8px;">
              <b>Editor:</b> {{ $cambio->editor?->nombre ?? '-' }}
            </div>

            <div style="background:#e8f5e9; padding:10px 14px; border-radius:8px;">
              <b>Estado:</b> {{ $cambio->estado }}
            </div>

            <div style="background:#ede7f6; padding:10px 14px; border-radius:8px;">
              <b>Fecha Inicio:</b> {{ $cambio->fecha_inicio }}
            </div>

            <div style="background:#ffebee; padding:10px 14px; border-radius:8px;">
              <b>Fecha Final:</b> {{ $cambio->fecha_final ?? '-' }}
            </div>

            <div style="background:#fffde7; padding:10px 14px; border-radius:8px;">
              <b>Observaciones:</b> {{ $cambio->observaciones ?? '-' }}
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <div class="modal-footer" style="background:#f5f5f5; padding:14px; text-align:right;">
    <a href="#!" class="modal-close btn grey lighten-1" style="border-radius:8px; background:#90a4ae;">Cerrar</a>
  </div>
</div>




        @endforeach
    </tbody>
</table>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar modales
    var elems = document.querySelectorAll('.modal');
    M.Modal.init(elems, {dismissible: true});
});

// Ordenamiento de tabla
let sortDirections = {};

function parseDate(value) {
    if (!value || value === '-') return null;
    const date = new Date(value);
    return isNaN(date) ? null : date;
}

function sortTable(colIndex) {
    const table = document.getElementById("procesosTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);

    let dir = sortDirections[colIndex] === "asc" ? "desc" : "asc";
    sortDirections[colIndex] = dir;

    rows.sort((a, b) => {
        let x = a.cells[colIndex].innerText.trim();
        let y = b.cells[colIndex].innerText.trim();

        // Detecta si es número
        const numX = parseFloat(x);
        const numY = parseFloat(y);
        if (!isNaN(numX) && !isNaN(numY)) return dir === "asc" ? numX - numY : numY - numX;

        // Detecta si es fecha
        const dateX = parseDate(x);
        const dateY = parseDate(y);
        if (dateX && dateY) return dir === "asc" ? dateX - dateY : dateY - dateX;

        // Comparar texto
        return dir === "asc" ? x.localeCompare(y) : y.localeCompare(x);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}

// Filtro por fechas
function filtrarFechas() {
    const inicioFiltro = document.getElementById("fechaInicioFiltro").value;
    const finalFiltro = document.getElementById("fechaFinalFiltro").value;
    const table = document.getElementById("procesosTable");
    const rows = Array.from(table.tBodies[0].rows);

    rows.forEach(row => {
        const fechaInicio = row.cells[3].innerText.trim();
        const fechaFinal = row.cells[4].innerText.trim();

        const fechaInicioDate = parseDate(fechaInicio);
        const fechaFinalDate = parseDate(fechaFinal);

        let visible = true;

        if (inicioFiltro && fechaInicioDate) visible = visible && (fechaInicioDate >= new Date(inicioFiltro));
        if (finalFiltro && fechaFinalDate) visible = visible && (fechaFinalDate <= new Date(finalFiltro));

        row.style.display = visible ? "" : "none";
    });
}
</script>
@endpush
