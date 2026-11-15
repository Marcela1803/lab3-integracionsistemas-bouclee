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
            // Regla avanzada Rule::in para validar los estados fijos de los pasteles/pedidos
            'estado' => ['required', Rule::in(['pendiente', 'en_preparacion', 'completado', 'cancelado'])],
            // Regla avanzada Rule::exists para asegurar que el usuario asignado existe en la BD
            'user_id' => ['required', Rule::exists('users', 'id')],
            'total' => ['required', 'numeric', 'min:0'],
        ];
    }
}