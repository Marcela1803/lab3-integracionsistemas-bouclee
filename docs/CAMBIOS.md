# REGISTRO DE CAMBIOS (`CAMBIOS.md`) · BOUCLEE API (`lab3-integracionsistemas-bouclee`)

Todos los cambios relevantes del proyecto de integración de sistemas para la API REST de Bouclée se documentan en este archivo siguiendo el estándar de Versionado Semántico (`MAJOR.MINOR.PATCH`).

---

## · Cierre de QA, Seguridad IDOR y Auditoría
### Agregado
- **Control de acceso individual (`GET /api/pedidos/{id}`):** Implementación de `PedidoController@show` protegido con `auth:sanctum`, retornando `404 Not Found` si el pedido no existe y `403 Forbidden` si pertenece a otro usuario autenticado (prevención IDOR).
- **Suite de pruebas automatizadas:** Exportación de la colección `Bouclée API - Pruebas` (Postman v2.1) estructurada por roles (`Usuario - Asistente Bouclee` y `Usuario - Administración Bouclee`).
- **Documentación de calidad (`/docs`):** Bitácora de 15 casos ejecutados (`bitacora_pruebas_bouclee.xlsx`), ficha técnica de uso crítico de IA (`ficha_uso_critico_ia.md`), registro de mantenimientos y Anexo A comparativo.

### Cambiado
- **ME-01 (Mantenimiento Evolutivo):** Incorporación de lectura individual de pedidos con validación estricta de propiedad por `user_id` (`solicitud → impacto → cambio → pruebas → registro`).

---

## · Depuración de Validaciones y Manejo Global de Excepciones
### Corregido
- **MC-01 (Mantenimiento Correctivo):** Resolución de error `500 Internal Server Error` al listar pedidos en `GET /api/pedidos` eliminando una relación inexistente en el flujo de `PedidoService` y `PedidoResource`.
- **Validación de creación (`POST /api/pedidos`):** Limpieza de reglas en `StorePedidoApiRequest` para exigir únicamente los campos activos (`cliente`, `postre`, `fecha_entrega`, `estado`, `notas`), resolviendo rechazos `422` y warnings por campos obsoletos.
- **Respuestas JSON consistentes:** Ajuste en `bootstrap/app.php` capturando `\Throwable` para garantizar que todas las excepciones en rutas `api/*` devuelvan JSON estructurado en lugar de vistas HTML.

---

## · Versión Base de API REST con Laravel Sanctum
### Agregado
- **Autenticación stateless:** Endpoints públicos `POST /api/register` y `POST /api/login` con emisión de tokens Bearer mediante Laravel Sanctum, más `POST /api/logout` protegido para revocación de token activo.
- **Gestión base de pedidos:** Endpoints `GET /api/pedidos` y `POST /api/pedidos` protegidos bajo `middleware('auth:sanctum')`.
- **Integridad transaccional:** Protección atómica con `DB::transaction` en `PedidoService` al registrar nuevos pedidos y actualizar la actividad del usuario (`$user->touch()`).
