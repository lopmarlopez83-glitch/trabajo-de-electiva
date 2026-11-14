<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMarcaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('marca')->id ?? $this->route('marca');

        return [
            'nombre' => ['required', 'string', 'max:100', Rule::unique('marcas', 'nombre')->ignore($id)],
        ];
    }
}