                                        LABORATORIO III - INTEGRACIÓN DE SISTEMAS

Información del Equipo
Nombre del Equipo: Bouclée
Integrantes y Coevaluación de Participación:

Adriana Marcela Hernández Recinos — carnet HR-64876-23 — 100%


---

## Descripción del Proyecto
Este proyecto es la evolución del sistema de gestión administrativa para la pastelería **Bouclée**. En este **Laboratorio III**, la aplicación desarrollada previamente en Laravel 11 incorpora una **API REST stateless** asegurada con **Laravel Sanctum** para la integración externa y gestión de encargos de postres.

El sistema conserva la administración de pedidos a través de los estados operativos (`Pendiente`, `En Preparación`, `Listo`, `Entregado`) e incorpora autenticación mediante tokens Bearer, consulta individual protegida contra acceso indebido entre usuarios (prevención IDOR), creación atómica de registros mediante `DB::transaction` y manejo estandarizado de errores en formato JSON.

---

## Instrucciones de Instalación y Ejecución

Para clonar y desplegar este proyecto en un entorno local, sigue los siguientes pasos en tu terminal:

1. **Clonar el repositorio e ingresar a la carpeta del proyecto:**
   `git clone [https://github.com/Marcela1803/lab3-integracionsistemas-bouclee.git](https://github.com/Marcela1803/lab3-integracionsistemas-bouclee.git)`
   `cd lab3-integracionsistemas-bouclee`

2. **Instalar las dependencias de PHP (Composer):**
   `composer install`

3. **Configurar las variables de entorno:**
   Copia el archivo de ejemplo para crear tu propio archivo `.env`:
   `cp .env.example .env`
   *(Nota: Abre el archivo `.env` recién creado y configura las credenciales de tu base de datos local: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).*

4. **Generar la clave de la aplicación:**
   `php artisan key:generate`

5. **Ejecutar las migraciones de la base de datos (incluyendo tablas de Sanctum):**
   `php artisan migrate`

6. **Instalar y compilar los recursos de Frontend (Tailwind/Breeze):**
   `npm install`
   `npm run dev`

7. **Iniciar el servidor local:**
   Abre una nueva terminal y ejecuta:
   `php artisan serve`
   La aplicación y la API estarán disponibles en `http://localhost:8000` (base URL de la API: `http://localhost:8000/api`).

---

## Tabla de Endpoints de la API

Todos los endpoints protegidos requieren los encabezados `Authorization: Bearer <token>` y `Accept: application/json`.

| Método | Ruta | Protegido | Descripción |
| :--- | :--- | :---: | :--- |
| `POST` | `/api/register` | No | Registra un nuevo usuario en el sistema y emite su token Bearer inicial de Sanctum (`201 Created`). |
| `POST` | `/api/login` | No | Autentica las credenciales del usuario (`email` y `password`) y devuelve un token Bearer activo (`200 OK`). |
| `POST` | `/api/logout` | Sí (`auth:sanctum`) | Revoca el token Bearer activo del usuario autenticado cerrando su sesión en la API (`200 OK`). |
| `GET` | `/api/pedidos` | Sí (`auth:sanctum`) | Lista los pedidos registrados en formato JSON estandarizado mediante `PedidoResource` (`200 OK`). |
| `POST` | `/api/pedidos` | Sí (`auth:sanctum`) | Valida los datos con `StorePedidoApiRequest` (`cliente`, `postre`, `fecha_entrega`, `estado`, `notas`) y registra un pedido de forma atómica con `DB::transaction` (`201 Created` / `422`). |
| `GET` | `/api/pedidos/{id}` | Sí (`auth:sanctum`) | Consulta el detalle de un pedido específico por ID; valida existencia (`404 Not Found`) y propiedad del usuario autenticado para prevenir vulnerabilidades IDOR (`403 Forbidden`). |

---

## Estructura de Evidencias y Documentación
* `/docs`: Contiene la bitácora de pruebas (`bitacora_pruebas_bouclee.xlsx`), la ficha técnica de uso crítico de IA (`ficha_uso_critico_ia.md`), el registro de versiones (`CAMBIOS.md`) y el cuadro comparativo de frameworks (`anexo_a_cuadro_comparativo.md`).
* `/pruebas`: Contiene la colección exportada de Postman (`Bouclée API - Pruebas`, v2.1) con los casos de prueba ejecutados por rol sin credenciales ni tokens activos expuestos.

---

## Nota: Uso de Herramientas de IA
La herramienta de IA funcionó como soporte técnico para la depuración de reglas de validación (`StorePedidoApiRequest`), estandarización de excepciones JSON en `bootstrap/app.php` y estructuración de controles de propiedad en `PedidoController@show`.

**Validación de resultados:** Ningún código generado fue implementado directamente sin revisión. Todos los fragmentos y sugerencias estructurales fueron analizados, adaptados a la lógica de negocio de Bouclée y validados mediante pruebas de ejecución local en el servidor y en Postman para garantizar el cumplimiento estricto de los requerimientos de la rúbrica.
