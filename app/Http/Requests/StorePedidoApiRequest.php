<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePedidoApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir la petición (Sanctum se encarga de la auth)
    }

    public function rules(): array
    {
        return [
            'cliente' => ['required', 'string', 'max:255'],
            'postre' => ['required', 'string', 'max:255'],
            'fecha_entrega' => ['required', 'date'],
            // Usamos Rule::in para validar los estados permitidos en tu negocio de repostería
            'estado' => ['required', Rule::in(['Pendiente', 'En proceso', 'Entregado', 'pendiente', 'entregado'])],
            'notas' => ['nullable', 'string'],
        ];
    }
}