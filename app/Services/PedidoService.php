<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PedidoService
{
    /**
     * Obtiene los pedidos del usuario autenticado con su relación de usuario.
     */
    public function obtenerPedidosDelUsuario(Request $request): Collection
    {
        return $request->user()->pedidos()->with(['user'])->get();
    }

    /**
     * Crea un nuevo pedido asociado al usuario autenticado.
     */
    public function crearPedido(Request $request, array $datosValidados): Pedido
    {
        $pedido = $request->user()->pedidos()->create($datosValidados);
        
        // Cargamos la relación del usuario para el Resource
        return $pedido->load('user');
    }
}