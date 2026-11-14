<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Entrega::with(['solicitud', 'equipo.modelo.marca', 'user', 'oficina']);

        // Solicitante: solo ve sus propias entregas
        if ($user->esSolicitante()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $entregas = $query->latest('fecha_entrega')->paginate(15)->withQueryString();

        $resumen = [
            'prestamos_activos'    => Entrega::when($user->esSolicitante(), fn ($q) => $q->where('user_id', $user->id))
                                              ->where('tipo', 'prestamo')
                                              ->where('status', Entrega::STATUS_ACTIVA)
                                              ->count(),
            'asignaciones_activas' => Entrega::when($user->esSolicitante(), fn ($q) => $q->where('user_id', $user->id))
                                              ->where('tipo', 'asignacion')
                                              ->where('status', Entrega::STATUS_ACTIVA)
                                              ->count(),
            'devueltas'            => Entrega::when($user->esSolicitante(), fn ($q) => $q->where('user_id', $user->id))
                                              ->where('status', Entrega::STATUS_DEVUELTA)
                                              ->count(),
        ];

        return view('entregas.index', compact('entregas', 'resumen'));
    }

    public function devolver(Entrega $entrega)
    {
        if ($entrega->status === Entrega::STATUS_DEVUELTA) {
            return back()->with('error', 'Esta entrega ya fue devuelta.');
        }

        try {
            DB::transaction(function () use ($entrega) {
                // Marcar entrega como devuelta
                $entrega->update([
                    'status'                => Entrega::STATUS_DEVUELTA,
                    'fecha_devolucion_real' => now(),
                ]);

                // Liberar equipo
                $entrega->equipo->update(['status' => Equipo::STATUS_DISPONIBLE]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al devolver: ' . $e->getMessage());
        }

        return redirect()->route('entregas.index')
            ->with('success', 'Equipo devuelto y disponible nuevamente.');
    }
}