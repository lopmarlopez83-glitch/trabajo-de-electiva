<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Entrega;
use App\Models\Equipo;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudSeeder extends Seeder
{
    public function run(): void
    {
        $solicitante = User::where('role', User::ROLE_SOLICITANTE)->first();
        if (!$solicitante) return;

        $categorias = Categoria::all();

        // 1. Solicitud Pendiente
        $catPendiente = $categorias->random();
        Solicitud::create([
            'user_id'         => $solicitante->id,
            'oficina_id'      => $solicitante->oficina_id,
            'categoria_id'    => $catPendiente->id,
            'fecha_solicitud' => now()->toDateString(),
            'fecha_requerida' => now()->addDays(3)->toDateString(),
            'fecha_fin'       => now()->addDays(10)->toDateString(),
            'cantidad'        => 2,
            'tipo'            => Solicitud::TIPO_PRESTAMO,
            'status'          => Solicitud::STATUS_PENDIENTE,
        ]);

        // 2. Solicitud Rechazada
        $catRechazada = $categorias->random();
        Solicitud::create([
            'user_id'         => $solicitante->id,
            'oficina_id'      => $solicitante->oficina_id,
            'categoria_id'    => $catRechazada->id,
            'fecha_solicitud' => now()->subDays(2)->toDateString(),
            'fecha_requerida' => now()->subDays(1)->toDateString(),
            'fecha_fin'       => null,
            'cantidad'        => 1,
            'tipo'            => Solicitud::TIPO_ASIGNACION,
            'status'          => Solicitud::STATUS_RECHAZADA,
            'motivo_rechazo'  => 'No hay equipos disponibles en esta categoría actualmente.',
        ]);

        // 3. Solicitud Aprobada (con entrega)
        $catAprobada = $categorias->random();
        $equipo = Equipo::whereHas('modelo', function($q) use ($catAprobada) {
            $q->where('categoria_id', $catAprobada->id);
        })->where('status', Equipo::STATUS_DISPONIBLE)->first();

        if ($equipo) {
            DB::transaction(function () use ($solicitante, $catAprobada, $equipo) {
                $solicitud = Solicitud::create([
                    'user_id'         => $solicitante->id,
                    'oficina_id'      => $solicitante->oficina_id,
                    'categoria_id'    => $catAprobada->id,
                    'fecha_solicitud' => now()->subDays(5)->toDateString(),
                    'fecha_requerida' => now()->subDays(4)->toDateString(),
                    'fecha_fin'       => now()->addDays(5)->toDateString(),
                    'cantidad'        => 1,
                    'tipo'            => Solicitud::TIPO_PRESTAMO,
                    'status'          => Solicitud::STATUS_APROBADA,
                ]);

                // Cambiar status del equipo
                $equipo->update(['status' => Equipo::STATUS_PRESTADO]);

                // Asociar equipo a la solicitud (pivote)
                $solicitud->equipos()->attach($equipo->id);

                // Crear entrega
                Entrega::create([
                    'solicitud_id'              => $solicitud->id,
                    'equipo_id'                 => $equipo->id,
                    'user_id'                   => $solicitante->id,
                    'oficina_id'                => $solicitante->oficina_id,
                    'tipo'                      => Solicitud::TIPO_PRESTAMO,
                    'fecha_entrega'             => now()->subDays(4),
                    'fecha_devolucion_esperada' => now()->addDays(5),
                    'status'                    => Entrega::STATUS_ACTIVA,
                ]);
            });
        }
    }
}