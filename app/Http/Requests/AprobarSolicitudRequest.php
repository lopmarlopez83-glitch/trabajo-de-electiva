<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AprobarSolicitudRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'equipo_ids'   => ['required', 'array', 'min:1'],
            'equipo_ids.*' => ['exists:equipos,id'],
        ];
    }
}