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
        'modelo_id',
        'cpu',
        'ram',
        'almacenamiento',
        'status',
        'observaciones',
    ];

    // Constantes de status
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

    public function requiereEspecificaciones(): bool
    {
        $cat = strtolower($this->modelo->categoria->nombre ?? '');
        return in_array($cat, ['computadora de mesa', 'laptop']);
    }
}