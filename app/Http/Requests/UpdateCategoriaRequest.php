<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('categoria')->id ?? $this->route('categoria');

        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('categorias', 'nombre')->ignore($id)],
            'descripcion' => ['nullable', 'string'],
        ];
    }
}