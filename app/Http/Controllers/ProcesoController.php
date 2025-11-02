<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Editor;

class ProcesoController extends Controller
{
    /**
     * Dashboard: muestra procesos con búsqueda por cliente o tipo
     */
    public function adminDashboard(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado');
        }

        $search = $request->input('search');

        $procesos = Proceso::with('cliente')
            ->withCount('cambios')
            ->when($search, function ($query, $search) {
                $query->whereHas('cliente', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%");
                })
                    ->orWhere('tipo', 'like', "%$search%");
            })
            ->orderBy('estado')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('admin.dashboard', compact('procesos', 'search'));
    }

    /**
     * Listado de procesos de un cliente
     */
    public function procesosCliente($cliente_id)
    {
        $cliente = Cliente::with(['procesos.editor', 'procesos.cambios'])->findOrFail($cliente_id);

        return view('admin.procesos_cliente', compact('cliente'));
    }

    /**
     * Listado global de procesos
     */
    public function index()
    {
        $procesos = Proceso::with('cliente')->orderBy('fecha_inicio', 'desc')->get();
        return view('admin.procesos.index', compact('procesos'));
    }

    /**
     * Formulario para crear proceso
     */
public function create()
{
    $clientes = Cliente::orderBy('nombre')->get();
    $editores = Editor::orderBy('nombre')->get(); // Trae todos los editores
    return view('admin.procesos.create', compact('clientes', 'editores'));
}


    /**
     * Guardar nuevo proceso
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'estado' => 'required|string|in:En Revision,Finalizado,Pendiente,Entregado',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'nullable|date|after_or_equal:fecha_inicio',
            'editor_id'  => 'required|exists:editores,id'
        ]);

        Proceso::create($validated);

        return redirect()->route('procesos.index')->with('success', 'Proceso creado correctamente.');
    }

    /**
     * Formulario para editar proceso
     */
    public function edit(Proceso $proceso)
    {
        return view('admin.procesos.edit', compact('proceso'));
    }

    /**
     * Actualizar proceso
     */
    public function update(Request $request, Proceso $proceso)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'estado' => 'required|string|in:En Revision,Finalizado,Pendiente,Entregado',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $proceso->update($validated);

        return redirect()->route('procesos.index')->with('success', 'Proceso actualizado correctamente.');
    }

    /**
     * Eliminar proceso
     */
    public function destroy(Proceso $proceso)
    {
        $proceso->delete();
        return redirect()->route('procesos.index')->with('success', 'Proceso eliminado correctamente.');
    }

    /**
     * Muestra los detalles del proceso junto con sus cambio
     */

    public function show(Proceso $proceso)
    {
        $proceso->load(['cliente', 'editor', 'cambios' => function ($q) {
            $q->orderBy('created_at', 'asc'); // Orden cronológico
        }]);

        return view('admin.procesos.show', compact('proceso'));
    }

public function flujograma($id)
{
    $proceso = Proceso::with('cambios')->findOrFail($id);
    return view('admin.procesos.flujograma', compact('proceso'));
}

// Mostrar la vista principal
public function cronometro()
{
    $procesos = Proceso::with('cliente')->get();
    return view('admin.cronometro', compact('procesos'));
}

// Retornar solo el tbody para AJAX
public function cronometroData()
{
    $procesos = Proceso::with('cliente')->get();
    return view('admin.partials.cronometro_body', compact('procesos'));
}




}
