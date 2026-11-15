<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Http\Requests\UpdateSolicitudRequest;
use App\Http\Requests\AprobarSolicitudRequest;
use App\Models\Entrega;
use App\Models\Equipo;
use App\Models\Oficina;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Solicitud::with(['user.oficina', 'oficina']);

        // El solicitante solo ve las suyas
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

        // ✅ Si es admin, cargar oficinas para que elija; si no, colección vacía
        $oficinas = $user->esAdmin()
            ? Oficina::orderBy('nombre')->get()
            : collect();

        return view('solicitudes.create', compact('user', 'oficinas'));
    }

    public function store(StoreSolicitudRequest $request)
    {
        $user = $request->user();

        // ✅ Si es admin usa la oficina seleccionada; si es solicitante, la suya
        $oficinaId = $user->esAdmin()
            ? $request->oficina_id
            : $user->oficina_id;

        Solicitud::create([
            'user_id'          => $user->id,
            'oficina_id'       => $oficinaId,
            'fecha_solicitud'  => now()->toDateString(),
            'fecha_requerida'  => $request->fecha_requerida,
            'fecha_fin'        => $request->fecha_fin,
            'cantidad'         => $request->cantidad,
            'tipo'             => $request->tipo,
            'status'           => Solicitud::STATUS_PENDIENTE,
        ]);

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud creada correctamente.');
    }

    public function show(Solicitud $solicitud)
    {
        // Un solicitante no puede ver solicitudes ajenas
        if (auth()->user()->esSolicitante() && $solicitud->user_id !== auth()->id()) {
            abort(403);
        }

        $solicitud->load(['user.oficina', 'oficina', 'equipos.modelo.marca', 'equipos.modelo.categoria']);

        // Equipos disponibles para aprobar (si es admin y está pendiente)
        $equiposDisponibles = [];
        if (auth()->user()->esAdmin() && $solicitud->status === Solicitud::STATUS_PENDIENTE) {
            $equiposDisponibles = Equipo::with(['modelo.marca', 'modelo.categoria'])
                ->where('status', Equipo::STATUS_DISPONIBLE)
                ->orderBy('serial')
                ->get();
        }

        return view('solicitudes.show', compact('solicitud', 'equiposDisponibles'));
    }

    public function edit(Solicitud $solicitud)
    {
        // Solo el dueño y solo si está pendiente
        if ($solicitud->user_id !== auth()->id() || !$solicitud->esEditable()) {
            abort(403, 'No puedes editar esta solicitud.');
        }
        return view('solicitudes.edit', compact('solicitud'));
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
        // Solo admin puede eliminar (según tu lógica)
        if (!auth()->user()->esAdmin()) {
            abort(403);
        }

        if ($solicitud->status === Solicitud::STATUS_APROBADA) {
            return back()->with('error', 'No se puede eliminar una solicitud ya aprobada.');
        }

        $solicitud->delete();
        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }

    /* ================== APROBAR (con DB::transaction) ================== */
    public function aprobar(AprobarSolicitudRequest $request, Solicitud $solicitud)
    {
        if ($solicitud->status !== Solicitud::STATUS_PENDIENTE) {
            return back()->with('error', 'Solo se pueden aprobar solicitudes pendientes.');
        }

        // Validar que la cantidad de equipos seleccionados coincida
        if (count($request->equipo_ids) !== $solicitud->cantidad) {
            return back()->with('error', "Debes seleccionar exactamente {$solicitud->cantidad} equipo(s).");
        }

        try {
            DB::transaction(function () use ($solicitud, $request) {
                // Verificar que todos estén disponibles (lock para evitar race conditions)
                $equipos = Equipo::whereIn('id', $request->equipo_ids)
                    ->where('status', Equipo::STATUS_DISPONIBLE)
                    ->lockForUpdate()
                    ->get();

                if ($equipos->count() !== count($request->equipo_ids)) {
                    throw new \Exception('Alguno de los equipos ya no está disponible.');
                }

                // Cambiar status de cada equipo según tipo
                $nuevoStatus = $solicitud->esPrestamo()
                    ? Equipo::STATUS_PRESTADO
                    : Equipo::STATUS_ASIGNADO;

                foreach ($equipos as $equipo) {
                    $equipo->update(['status' => $nuevoStatus]);

                    // Registrar entrega
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

                // Asociar equipos a la solicitud (pivote)
                $solicitud->equipos()->sync($request->equipo_ids);

                // Actualizar estado de la solicitud
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