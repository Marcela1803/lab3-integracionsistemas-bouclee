# Anexo A · Cuadro comparativo de frameworks

* **Proyecto:** Bouclée API (`lab3-integracionsistemas-bouclee`)
* **Asignatura:** Integración de Sistemas · CE-ISC019 · Laboratorio III · Ciclo 02-2026

| Criterio | Laravel | Otro framework: Express.js (Node.js) |
| :--- | :--- | :--- |
| **Lenguaje y ecosistema** | PHP 8+, con ecosistema integrado mediante Composer, Artisan CLI, Form Requests, API Resources y soporte nativo para arquitectura MVC/API. | JavaScript / TypeScript sobre Node.js, con ecosistema modular mediante npm basado en librerías externas desacopladas. |
| **Curva de aprendizaje** | Moderada; ofrece convenciones claras y estructura definida desde el inicio (`routes/api.php`, controladores, servicios y middleware), agilizando el desarrollo ordenado. | Baja al iniciar una ruta básica, pero aumenta al integrar manualmente arquitectura, validaciones, autenticación y manejo de errores. |
| **ORM y acceso a datos** | Eloquent ORM integrado con migraciones, relaciones, modelos y transacciones atómicas nativas (`DB::transaction`) sobre MySQL. | No incluye ORM nativo; requiere configurar herramientas externas como Prisma, Sequelize o TypeORM para modelar y transaccionar datos. |
| **Autenticación de API** | Laravel Sanctum integrado de forma oficial para emitir y revocar tokens Bearer (`auth:sanctum`) y proteger rutas por usuario. | Requiere implementar manualmente middlewares con librerías como `jsonwebtoken` (JWT) o `Passport.js` y gestionar la revocación de tokens. |
| **Herramientas de pruebas** | Incluye PHPUnit y Pest de fábrica para pruebas unitarias y de integración HTTP, además de integración directa con colecciones de Postman. | No trae suite de pruebas integrada; requiere instalar y configurar Jest, Mocha o Supertest adicionalmente a Postman. |
| **Despliegue y hosting** | Compatible con servidores tradicionales PHP/Apache/Nginx (como WAMP en entorno local), hosting compartido, VPS y plataformas PaaS (Laravel Forge, Render, Railway). | Requiere entornos con runtime Node.js activo (VPS, contenedores Docker o servicios PaaS como Render, Railway o Vercel). |
| **Escenario donde conviene más** | Aplicaciones empresariales y APIs REST transaccionales que requieren seguridad, validación estricta, ORM robusto y rapidez de entrega con estructura estandarizada. | Microservicios ligeros en tiempo real, proxies de I/O intensivo o equipos cuyo stack completo (frontend y backend) trabaja exclusivamente en JavaScript. |

---

### Conclusión aplicada al proyecto Bouclée API

Para el desarrollo de **Bouclée API**, **Laravel** resultó la opcion más conveniente frente a un frameworkk Express.js, esto porque el sistema de gestión de pedidos de la pastelería exige consistencia transaccional, validción estricta de entradas y aislamiento de datos entre roles operativos (`Asistente` y `Administración`). Gracias a herramientas nativas como Laravel Sanctum para la emisión y revocación de tokens (`POST /api/login` y `POST /api/logout`), Eloquent con `DB::transaction` en `PedidoService` para asegurar el registro atómico de pedidos en MySQL, `StorePedidoApiRequest` para validar los campos del pedido y el manejo centralizado de excepciones JSON en `bootstrap/app.php`, se logró construir una API segura y mantenible.
