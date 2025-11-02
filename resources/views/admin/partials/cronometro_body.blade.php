@foreach($procesos as $proceso)
    @if($proceso->estado !== 'Finalizado')
    <tr class="process-row
    ">
        <td>{{ $proceso->tipo }}</td>
        <td>{{ $proceso->descripcion }}</td>
        <td>{{ $proceso->cliente->nombre }}</td>
        <td>{{ $proceso->estado }}</td>
        <td>{{ $proceso->fecha_final }}</td>
        <td data-fecha="{{ $proceso->fecha_final }}">{{ \Carbon\Carbon::parse($proceso->fecha_final)->diffForHumans() }}</td>
    </tr>
    @endif
@endforeach
