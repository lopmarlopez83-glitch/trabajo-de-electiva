<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('equipo')->id ?? $this->route('equipo');

        return [
            'serial'             => ['required', 'string', 'max:100', Rule::unique('equipos', 'serial')->ignore($id)],
            'codigo_patrimonial' => ['nullable', 'string', 'max:100', Rule::unique('equipos', 'codigo_patrimonial')->ignore($id)],
            'modelo_id'          => ['required', 'exists:modelos,id'],
            'status'             => ['required', 'in:disponible,en_mantenimiento,prestado,asignado'],
            'observaciones'      => ['nullable', 'string'],
        ];
    }
}