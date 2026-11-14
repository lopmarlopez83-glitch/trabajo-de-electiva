<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombres'    => ['required', 'string', 'max:100'],
            'apellidos'  => ['required', 'string', 'max:100'],
            'cedula'     => ['required', 'string', 'max:20', 'unique:users,cedula'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'oficina_id' => ['required', 'exists:oficinas,id'],
            'role'       => ['required', 'in:admin,solicitante'],
            'password'   => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'cedula.unique'  => 'La cédula ya está registrada.',
            'email.unique'   => 'El correo ya está registrado.',
            'oficina_id.required' => 'Debe seleccionar una oficina.',
        ];
    }
}