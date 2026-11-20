<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePedidoApiRequest;
use App\Http\Resources\PedidoResource;
use App\Services\PedidoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Pedido;

class PedidoController extends Controller
{
    protected PedidoService $pedidoService;

    // Inyectamos el servicio a través del constructor
    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    // Listar los pedidos del usuario autenticado (Código 200)
    public function index(Request $request): JsonResponse
    {
        $pedidos = $this->pedidoService->obtenerPedidosDelUsuario($request);

        return response()->json([
            'success' => true,
            'data' => PedidoResource::collection($pedidos)
        ], 200);
    }

    // Crear un nuevo pedido (Código 201 Created)
    public function store(StorePedidoApiRequest $request): JsonResponse
    {
        $pedido = $this->pedidoService->crearPedido($request, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Encargo registrado con éxito en Bouclée.',
            'data' => new PedidoResource($pedido)
        ], 201);
    }
    // Ver un pedido específico y validar propiedad / IDOR (Códigos 200, 403, 404)
    public function show(Request $request, $id): JsonResponse
    {
        $pedido = \App\Models\Pedido::find($id);

        // Si el pedido no existe -> 404 Not Found
        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado.'
            ], 404);
        }

        // Si el pedido pertenece a otro usuario -> 403 Forbidden (Protección IDOR)
        if ($pedido->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para acceder a este pedido.'
            ], 403);
        }

        // Si es del usuario autenticado -> 200 OK
        return response()->json([
            'success' => true,
            'data' => new PedidoResource($pedido)
        ], 200);
    }
}