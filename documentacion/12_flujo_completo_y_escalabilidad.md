# TRIGESTION — Flujo Completo del Sistema y Escalabilidad

> Documento técnico-operativo. Describe el ciclo de vida completo del negocio dentro del sistema, cómo se conectan todos los módulos, y una hoja de ruta de mejoras para escalar la plataforma.

---

## 1. Visión General del Sistema

TRIGESTION no es un simple CRUD. Es un sistema de **trazabilidad circular**: un envase entra como materia prima, se transforma en producto terminado, se vende al cliente, y puede regresar al sistema como envase reutilizable. Cada paso queda registrado y afecta al siguiente.

```
┌─────────────────────────────────────────────────────────────────────┐
│                    CICLO OPERATIVO TRIGESTION                       │
│                                                                     │
│  BODEGA ──► PRODUCCIÓN ──► INVENTARIO ──► VENTA ──► DEVOLUCIÓN     │
│    │                           │              │           │         │
│    └───────────────────────────┴──────────────┴───────────┘         │
│                        (ciclo retornable)                           │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. Flujo Completo Paso a Paso

### FASE 1 — Ingreso de Materia Prima

**Actor:** Trabajador o Administrador
**Módulos:** Lotes → Inventario de Materia Prima

```
Llegan envases (garrafones) del proveedor
        │
        ▼
Trabajador crea un LOTE
  └─ Código del lote (ej: LOTE-2026-08)
  └─ Queda vinculado al usuario que lo registró
        │
        ▼
Agrega DETALLES al lote (puede ser por tipo de envase):
  └─ Cantidad de unidades
  └─ Tipo de envase (Garrafón 20L, Botella 500ml...)
  └─ Capacidad, proveedor
        │
        ▼ (automático al guardar el detalle)
Se crea entrada en INVENTARIO DE MATERIA PRIMA:
  └─ ingreso = unidades del detalle
  └─ bodega = "Bodega Principal"
  └─ id_detalles = ID del detalle de lote
```

**Tablas afectadas:** `lote` → `detalles` → `inventario_materia_prima`

---

### FASE 2 — Producción

**Actor:** Trabajador
**Módulos:** Producción → Inventario Materia Prima → Inventario Productos

```
Trabajador inicia una PRODUCCIÓN:
  └─ Código de lote de producción
  └─ Cantidad a producir
  └─ Producto terminado que se va a fabricar
  └─ (Opcional) Entrada de MP vinculada
  └─ Estado inicial: "En Producción"
        │
        ▼
[Proceso físico de llenado/purificación]
        │
        ▼
Trabajador finaliza la producción (DB::transaction):
  │
  ├─► PASO 1: Estado → "Finalizada"
  │
  ├─► PASO 2: Registro en INVENTARIO DE PRODUCTOS
  │     └─ cantidad = unidades producidas
  │     └─ id_produccion = esta producción (evita duplicados)
  │     └─ bodega = "Principal"
  │
  └─► PASO 3: Descuento de MATERIA PRIMA (si vinculada)
        └─ ingreso = max(0, ingreso_actual - cantidad_producida)
```

**Tablas afectadas:** `produccion` → `inventario_productos` (+) → `inventario_materia_prima` (-)

**Regla crítica:** Si la producción ya fue finalizada, el sistema la rechaza. El check por `id_produccion` en `inventario_productos` evita doble registro.

---

### FASE 3 — Catálogo y Carrito

**Actor:** Cliente
**Módulos:** Productos → Carrito (sesión)

```
Cliente accede a /catalogo
        │
        ▼
Sistema carga productos activos (estado = 1)
Para cada producto consulta StockService::disponible():
  └─ stock = SUM(inventario_productos) - SUM(detalle_venta WHERE estado IN ('En Proceso','Entregado'))
        │
        ├─ stock > 0 → muestra "X en stock" (verde), botón activo
        └─ stock = 0 → muestra "Agotado" (rojo), tarjeta bloqueada, botón deshabilitado
        │
        ▼
