# FICHA TÉCNICA DE USO CRÍTICO DE IA · BOUCLEE API

| Campo | Detalle técnico |
| :--- | :--- |
| **Proyecto** | `lab3-integracionsistemas-bouclee` (API de Pedidos Bouclée) |
| **Asignatura** | Integración de Sistemas · CE-ISC019 · Laboratorio III · Ciclo 02-2026 |
| **Stack tecnológico** | Laravel, Laravel Sanctum, MySQL, Postman |
| **Fecha de emisión** | 2026-11-19 |
| **Control de seguridad** | Sin inclusión de archivos `.env`, tokens activos, credenciales ni datos sensibles de clientes |

---

## Caso 01 · Implementación de `show()` y control de acceso por propiedad (Mitigación IDOR)

### 1. Prompt usado
> "En `PedidoController` de Laravel (que ya cuenta con `index` y `store` apoyándose en `PedidoService` y `PedidoResource`), necesito implementar `show(Request $request, $id): JsonResponse` para consultar un pedido por su ID bajo `auth:sanctum`. Debe retornar `404` si el pedido no existe, `403` si pertenece a otro usuario autenticado y `200` con `PedidoResource` si pertenece al usuario autenticado."

### 2. Código generado
```php  
public function show(Request $request,$id): JsonResponse
{
    $pedido = Pedido::find($id);

    if (!$pedido) {
        return response()->json([
            'success' => false,
            'message' => 'Pedido no encontrado.'
        ], 404);
    }

    if ($pedido->user_id !==$request->user()->id) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes autorización para consultar este pedido.'
        ], 403);
    }
 
    return response()->json([
        'success' => true,
        'data' => new PedidoResource($pedido)
    ], 200);

```


### 3. Problemas detectados 

>- Al solicitar un pedido individual por ID sin el método ni la validación completa de autorización, la API devolvía códigos 405 o 500 en lugar de códigos semánticos de acceso" (403 / 404).
>
>- En `routes/api.php` debían coexistir el grupo protegido con `auth:sanctum`, `Route::apiResource('pedidos')`, `PedidoController::class)` y la ruta explícita `GET /pedidos/{id}` sin conflictos de parámetros ni importaciones faltantes.

### 3. Problemas detectados

>- Al solicitar un pedido individual por ID sin el método ni la validación completa de autorización, la API devolvía códigos `405` o `500` en lugar de códigos semánticos de acceso `(403 / 404)`.
>
>- En `routes/api.php` debían coexistir el grupo protegido con `auth:sanctum`, `Route::apiResource('pedidos', PedidoController::class)` y la ruta explícita `GET /pedidos/{id}` sin conflictos de parámetros ni importaciones faltantes.

### 4. Corrección aplicada

>- Se añadió el método `show(Request $request, $id): JsonResponse` en `PedidoController`, se verificó el import del modelo `Pedido` y se revisó el registro de rutas protegidas bajo `middleware('auth:sanctum')` en `routes/api.php`.
>
>- Se probó en Postman `(Bouclée API - Pruebas, v2.1)` el acceso a pedidos de otro usuario con tokens de distintos roles `(Asistente y Administración)`, verificando la respuesta `403 Forbidden`.

### 5. Justificación escrita

> La IA permitió estructurar rápidamente el flujo de validación por existencia y propiedad del recurso, pero la revisión técnica sobre el controlador, las rutas y las pruebas de seguridad en Postman fue indispensable para evitar una vulnerabilidad IDOR y asegurar respuestas HTTP coherentes entre usuarios autenticados.


## Caso 02 · Depuración de validación (StorePedidoApiRequest), transacciones (DB::transaction) y errores JSON (Throwable)

### 1. Prompt usado

>En una API REST con Laravel y Sanctum para gestión de pedidos, al listar o crear registros en Postman ocurrían errores 500, 422 o warnings en lugar de respuestas JSON consistentes. ¿Qué debo ajustar en StorePedidoApiRequest, PedidoService / PedidoResource y bootstrap/app.php para validar bien los campos activos, mantener la operación atómica con DB::transaction y devolver errores siempre en JSON?
>
### 2. Código generado

```php

// app/Http/Requests/StorePedidoApiRequest.php
public function rules(): array
{
    return [
        'cliente' => ['required', 'string', 'max:255'],
        'postre' => ['required', 'string', 'max:255'],
        'fecha_entrega' => ['required', 'date'],
        'estado' => ['required', 'in:Pendiente,En preparación,Listo,Entregado'],
        'notas' => ['nullable', 'string'],
    ];
}
```

```php

->withExceptions(function (Exceptions $exceptions): void {$exceptions->shouldRenderJsonWhen(function ($request, \Throwable$e) {
        return $request->is('api/*') \vert{}\vert{}$request->expectsJson();
    });
})

```
### 3. Problemas detectados

>- Al listar pedidos en GET /api/pedidos se producía un error 500 por intentar resolver una relación inexistente en el modelo Pedido
>
>- Al crear pedidos en POST /api/pedidos se presentaban rechazos 422/warnings por exigir campos obsoletos (total / user_id) y por excepciones sin forzar salida en JSON para rutas de API.

### 4. Corrección Aplicada

>- Se depuraron las relaciones y serialización en `PedidoService` y `PedidoResource`, manteniendo la creación atómica protegida con `DB::transaction`.
>
>- Se actualizaron las reglas de `StorePedidoApiRequest` para los campos activos del pedido y se configuró `bootstrap/app.php` con `\Throwable` para garantizar respuestas JSON en rutas API.
>
>- Se reejecutaron las pruebas de login, listado `(200 OK)`, creación válida `(201 Created)` y datos inválidos/incompletos `(422 Unprocessable Entity)` en Postman.

## 5. Justificación

> Utilizar la IA como apoyo diagnóstico permitió ubicar con rapidez la interacción entre reglas de validación, recursos de respuesta, transacciones en `PedidoService` y el manejador global de excepciones, mientras que las pruebas empíricas confirmaron la estabilidad del contrato JSON y la integridad de los datos en base de datos.


}
