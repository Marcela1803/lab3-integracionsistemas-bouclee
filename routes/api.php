<?php

use App\Http\Controllers\Api\PedidoController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas para la API de Bouclée
Route::middleware('auth:sanctum')->group(function () {
    // Recurso principal: pedidos
    Route::apiResource('pedidos', PedidoController::class);
    
    // Recurso relacionado (ej. ítems o detalles del pedido vinculados al pedido)
    Route::apiResource('pedidos.detalles', PedidoDetalleApiController::class)->scoped();
});