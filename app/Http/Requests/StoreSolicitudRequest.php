<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $rules = [
            'fecha_requerida' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin'       => ['required_if:tipo,prestamo', 'nullable', 'date', 'after_or_equal:fecha_requerida'],
            'cantidad'        => ['required', 'integer', 'min:1'],
            'tipo'            => ['required', 'in:prestamo,asignacion'],
        ];

        // ✅ Solo el admin debe elegir oficina
        if ($this->user() && $this->user()->esAdmin()) {
            $rules['oficina_id'] = ['required', 'exists:oficinas,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'fecha_fin.required_if'          => 'La fecha de devolución es obligatoria cuando el tipo es préstamo.',
            'fecha_fin.after_or_equal'       => 'La fecha de devolución debe ser igual o posterior a la fecha requerida.',
            'fecha_requerida.after_or_equal' => 'La fecha requerida no puede ser anterior a hoy.',
            'oficina_id.required'            => 'Debe seleccionar una oficina.',
        ];
    }
}