<?php

use App\Http\Controllers\Api\PedidoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Rutas protegidas para la API de Bouclée
Route::middleware('auth:sanctum')->group(function () {
    // Recurso principal: pedidos
    Route::apiResource('pedidos', PedidoController::class);
    
    // Recurso relacionado (ej. ítems o detalles del pedido vinculados al pedido)
    Route::apiResource('pedidos.detalles', PedidoDetalleApiController::class)->scoped();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::apiResource('pedidos', PedidoController::class);
});