Cliente hace clic en "Agregar al Carrito" (AJAX):
  1. Verifica producto activo
  2. Verifica stock disponible
  3. Si ya está en carrito: suma cantidad (respeta límite de stock)
  4. Guarda en session('carrito')
  5. Retorna JSON: {ok, total_items, msg}
```

**Tablas consultadas:** `producto`, `inventario_productos`, `detalle_venta`, `venta`
**Sin escritura en BD** en este punto. El carrito vive en sesión.

---

### FASE 4 — Checkout (Confirmar Pedido)

**Actor:** Cliente
**Módulos:** Carrito → Venta → Detalle Venta

```
Cliente confirma el pedido desde el carrito (AJAX POST /carrito/finalizar)
        │
        ▼
DB::transaction() {
  │
  ├─► Re-verifica stock de CADA producto
  │     └─ Si alguno falla → RuntimeException → rollback completo
  │         └─ Mensaje con nombre del producto afectado
  │
  ├─► Crea registro en VENTA:
  │     └─ estado = "Pendiente"
  │     └─ total = Σ(precio_unitario × cantidad) — sin IVA
  │     └─ fecha = hoy
  │     └─ método de pago, notas
  │
  └─► Por cada ítem crea registro en DETALLE_VENTA:
        └─ id_venta, id_producto, cantidad, precio_unitario, descuento
}
        │
        ▼
Limpia session('carrito')
        │
        ▼
Retorna JSON con datos de factura → SweetAlert de éxito
        │
        ▼
Redirige a /mis-compras
```

> ⚠ **El stock NO se descuenta aquí.** La venta queda en `Pendiente`. El inventario solo se afecta cuando el admin confirma el pedido.

**Tablas escritas:** `venta`, `detalle_venta`

---

### FASE 5 — Gestión del Pedido (Admin)

**Actor:** Administrador
**Módulos:** Ventas

```
Admin accede a /ventas
        │
        ▼
Ve todos los pedidos ordenados (paginados)
        │
        ▼
Cambia el estado del pedido:

  Pendiente ──────────────────► En Proceso
      │           (stock se descuenta aquí — StockService lo refleja)
      │
      └──────────────────────► Cancelado
                                (stock libre, venta ignorada por StockService)

  En Proceso ─────────────────► Entregado
      │                          (stock ya descontado, venta concluida)
      │
      └──────────────────────► Cancelado
                                (stock se libera automáticamente)

  Entregado ──────────────────► (estado final, sin cambios posibles)
```

**Tablas escritas:** `venta` (campo estado)

> El descuento de stock es implícito: `StockService` suma/resta según los estados en cada consulta. No hay movimiento físico en `inventario_productos` al cambiar el estado.

---

### FASE 6 — Devolución de Garrafones

**Actor:** Administrador o Trabajador
**Módulos:** Devoluciones → Inventario Materia Prima

```
Cliente regresa el garrafón vacío
        │
        ▼
Operario accede a /devoluciones
Ve balances: cuántos garrafones tiene cada cliente pendientes
        │
        ▼
Registra la devolución:
  └─ Cliente, producto retornable, cantidad, estado del envase
        │
  ┌─────▼─────────────────────────────┐
  │ Validación de balance:            │
  │ cantidad ≤ (entregado - devuelto) │
  └─────────────────────────────────┬─┘
                                    │
              ┌─────────────────────┼──────────────────────┐
              │ APTO (buen estado)  │   DAÑADO             │
              ▼                     │                       ▼
  DB::transaction() {               │       Solo INSERT en
    INSERT devolucion_retornables   │       devolucion_retornables
    INSERT inventario_materia_prima │       (registro histórico)
      └─ ingreso = cantidad         │       Sin entrada al inventario
      └─ id_retornables = ID        │       Garrafón fuera del ciclo
  }                                 │
              │                     │
              ▼                     ▼
  Garrafón disponible         Garrafón descartado
  para nueva producción       permanentemente
