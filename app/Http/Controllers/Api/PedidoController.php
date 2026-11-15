<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePedidoApiRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PedidoController extends Controller
{
    // Listar los pedidos del usuario autenticado (Código 200)
    public function index(Request $request): JsonResponse
    {
        // Aislamos los datos por usuario para evitar IDOR
        $pedidos = $request->user()->pedidos()->with(['user', 'detalles'])->get();
        
        return response()->json([
            'success' => true,
            'data' => PedidoResource::collection($pedidos)
        ], 200);
    }

    // Crear un nuevo pedido (Código 201 Created)
    public function store(StorePedidoApiRequest $request): JsonResponse
    {
        $pedido = $request->user()->pedidos()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Encargo registrado con éxito en Bouclée.',
            'data' => new PedidoResource($pedido->load('user'))
        ], 201);
    }

    // Mostrar un pedido específico (Código 200 o 404 automático si no existe)
    public function show(Request $request, string $id): JsonResponse
    {
        $pedido = $request->user()->pedidos()->with(['user', 'detalles'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new PedidoResource($pedido)
        ], 200);
    }

    // Eliminar un pedido (Código 204 No Content)
    public function destroy(Request $request, string $id): JsonResponse
    {
        $pedido = $request->user()->pedidos()->findOrFail($id);
        $pedido->delete();

        return response()->json(null, 204);
    }
}