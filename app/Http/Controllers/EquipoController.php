<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Models\Equipo;
use App\Models\Modelo;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with(['modelo.marca', 'modelo.categoria'])
            ->latest()
            ->paginate(10);
        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        $modelos = Modelo::with(['marca', 'categoria'])->orderBy('nombre')->get();
        return view('equipos.create', compact('modelos'));
    }

    public function store(StoreEquipoRequest $request)
    {
        Equipo::create($request->validated());
        return redirect()->route('equipos.index')->with('success', 'Equipo registrado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->load(['modelo.marca', 'modelo.categoria', 'entregas.user', 'entregas.oficina']);
        return view('equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        $modelos = Modelo::with(['marca', 'categoria'])->orderBy('nombre')->get();
        return view('equipos.edit', compact('equipo', 'modelos'));
    }

    public function update(UpdateEquipoRequest $request, Equipo $equipo)
    {
        $equipo->update($request->validated());
        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        // Solo se puede dar de baja si está disponible (no prestado/asignado)
        if (!in_array($equipo->status, [Equipo::STATUS_DISPONIBLE, Equipo::STATUS_EN_MANTENIMIENTO])) {
            return back()->with('error', 'No se puede eliminar un equipo actualmente prestado o asignado.');
        }
        $equipo->delete();
        return redirect()->route('equipos.index')->with('success', 'Equipo dado de baja correctamente.');
    }
}