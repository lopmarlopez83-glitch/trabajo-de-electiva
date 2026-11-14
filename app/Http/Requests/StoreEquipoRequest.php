<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'serial'             => ['required', 'string', 'max:100', 'unique:equipos,serial'],
            'codigo_patrimonial' => ['nullable', 'string', 'max:100', 'unique:equipos,codigo_patrimonial'],
            'modelo_id'          => ['required', 'exists:modelos,id'],
            'status'             => ['required', 'in:disponible,en_mantenimiento,prestado,asignado'],
            'observaciones'      => ['nullable', 'string'],
        ];
    }
}