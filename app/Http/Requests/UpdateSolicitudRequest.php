<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSolicitudRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'fecha_requerida' => ['required', 'date'],
            'fecha_fin'       => ['required_if:tipo,prestamo', 'nullable', 'date', 'after_or_equal:fecha_requerida'],
            'cantidad'        => ['required', 'integer', 'min:1'],
            'tipo'            => ['required', 'in:prestamo,asignacion'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_fin.required_if' => 'La fecha de devolución es obligatoria cuando el tipo es préstamo.',
            'fecha_fin.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha requerida.',
        ];
    }
}