<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Envolvemos todo en una transacción de base de datos
        return DB::transaction(function () use ($request, $datosValidados) {
    
        $pedido = $request->user()->pedidos()->create($datosValidados);
        
        // Operación en la tabla 'users': Actualizamos el timestamp del usuario 
            // demostrando que se modifican múltiples tablas de forma atómica.

        $user = $request->user();
        $user->touch();
        // Cargamos la relación del usuario para el Resource
        return $pedido->load('user');
    });
}

}

