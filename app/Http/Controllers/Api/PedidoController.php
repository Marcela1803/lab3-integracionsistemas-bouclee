<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePedidoApiRequest;
use App\Http\Resources\PedidoResource;
use App\Services\PedidoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
}