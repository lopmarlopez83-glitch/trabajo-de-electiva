<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Http\Requests\UpdateSolicitudRequest;
use App\Http\Requests\AprobarSolicitudRequest;
use App\Models\Categoria;
use App\Models\Entrega;
use App\Models\Equipo;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // ✅ Incluimos categoria en el eager loading
        $query = Solicitud::with(['user.oficina', 'oficina', 'categoria']);

        if ($user->esSolicitante()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $solicitudes = $query->latest()->paginate(10)->withQueryString();

        return view('solicitudes.index', compact('solicitudes'));
    }

    public function create(Request $request)
    {
        $user = $request->user();

        // ✅ Pasar categorías y equipos disponibles para el selector dinámico
        $categorias = Categoria::orderBy('nombre')->get();

        $equiposDisponibles = Equipo::with(['modelo.marca', 'modelo.categoria'])
            ->where('status', Equipo::STATUS_DISPONIBLE)
            ->orderBy('serial')
            ->get();

        return view('solicitudes.create', compact('user', 'categorias', 'equiposDisponibles'));
    }

    public function store(StoreSolicitudRequest $request)
    {
        $user = $request->user();

        Solicitud::create([
            'user_id'         => $user->id,
            'oficina_id'      => $user->oficina_id,
            'categoria_id'    => $request->categoria_id, // ✅ NUEVO
            'fecha_solicitud' => now()->toDateString(),
            'fecha_requerida' => $request->fecha_requerida,
            'fecha_fin'       => $request->fecha_fin,
            'cantidad'        => $request->cantidad,
            'tipo'            => $request->tipo,
            'status'          => Solicitud::STATUS_PENDIENTE,
        ]);

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud creada correctamente.');
    }

    public function show(Solicitud $solicitud)
    {
        if (auth()->user()->esSolicitante() && $solicitud->user_id !== auth()->id()) {
            abort(403);
        }

        // ✅ Cargamos la categoría también
        $solicitud->load(['user.oficina', 'oficina', 'categoria', 'equipos.modelo.marca', 'equipos.modelo.categoria']);

        $equiposDisponibles = [];
        if (auth()->user()->esAdmin() && $solicitud->status === Solicitud::STATUS_PENDIENTE) {
            // ✅ Filtramos solo equipos disponibles de la MISMA categoría
            $equiposDisponibles = Equipo::with(['modelo.marca', 'modelo.categoria'])
                ->where('status', Equipo::STATUS_DISPONIBLE)
                ->whereHas('modelo', function ($q) use ($solicitud) {
                    $q->where('categoria_id', $solicitud->categoria_id);
                })
                ->orderBy('serial')
                ->get();
        }

        return view('solicitudes.show', compact('solicitud', 'equiposDisponibles'));
    }

    public function edit(Solicitud $solicitud)
    {
        if ($solicitud->user_id !== auth()->id() || !$solicitud->esEditable()) {
            abort(403, 'No puedes editar esta solicitud.');
        }

        // ✅ Pasar categorías al formulario de edición
        $categorias = Categoria::orderBy('nombre')->get();

        return view('solicitudes.edit', compact('solicitud', 'categorias'));
    }

    public function update(UpdateSolicitudRequest $request, Solicitud $solicitud)
    {
        if ($solicitud->user_id !== auth()->id() || !$solicitud->esEditable()) {
            abort(403, 'No puedes editar esta solicitud.');
        }

        $solicitud->update($request->validated());

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud actualizada correctamente.');
    }

    public function destroy(Solicitud $solicitud)
{
    $user = auth()->user();

    // ✅ Admin: puede eliminar cualquier solicitud (excepto aprobada)
    if ($user->esAdmin()) {
        if ($solicitud->status === Solicitud::STATUS_APROBADA) {
            return back()->with('error', 'No se puede eliminar una solicitud ya aprobada.');
        }
        $solicitud->delete();
        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }

    // ✅ Solicitante: solo sus propias solicitudes y solo si están pendientes
    if ($user->esSolicitante()) {
        if ($solicitud->user_id !== $user->id) {
            abort(403, 'No puedes eliminar solicitudes de otros usuarios.');
        }
        if (!$solicitud->esEditable()) {
            return back()->with('error', 'Solo puedes cancelar solicitudes pendientes.');
        }
        $solicitud->delete();
        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud cancelada correctamente.');
    }

    abort(403);
}

    /* ================== APROBAR ================== */
    public function aprobar(AprobarSolicitudRequest $request, Solicitud $solicitud)
    {
        if ($solicitud->status !== Solicitud::STATUS_PENDIENTE) {
            return back()->with('error', 'Solo se pueden aprobar solicitudes pendientes.');
        }

        if (count($request->equipo_ids) !== $solicitud->cantidad) {
            return back()->with('error', "Debes seleccionar exactamente {$solicitud->cantidad} equipo(s).");
        }

        try {
            DB::transaction(function () use ($solicitud, $request) {
                $equipos = Equipo::whereIn('id', $request->equipo_ids)
                    ->where('status', Equipo::STATUS_DISPONIBLE)
                    ->lockForUpdate()
                    ->get();

                if ($equipos->count() !== count($request->equipo_ids)) {
                    throw new \Exception('Alguno de los equipos ya no está disponible.');
                }

                $nuevoStatus = $solicitud->esPrestamo()
                    ? Equipo::STATUS_PRESTADO
                    : Equipo::STATUS_ASIGNADO;

                foreach ($equipos as $equipo) {
                    $equipo->update(['status' => $nuevoStatus]);

                    Entrega::create([
                        'solicitud_id'              => $solicitud->id,
                        'equipo_id'                 => $equipo->id,
                        'user_id'                   => $solicitud->user_id,
                        'oficina_id'                => $solicitud->oficina_id,
                        'tipo'                      => $solicitud->tipo,
                        'fecha_entrega'             => now(),
                        'fecha_devolucion_esperada' => $solicitud->fecha_fin,
                        'status'                    => Entrega::STATUS_ACTIVA,
                    ]);
                }

                $solicitud->equipos()->sync($request->equipo_ids);
                $solicitud->update(['status' => Solicitud::STATUS_APROBADA]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al aprobar: ' . $e->getMessage());
        }

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud aprobada y equipos asignados correctamente.');
    }

    /* ========================== RECHAZAR ========================== */
    public function rechazar(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'motivo_rechazo' => ['required', 'string', 'max:500'],
        ]);

        if ($solicitud->status !== Solicitud::STATUS_PENDIENTE) {
            return back()->with('error', 'Solo se pueden rechazar solicitudes pendientes.');
        }

        $solicitud->update([
            'status'         => Solicitud::STATUS_RECHAZADA,
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud rechazada.');
    }
}