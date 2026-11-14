<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipo::with(['modelo.marca', 'modelo.categoria']);

        // Filtros opcionales
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial', 'ilike', "%{$search}%")
                  ->orWhere('codigo_patrimonial', 'ilike', "%{$search}%");
            });
        }

        $equipos = $query->latest()->paginate(15)->withQueryString();

        $resumen = [
            'disponible'       => Equipo::where('status', Equipo::STATUS_DISPONIBLE)->count(),
            'en_mantenimiento' => Equipo::where('status', Equipo::STATUS_EN_MANTENIMIENTO)->count(),
            'prestado'         => Equipo::where('status', Equipo::STATUS_PRESTADO)->count(),
            'asignado'         => Equipo::where('status', Equipo::STATUS_ASIGNADO)->count(),
        ];

        return view('inventario.index', compact('equipos', 'resumen'));
    }
}