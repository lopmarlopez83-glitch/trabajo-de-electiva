<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOficinaRequest;
use App\Http\Requests\UpdateOficinaRequest;
use App\Models\Oficina;

class OficinaController extends Controller
{
    public function index()
    {
        $oficinas = Oficina::latest()->paginate(10);
        return view('oficinas.index', compact('oficinas'));
    }

    public function create()
    {
        return view('oficinas.create');
    }

    public function store(StoreOficinaRequest $request)
    {
        Oficina::create($request->validated());
        return redirect()->route('oficinas.index')->with('success', 'Oficina creada correctamente.');
    }

    public function show(Oficina $oficina)
    {
        $oficina->loadCount('users', 'solicitudes');
        return view('oficinas.show', compact('oficina'));
    }

    public function edit(Oficina $oficina)
    {
        return view('oficinas.edit', compact('oficina'));
    }

    public function update(UpdateOficinaRequest $request, Oficina $oficina)
    {
        $oficina->update($request->validated());
        return redirect()->route('oficinas.index')->with('success', 'Oficina actualizada correctamente.');
    }

    public function destroy(Oficina $oficina)
    {
        // Evitar borrar oficinas con usuarios o solicitudes asociadas
        if ($oficina->users()->exists() || $oficina->solicitudes()->exists()) {
            return back()->with('error', 'No se puede eliminar: la oficina tiene usuarios o solicitudes asociadas.');
        }
        $oficina->delete();
        return redirect()->route('oficinas.index')->with('success', 'Oficina eliminada correctamente.');
    }
}