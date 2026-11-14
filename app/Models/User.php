<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'email',
        'oficina_id',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // Constantes de rol
    const ROLE_ADMIN       = 'admin';
    const ROLE_SOLICITANTE = 'solicitante';

    // Relaciones
    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }

    // Helpers
    public function esAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function esSolicitante(): bool
    {
        return $this->role === self::ROLE_SOLICITANTE;
    }

    // Nombre completo (opcional, útil en vistas)
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}