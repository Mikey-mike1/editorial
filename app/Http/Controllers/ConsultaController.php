<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Proceso;
use App\Models\Cambio;

class ConsultaController extends Controller
{
    // Mostrar formulario de consulta
    public function form()
    {
        return view('consulta.form');
    }

    // Procesar formulario y mostrar resultados
    public function resultados(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits_between:13,13',
            'celular' => 'required|digits_between:8,8',
        ]);

        $dni = $request->dni;
        $celular = $request->celular;

        // Buscar cliente
        $cliente = Cliente::where('dni', $dni)
                          ->where('celular', $celular)
                          ->first();

        if (!$cliente) {
            return back()->withErrors(['No se encontró ningún cliente con esos datos.']);
        }

        // Obtener procesos asociados
        $procesos = Proceso::where('cliente_id', $cliente->id)
                            ->orderBy('fecha_final', 'asc')
                            ->get();

        // Obtener cambios asociados a esos procesos
        $cambios = Cambio::whereIn('proceso_id', $procesos->pluck('id'))->orderBy('fecha_final', 'asc')->get();

        return view('consulta.resultados', compact('cliente', 'procesos', 'cambios'));
    }
}
