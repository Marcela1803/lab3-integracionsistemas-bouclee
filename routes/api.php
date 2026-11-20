<?php
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


// Rutas Públicas de Autenticación

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas Protegidas (Requieren Token Sanctum)

Route::middleware('auth:sanctum')->group(function () {
    // Revocar el token actual
    Route::post('/logout', [AuthController::class, 'logout']);

    // Recurso principal: pedidos
    Route::apiResource('pedidos', PedidoController::class)->names('api.pedidos');
    
    // Recurso relacionado
    Route::apiResource('pedidos.detalles', PedidoDetalleApiController::class)->scoped();

    Route::get('/pedidos/{id}', [PedidoController::class, 'show']);
    });