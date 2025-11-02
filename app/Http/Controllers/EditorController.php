<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Editor;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function index()
    {
        $editores = Editor::with(['procesos', 'cambios'])->get();
        return view('admin.editores.index', compact('editores'));
    }

    public function create()
    {
        return view('admin.editores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        Editor::create($request->all());
        return redirect()->route('admin.editores.index')->with('success', 'Editor creado correctamente.');
    }

    public function edit(Editor $editor)
    {
        return view('admin.editores.edit', compact('editor'));
    }

    public function update(Request $request, Editor $editor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $editor->update($request->all());
        return redirect()->route('admin.editores.index')->with('success', 'Editor actualizado correctamente.');
    }

    public function destroy(Editor $editor)
    {
        $editor->delete();
        return redirect()->route('admin.editores.index')->with('success', 'Editor eliminado correctamente.');
    }
}