```

**Tablas escritas:** `devolucion_retornables`, `inventario_materia_prima` (solo si apto)

El garrafón que vuelve al inventario de materia prima puede usarse en la **Fase 2** nuevamente, cerrando el ciclo.

---

## 3. Mapa de Dependencias entre Módulos

```
                        ┌──────────┐
                        │  Roles   │
                        └────┬─────┘
                             │ tiene
                        ┌────▼─────┐
                        │ Usuarios │
                        └────┬─────┘
               ┌─────────────┼──────────────┐
               │             │              │
          ┌────▼────┐   ┌────▼────┐   ┌────▼────┐
          │  Admin  │   │Trabajad.│   │ Cliente │
          └────┬────┘   └────┬────┘   └────┬────┘
               │             │              │
         ┌─────▼──┐    ┌─────▼──┐    ┌─────▼──────┐
         │Categorí│    │  Lotes  │    │  Catálogo  │
         │Producto│    └────┬────┘    │  + Carrito │
         │Ventas  │         │         └─────┬──────┘
         │Reportes│    ┌────▼──────┐        │
         └────────┘    │Inv.Mat.   │   ┌────▼──────┐
                       │Prima      │   │   Venta   │
                       └────┬──────┘   │(Pendiente)│
                            │          └─────┬─────┘
                       ┌────▼──────┐         │
                       │Producción │    ┌─────▼──────┐
                       └────┬──────┘    │  Admin     │
                            │           │  cambia    │
                       ┌────▼──────┐    │  estado    │
                       │Inv.Prod.  │◄───┘            │
                       └────┬──────┘                 │
                            │                        │
                       ┌────▼──────┐                 │
                       │StockSvc   │◄────────────────┘
                       │(consulta) │
                       └───────────┘
