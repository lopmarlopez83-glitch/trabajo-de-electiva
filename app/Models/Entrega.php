<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrega extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entregas';

    protected $fillable = [
        'solicitud_id',
        'equipo_id',
        'user_id',
        'oficina_id',
        'tipo',
        'fecha_entrega',
        'fecha_devolucion_esperada',
        'fecha_devolucion_real',
        'status',
    ];

    protected $casts = [
        'fecha_entrega'             => 'datetime',
        'fecha_devolucion_esperada' => 'datetime',
        'fecha_devolucion_real'     => 'datetime',
    ];

    const STATUS_ACTIVA   = 'activa';
    const STATUS_DEVUELTA = 'devuelta';

    // Relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    // Helpers
    public function esPrestamo(): bool
    {
        return $this->tipo === Solicitud::TIPO_PRESTAMO;
    }
}