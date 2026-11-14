<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'fecha_requerida' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin'       => ['nullable', 'date', 'after_or_equal:fecha_requerida'],
            'cantidad'        => ['required', 'integer', 'min:1'],
            'tipo'            => ['required', 'in:prestamo,asignacion'],
        ];
    }
}