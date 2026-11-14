<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->esAdmin()) {
            // Indicadores en tiempo real (una sola query por métrica, sin N+1)
            $disponibles     = Equipo::where('status', Equipo::STATUS_DISPONIBLE)->count();
            $mantenimiento   = Equipo::where('status', Equipo::STATUS_EN_MANTENIMIENTO)->count();
            $prestados       = Equipo::where('status', Equipo::STATUS_PRESTADO)->count();
            $asignados       = Equipo::where('status', Equipo::STATUS_ASIGNADO)->count();
            $pendientes      = Solicitud::where('status', Solicitud::STATUS_PENDIENTE)->count();
            $totalEquipos    = Equipo::count();
            $totalSolicitudes= Solicitud::count();

            // Últimas 5 solicitudes pendientes con eager loading
            $ultimasPendientes = Solicitud::with(['user.oficina', 'oficina'])
                ->where('status', Solicitud::STATUS_PENDIENTE)
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'disponibles', 'mantenimiento', 'prestados', 'asignados',
                'pendientes', 'totalEquipos', 'totalSolicitudes', 'ultimasPendientes'
            ));
        }

        // Dashboard del solicitante: sus propias solicitudes
        $misSolicitudes = Solicitud::with(['oficina'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $misPendientes = Solicitud::where('user_id', $user->id)
            ->where('status', Solicitud::STATUS_PENDIENTE)
            ->count();

        $misAprobadas = Solicitud::where('user_id', $user->id)
            ->where('status', Solicitud::STATUS_APROBADA)
            ->count();

        $misRechazadas = Solicitud::where('user_id', $user->id)
            ->where('status', Solicitud::STATUS_RECHAZADA)
            ->count();

        return view('dashboard', compact(
            'misSolicitudes', 'misPendientes', 'misAprobadas', 'misRechazadas'
        ));
    }
}