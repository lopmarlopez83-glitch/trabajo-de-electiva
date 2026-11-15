<?php

namespace App\Http\Requests;

use App\Models\Modelo;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation()
    {
        if ($this->filled('modelo_id')) {
            $modelo = Modelo::with('categoria')->find($this->modelo_id);
            $cat = strtolower($modelo->categoria->nombre ?? '');
            
            $esPc = str_contains($cat, 'laptop') || str_contains($cat, 'portátil') || str_contains($cat, 'computadora');
            
            if (!$esPc) {
                $this->merge([
                    'cpu' => null,
                    'ram' => null,
                    'almacenamiento' => null,
                ]);
            }
        }
    }

    public function rules(): array
    {
        $reglas = [
            'serial'        => ['required', 'string', 'max:100', 'unique:equipos,serial'],
            'modelo_id'     => ['required', 'exists:modelos,id'],
            'status'        => ['required', 'in:disponible,en_mantenimiento,prestado,asignado'],
            'observaciones' => ['nullable', 'string'],
        ];

        if ($this->filled('modelo_id')) {
            $modelo = Modelo::with('categoria')->find($this->modelo_id);
            $cat = strtolower($modelo->categoria->nombre ?? '');
            
            $esPc = str_contains($cat, 'laptop') || str_contains($cat, 'portátil') || str_contains($cat, 'computadora');
            
            if ($esPc) {
                $reglas['cpu'] = ['required', 'string', 'max:100'];
                $reglas['ram'] = ['required', 'string', 'max:100'];
                $reglas['almacenamiento'] = ['required', 'string', 'max:100'];
            } else {
                $reglas['cpu'] = ['nullable', 'string', 'max:100'];
                $reglas['ram'] = ['nullable', 'string', 'max:100'];
                $reglas['almacenamiento'] = ['nullable', 'string', 'max:100'];
            }
        } else {
            $reglas['cpu'] = ['nullable', 'string', 'max:100'];
            $reglas['ram'] = ['nullable', 'string', 'max:100'];
            $reglas['almacenamiento'] = ['nullable', 'string', 'max:100'];
        }

        return $reglas;
    }
}