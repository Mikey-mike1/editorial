@extends('layouts.app')

@section('content')
<h2 style="text-align:center;">Cronograma — Proceso: {{ $proceso->tipo }}</h2>
<small style="display:block; text-align:center;">Cliente: {{ $proceso->cliente->nombre }} — ID: {{ $proceso->id }}</small>

<div id="timeline" style="height: 400px; margin: 20px;"></div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['timeline']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var container = document.getElementById('timeline');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();

    // columnas: fila, etiqueta, tooltip(html), inicio, fin
    dataTable.addColumn({ type: 'string', id: 'Row' });
    dataTable.addColumn({ type: 'string', id: 'Label' });
    dataTable.addColumn({ type: 'string', role: 'tooltip', 'p': {'html': true} });
    dataTable.addColumn({ type: 'date', id: 'Start' });
    dataTable.addColumn({ type: 'date', id: 'End' });

    dataTable.addRows([
        // Nodo inicio proceso (uso endOfDay si no hay fecha_final)
        @php
            use Carbon\Carbon;
            $procStart = Carbon::parse($proceso->fecha_inicio)->toIso8601String();
            if($proceso->fecha_final) {
                $procEnd = Carbon::parse($proceso->fecha_final)->toIso8601String();
            } else {
                $procEnd = Carbon::parse($proceso->fecha_inicio)->endOfDay()->toIso8601String();
            }
            $procTooltip = "<div style='padding:8px;max-width:300px;'>
                                <strong>Proceso: </strong>" . e($proceso->tipo) . "<br/>
                                <strong>Cliente: </strong>" . e($proceso->cliente->nombre) . "<br/>
                                <strong>Inicio: </strong>" . e($proceso->fecha_inicio) . "<br/>" .
                                ($proceso->fecha_final ? "<strong>Fin: </strong>" . e($proceso->fecha_final) : "<strong>Fin: </strong>--") .
                           "</div>";
        @endphp
        ['Proceso', 'Inicio', {!! json_encode($procTooltip) !!}, new Date("{{ $procStart }}"), new Date("{{ $procEnd }}") ],

        // Cambios (ordenados por fecha_inicio)
        @foreach($proceso->cambios->sortBy('fecha_inicio') as $cambio)
            @php
                $cStart = \Carbon\Carbon::parse($cambio->fecha_inicio)->toIso8601String();
                if($cambio->fecha_final) {
                    $cEnd = \Carbon\Carbon::parse($cambio->fecha_final)->toIso8601String();
                } else {
                    // si no hay fecha_final, usamos el fin del mismo día para que sea visible
                    $cEnd = \Carbon\Carbon::parse($cambio->fecha_inicio)->endOfDay()->toIso8601String();
                }

                $cTooltip = "<div style='padding:8px;max-width:300px;'>
                                <strong>Cambio:</strong> " . e($cambio->descripcion) . "<br/>
                                <strong>Responsable:</strong> " . (isset($cambio->responsable) ? e($cambio->responsable) : '--') . "<br/>
                                <strong>Inicio:</strong> " . e($cambio->fecha_inicio) . "<br/>" .
                                ($cambio->fecha_final ? "<strong>Fin:</strong> " . e($cambio->fecha_final) : "<strong>Fin:</strong> --") .
                            "</div>";
            @endphp
            ['Cambio', {!! json_encode(\Illuminate\Support\Str::limit($cambio->descripcion, 40)) !!}, {!! json_encode($cTooltip) !!}, new Date("{{ $cStart }}"), new Date("{{ $cEnd }}") ],
        @endforeach

        // Nodo fin proceso (si existe; si no, ya se creó arriba como endOfDay)
        @if($proceso->fecha_final)
            @php
                $procFinTooltip = "<div style='padding:8px;max-width:300px;'>
                                    <strong>Fin del proceso</strong><br/>
                                    <strong>Fecha:</strong> " . e($proceso->fecha_final) . "
                                   </div>";
            @endphp
            ['Proceso', 'Fin', {!! json_encode($procFinTooltip) !!}, new Date("{{ $procEnd }}"), new Date("{{ $procEnd }}") ],
        @endif
    ]);

    var options = {
        timeline: { showRowLabels: true },
        avoidOverlappingGridLines: false,
        tooltip: { isHtml: true, trigger: 'focus' }, // permite tooltip HTML y que se vea al hover/focus
        height: 400
    };

    chart.draw(dataTable, options);
}
</script>
@endpush
