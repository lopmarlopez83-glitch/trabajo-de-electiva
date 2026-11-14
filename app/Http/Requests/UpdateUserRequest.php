<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombres'    => ['required', 'string', 'max:100'],
            'apellidos'  => ['required', 'string', 'max:100'],
            'oficina_id' => ['required', 'exists:oficinas,id'],
            'role'       => ['required', 'in:admin,solicitante'],
            'password'   => ['nullable', 'confirmed', Password::min(8)],
        ];
        // Nota: NO se permite editar email ni cedula (según tu lógica)
    }
}