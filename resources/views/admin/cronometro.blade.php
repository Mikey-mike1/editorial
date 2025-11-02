@extends('layouts.app')

@section('content')
<h4>Procesos con Cronómetro</h4>

<div class="info-box">
    <p style="margin:0;">
        En esta pantalla puedes visualizar todos los procesos con un cronómetro que indica cuánto falta para la fecha de entrega.
        Los procesos finalizados no se muestran.
        La tabla se actualizará automáticamente sin necesidad de recargar la página.
    </p>
</div>

{{-- Explicación de colores --}}
<div class="color-legend" style="margin-top:15px; background:#f9f9f9; padding:10px; border-radius:8px;">
    <strong>Significado de los colores:</strong>
    <ul style="margin-top:8px; list-style:none; padding-left:0;">
        <li><span style="background:#ffebee; padding:4px 8px; border-radius:4px;">🔴 Rojo:</span> Proceso vencido. Requiere atención inmediata.</li>
        <li><span style="background:#fff3e0; padding:4px 8px; border-radius:4px;">🟠 Naranja:</span> Falta menos de 1 día para la entrega.</li>
        <li><span style="background:#fffde7; padding:4px 8px; border-radius:4px;">🟡 Amarillo:</span> Faltan entre 1 y 3 días.</li>
        <li><span style="background:#e8f5e9; padding:4px 8px; border-radius:4px;">🟢 Verde:</span> Faltan más de 3 días.</li>
    </ul>
</div>

<button id="btnToggleVoz" class="btn green darken-1 waves-effect waves-light">
    <i class="material-icons left">volume_up</i> Voz activada
</button>

<table id="procesosTable" class="striped responsive-table" style="margin-top:15px;">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Cliente</th>
            <th>Estado</th>
            <th>Fecha Final</th>
            <th>Tiempo Restante</th>
        </tr>
    </thead>
    <tbody id="procesosBody">
        @include('admin.partials.cronometro_body', ['procesos' => $procesos])
    </tbody>
</table>
@endsection

@push('scripts')
<script>
// ---- VARIABLES GLOBALES ----
let vozActiva = true; // por defecto la voz está activada

// ---- FUNCIONES ----

// Actualiza cronómetros y colorea filas
function actualizarCronometros() {
    const filas = Array.from(document.querySelectorAll('#procesosBody tr'));
    const ahora = new Date();
    let vencidos = [];

    filas.forEach(tr => {
        const td = tr.querySelector('td[data-fecha]');
        if (!td) return;

        const fechaFinal = new Date(td.getAttribute('data-fecha'));
        const diff = fechaFinal - ahora;
        const celda = td;
        tr.style.fontWeight = 'normal';

        if (diff <= 0) {
            celda.innerText = '¡Vencido!';
            celda.classList.remove('orange-text', 'green-text');
            celda.classList.add('red-text');
            tr.style.backgroundColor = '#ffebee'; // rojo claro
            tr.style.fontWeight = 'bold';
            vencidos.push(tr);
        } else {
            const dias = Math.floor(diff / (1000 * 60 * 60 * 24));
            const horas = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const minutos = Math.floor((diff / (1000 * 60)) % 60);
            const segundos = Math.floor((diff / 1000) % 60);
            celda.innerText = `${dias}d ${horas}h ${minutos}m ${segundos}s`;

            // Colores según urgencia
            if (dias < 1) {
                tr.style.backgroundColor = '#fff3e0'; // naranja claro
                celda.classList.add('orange-text');
            } else if (dias < 3) {
                tr.style.backgroundColor = '#fffde7'; // amarillo claro
            } else {
                tr.style.backgroundColor = '#e8f5e9'; // verde claro
                celda.classList.add('green-text');
            }
        }
    });

    // Ordenar filas (vencidos primero)
    filas.sort((a, b) => {
        const fechaA = new Date(a.querySelector('td[data-fecha]').getAttribute('data-fecha'));
        const fechaB = new Date(b.querySelector('td[data-fecha]').getAttribute('data-fecha'));
        return fechaA - fechaB;
    });

    vencidos.forEach(v => v.parentNode.prepend(v));

    const cuerpo = document.getElementById('procesosBody');
    cuerpo.innerHTML = '';
    filas.forEach(f => cuerpo.appendChild(f));
}

// Refrescar tabla desde el servidor
async function refrescarTabla() {
    try {
        const response = await fetch("{{ route('admin.cronometro.data') }}");
        if (!response.ok) throw new Error('Error al obtener los datos');
        const data = await response.text();
        document.getElementById('procesosBody').innerHTML = data;
        actualizarCronometros();
    } catch (error) {
        console.error(error);
    }
}

// ---- VOZ ----
function anunciarVencidos() {
    if (!vozActiva) return; // no habla si la voz está desactivada

    const vencidos = document.querySelectorAll('#procesosBody td.red-text');

    window.speechSynthesis.cancel(); // Detiene cualquier voz anterior

    if (vencidos.length > 0) {
        vencidos.forEach(td => {
            const fila = td.closest('tr');
            const descripcion = fila.cells[1]?.innerText.trim() || 'Tipo desconocido';
            const cliente = fila.cells[2]?.innerText.trim() || 'Cliente desconocido';

            const mensaje = `Atención. El proceso ${descripcion} del cliente ${cliente} está vencido.`;
            const utterance = new SpeechSynthesisUtterance(mensaje);
            utterance.lang = 'es-ES';
            window.speechSynthesis.speak(utterance);
        });
    }
}

// ---- TOGGLE DE VOZ ----
function toggleVoz() {
    vozActiva = !vozActiva;
    const boton = document.getElementById('btnToggleVoz');
    if (vozActiva) {
        boton.classList.remove('red', 'darken-1');
        boton.classList.add('green', 'darken-1');
        boton.innerHTML = `<i class="material-icons left">volume_up</i> Voz activada`;
        const u = new SpeechSynthesisUtterance('Alertas de voz activadas.');
        u.lang = 'es-ES';
        speechSynthesis.speak(u);
    } else {
        boton.classList.remove('green', 'darken-1');
        boton.classList.add('red', 'darken-1');
        boton.innerHTML = `<i class="material-icons left">volume_off</i> Voz desactivada`;
        speechSynthesis.cancel();
    }
}

// ---- EVENTOS ----
document.addEventListener('DOMContentLoaded', () => {
    actualizarCronometros();

    // Botón toggle
    document.getElementById('btnToggleVoz').addEventListener('click', toggleVoz);

    // Intervalos automáticos
    setInterval(actualizarCronometros, 1000);
    setInterval(refrescarTabla, 30000);
    setInterval(anunciarVencidos, 120000); //  cada 2 minutos exactos
});

</script>
@endpush
