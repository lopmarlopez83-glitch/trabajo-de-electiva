<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOficinaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('oficina')->id ?? $this->route('oficina');

        return [
            'nombre'    => ['required', 'string', 'max:150', Rule::unique('oficinas', 'nombre')->ignore($id)],
            'ubicacion' => ['nullable', 'string', 'max:200'],
        ];
    }
}