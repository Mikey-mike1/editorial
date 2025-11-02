<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cambio;
use App\Models\Proceso;
use App\Models\Editor;
use Illuminate\Support\Facades\Auth;

class CambioController extends Controller
{
    // Listado de cambios
    public function index()
    {
        $cambios = Cambio::with(['proceso.cliente', 'editor'])->orderBy('fecha_inicio', 'desc')->get();
        return view('admin.cambios.index', compact('cambios'));
    }

    // Crear cambio
    public function create()
    {
        $procesos = Proceso::all();
        $editores = Editor::all(); // tabla editores
        return view('admin.cambios.create', compact('procesos', 'editores'));
    }

    // Guardar cambio
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proceso_id' => 'required|exists:procesos,id',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'nullable|date',
            'estado' => 'required|in:En Revision,Finalizado,Pendiente,Entregado',
            'observaciones' => 'nullable|string',
            'editor_id' => 'required|exists:editores,id' // tabla editores
        ]);

        Cambio::create($validated);

        return redirect()->route('cambios.index')->with('success', 'Cambio creado correctamente.');
    }

    // Mostrar detalle
    public function show(Cambio $cambio)
    {
        $cambio->load('proceso.cliente', 'editor');
        return view('admin.cambios.show', compact('cambio'));
    }

    // Editar cambio
    public function edit(Cambio $cambio)
    {
        $procesos = Proceso::all();
        $editores = Editor::all(); // tabla editores
        return view('admin.cambios.edit', compact('cambio', 'procesos', 'editores'));
    }

    // Actualizar cambio
    public function update(Request $request, Cambio $cambio)
    {
        $validated = $request->validate([
            'proceso_id' => 'required|exists:procesos,id',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'nullable|date',
            'estado' => 'required|in:En Revision,Finalizado,Pendiente,Entregado',
            'observaciones' => 'nullable|string',
            'editor_id' => 'required|exists:editores,id'
        ]);

        $cambio->update($validated);

        return redirect()->route('cambios.index')->with('success', 'Cambio actualizado correctamente.');
    }

    // Eliminar cambio
    public function destroy(Cambio $cambio)
    {
        $cambio->delete();
        return redirect()->route('cambios.index')->with('success', 'Cambio eliminado correctamente.');
    }
}