```

---

## 4. Reglas de Negocio Centrales

### Stock
| Regla | Descripción |
|---|---|
| Stock dinámico | No hay campo `stock` en ninguna tabla. Se calcula en cada petición. |
| Solo estados confirmados | `En Proceso` y `Entregado` descuentan stock. `Pendiente` y `Cancelado` no. |
| Validación doble | Se valida al agregar al carrito Y al momento de finalizar la compra. |
| Sin negativos | `StockService` retorna `max(0, ingresado - vendido)`. |

### Precios
| Regla | Descripción |
|---|---|
| Sin IVA | Los precios en `producto.precio` ya incluyen todos los impuestos. |
| Precio congelado | El precio se copia a `detalle_venta.precio_unitario` al momento de la venta. Un cambio posterior en el producto no afecta ventas pasadas. |
| Sin redondeo automático | El total se suma aritméticamente. |

### Retornables
| Regla | Descripción |
|---|---|
| Solo productos marcados | Solo los productos con `retornable = 1` participan del ciclo de devolución. |
| Balance por cliente | No se puede devolver más garrafones de los que se entregaron. |
| Daño permanente | Un garrafón dañado nunca vuelve al inventario. |

---

## 5. Estado Actual del Sistema — Inventario de Decisiones Técnicas

| Decisión | Motivo | Impacto |
|---|---|---|
| Stock calculado dinámicamente | Simplicidad, sin riesgo de desincronización | Consultas más pesadas con gran volumen de datos |
| Carrito en sesión | Sin persistencia de BD, sin sesiones huérfanas | Se pierde al cerrar sesión o expirar |
| Sin IVA en precio | Los precios del negocio ya lo incluyen | Simplifica el modelo de datos |
| Timestamps manuales | El negocio maneja fechas de negocio, no de sistema | Sin `created_at` automático |
| Único modelo para devoluciones | `DevolucionRetornables` tiene toda la lógica | Fácil de mantener |
| `DB::transaction()` en operaciones críticas | Consistencia ante fallos parciales | Estándar recomendado |

---

## 6. Escalabilidad — Hoja de Ruta

### 6.1 Corto Plazo (sin cambios de arquitectura)

Estas mejoras se pueden implementar directamente sobre el código actual:

#### Notificaciones al cliente
- Enviar email/SMS cuando el admin cambia el estado del pedido a `En Proceso` o `Entregado`.
- Usar `Mail` de Laravel con una clase `PedidoActualizadoMail`.
- Sin cambios en la base de datos.

#### Recuperación de contraseña
- La infraestructura de emails de Laravel ya está disponible.
- Activar `password_reset_tokens` en `routes/auth.php`.

#### Historial de cambios de estado
- Crear tabla `venta_historial (id, id_venta, estado_anterior, estado_nuevo, id_usuario, created_at)`.
- Registrar automáticamente en `VentaController::cambiarEstado()`.
- Permite auditoría completa de cada pedido.

#### Descuento por volumen
- Agregar campo `descuento` a nivel de venta (ya existe a nivel de `detalle_venta`).
- Crear tabla `reglas_descuento (min_cantidad, porcentaje, id_producto)`.
- Aplicar en `VentaController::finalizarCompra()`.

#### Paginación en catálogo
- El catálogo actual carga todos los productos activos con `->get()`.
- Cambiar a `->paginate(12)` para mejorar rendimiento con muchos productos.

---

### 6.2 Mediano Plazo (cambios de modelo de datos)

#### Múltiples bodegas con trazabilidad
**Situación actual:** `bodega` es un campo de texto libre.
**Mejora:**
```sql
CREATE TABLE bodega (
    id_bodega     INT PRIMARY KEY AUTO_INCREMENT,
    nombre        VARCHAR(100),
    direccion     VARCHAR(200),
    activo        TINYINT DEFAULT 1
);
```
- Reemplazar el campo `bodega` en `inventario_materia_prima` e `inventario_productos` por `id_bodega`.
- Permite reportes de stock por bodega y transferencias entre bodegas.

#### Carrito persistente en base de datos
**Situación actual:** El carrito vive en la sesión del servidor.
**Problema:** Se pierde si el usuario cierra sesión o cambia de dispositivo.
**Solución:**
```sql
CREATE TABLE carrito (
    id_carrito    INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario    INT,
    id_producto   INT,
    cantidad      INT,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```
- Sincronizar el carrito de sesión con la BD al agregar ítems.
- Restaurar el carrito desde BD al iniciar sesión.

#### Rutas de reparto
```sql
CREATE TABLE ruta (
    id_ruta       INT PRIMARY KEY AUTO_INCREMENT,
    nombre        VARCHAR(100),
    descripcion   TEXT,
    id_usuario    INT  -- repartidor asignado
);

CREATE TABLE ruta_venta (
    id_ruta       INT,
    id_venta      INT,
    orden         INT,
    entregado_at  TIMESTAMP NULL
);
```
- Agrupar pedidos por zona y asignarlos a un repartidor.
- El repartidor marca la entrega desde su panel.
- Actualización automática del estado de la venta a `Entregado`.

#### Gestión de proveedores
```sql
CREATE TABLE proveedor (
    id_proveedor  INT PRIMARY KEY AUTO_INCREMENT,
    nombre        VARCHAR(150),
    telefono      VARCHAR(50),
    email         VARCHAR(100),
    nit           VARCHAR(50)
);
```
- Vincular `lote.id_proveedor` para trazabilidad de dónde vienen los envases.
- Permite análisis de calidad por proveedor.

---

### 6.3 Largo Plazo (nueva arquitectura)

#### API REST para app móvil
**Justificación:** Clientes y repartidores desde el teléfono.
**Implementación con Laravel:**
```
routes/api.php
├── POST   /auth/login
├── GET    /productos
├── POST   /carrito/agregar
├── POST   /pedidos/confirmar
├── GET    /mis-pedidos
└── PATCH  /pedidos/{id}/estado  (repartidor)
```
- Usar `Laravel Sanctum` para autenticación por token.
- Los mismos modelos y servicios actuales sirven para la API.
- Sin duplicar lógica de negocio.

#### Sistema de reportes avanzados
**Situación actual:** `ReportService` genera reportes básicos con KPIs y gráficas.
**Mejoras:**
- Exportación a PDF con `barryvdh/laravel-dompdf`.
- Exportación a Excel con `maatwebsite/excel`.
- Reportes programados por email (producción semanal, ventas del mes).
- Dashboard en tiempo real con WebSockets (`Laravel Reverb` o `Pusher`).

#### Sistema de alertas de stock
- Definir umbral mínimo por producto (`producto.stock_minimo`).
- Job de Laravel que corra cada hora y notifique al admin si algún producto baja del umbral.
- Notificaciones en el panel y por email.

#### Multi-empresa (SaaS)
Si el sistema se quiere ofrecer a varias distribuidoras:
- Agregar tabla `empresa` con sus propios productos, usuarios y configuración.
- Middleware de tenant para aislar datos entre empresas.
- Subdominio por empresa: `empresa1.trigestion.com`, `empresa2.trigestion.com`.
- Implementar con `tenancyforlaravel/tenancy` o manualmente con un campo `id_empresa` en las tablas principales.

---

## 7. Puntos de Atención Técnica

Estos puntos no son bugs, pero conviene tenerlos en cuenta antes de escalar:

| Punto | Descripción | Recomendación |
|---|---|---|
| Stock dinámico en catálogo | Con muchos productos y ventas, la consulta de stock por producto se vuelve costosa | Agregar caché con `Cache::remember()` de 1-2 minutos |
| `misCompras` con `with()` | Carga todas las relaciones en memoria | Implementar paginación cuando el historial crezca |
| Imágenes en `public/` | Las imágenes se guardan en `public/img/productos/` | Migrar a `storage/` con `php artisan storage:link` para mejor control |
| Sin soft deletes | Los registros eliminados desaparecen permanentemente | Considerar `SoftDeletes` en `Producto` y `Venta` para auditoría |
| Sesiones en archivo | Por defecto Laravel usa sesiones en disco | Con múltiples servidores, migrar a `SESSION_DRIVER=redis` |
| Sin rate limiting en AJAX | El carrito no tiene límite de peticiones | Agregar `throttle:60,1` a las rutas AJAX del carrito |

---

## 8. Diagrama de Flujo de Datos (DFD simplificado)

```
EXTERNO          PROCESOS                    ALMACENES
─────────        ────────                    ─────────

Proveedor ──► Registrar Lote ──────────────► lote
                    │                        detalles
                    └──────────────────────► inventario_materia_prima

Trabajador ──► Iniciar Producción ─────────► produccion
                    │
               Finalizar Producción ───────► inventario_productos
                    │                        inventario_materia_prima (-)

Administrador ──► Crear Producto ──────────► producto
                    │
                ──► Gestionar Venta ────────► venta (estado)

Cliente ──► Ver Catálogo ◄──────────────── producto + inventario_productos
                    │                       detalle_venta + venta
                ──► Agregar al Carrito ───► session (sin BD)
                    │
                ──► Confirmar Pedido ──────► venta
                    │                       detalle_venta

Operario ──► Registrar Devolución ─────────► devolucion_retornables
                    │                        inventario_materia_prima (si apto)
```

---

## 9. Tecnologías Sugeridas para Escalar

| Necesidad | Tecnología recomendada | Integración con Laravel |
|---|---|---|
| Cache de stock | Redis | `CACHE_DRIVER=redis` + `Cache::remember()` |
| Colas de trabajos | Redis + Laravel Horizon | Notificaciones asíncronas por email |
| Tiempo real | Laravel Reverb (WebSockets) | Actualizaciones de estado en vivo |
| API móvil | Laravel Sanctum | `routes/api.php` + tokens por usuario |
| PDF/Excel | DomPDF + Maatwebsite Excel | Exportación de reportes |
| Búsqueda avanzada | Laravel Scout + Meilisearch | Búsqueda full-text en productos |
| Monitoreo | Laravel Telescope | Debug en desarrollo |
| Despliegue | Laravel Forge + DigitalOcean | Servidor dedicado con CI/CD |

---

*Última actualización: Agosto 2026 — refleja el estado del sistema tras la revisión completa de flujo, corrección de stock, eliminación de IVA y limpieza de bugs documentada en esta sesión.*
