<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModeloRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:150'],
            'marca_id'     => ['required', 'exists:marcas,id'],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ];
    }
}