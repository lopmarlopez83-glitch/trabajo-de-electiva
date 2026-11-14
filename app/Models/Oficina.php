<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oficina extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'oficinas';

    protected $fillable = [
        'nombre',
        'ubicacion',
    ];

    // Relaciones
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}