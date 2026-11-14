<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipos';

    protected $fillable = [
        'serial',
        'codigo_patrimonial',
        'modelo_id',
        'status',
        'observaciones',
    ];

    // Constantes de status (para usar en código)
    const STATUS_DISPONIBLE       = 'disponible';
    const STATUS_EN_MANTENIMIENTO = 'en_mantenimiento';
    const STATUS_PRESTADO         = 'prestado';
    const STATUS_ASIGNADO         = 'asignado';

    // Relaciones
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    public function solicitudes()
    {
        return $this->belongsToMany(Solicitud::class, 'solicitud_equipo')
                    ->withTimestamps();
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }

    // Helpers
    public function estaDisponible(): bool
    {
        return $this->status === self::STATUS_DISPONIBLE;
    }
}