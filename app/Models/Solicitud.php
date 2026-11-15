<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitud extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solicitudes';

    protected $fillable = [
        'user_id',
        'oficina_id',
        'categoria_id',  
        'fecha_solicitud',
        'fecha_requerida',
        'fecha_fin',
        'cantidad',
        'tipo',
        'status',
        'motivo_rechazo',
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_requerida' => 'date',
        'fecha_fin'       => 'date',
        'cantidad'        => 'integer',
    ];

    const STATUS_PENDIENTE  = 'pendiente';
    const STATUS_APROBADA   = 'aprobada';
    const STATUS_RECHAZADA  = 'rechazada';

    const TIPO_PRESTAMO   = 'prestamo';
    const TIPO_ASIGNACION = 'asignacion';

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'solicitud_equipo')
                    ->withTimestamps();
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }

    // Helpers
    public function esEditable(): bool
    {
        return $this->status === self::STATUS_PENDIENTE;
    }

    public function esPrestamo(): bool
    {
        return $this->tipo === self::TIPO_PRESTAMO;
    }
}