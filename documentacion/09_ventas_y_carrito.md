# Módulo: Ventas y Carrito de Compras

## ¿Para qué sirve?

Permite a los **clientes** explorar el catálogo, agregar productos al carrito y confirmar pedidos. Al **administrador** le permite gestionar todos los pedidos, cambiar sus estados y registrar ventas directas desde el punto de venta (POS).

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|---|---|
| Administrador (rol 1) | ✅ Gestión de ventas, cambio de estados, POS |
| Trabajador (rol 2) | ❌ Sin acceso |
| Cliente (rol 3) | ✅ Catálogo, carrito, checkout y mis compras |

---

## Parte 1: El Carrito (clientes)

### Cómo funciona

El carrito se guarda en la **sesión del servidor**. Los datos persisten mientras la sesión esté activa.

Estructura de cada ítem en sesión:
```php
[
    'id_producto'     => int,
    'nombre'          => string,
    'precio_unitario' => float,  // precio al momento de agregar
    'img'             => string,
    'cantidad'        => int,
]
```

### Agregar un producto

1. Cliente hace clic en "Agregar al Carrito" desde el catálogo.
2. El sistema verifica que el producto esté **activo** (`estado = 1`).
3. Consulta el **stock disponible** con `StockService::disponible()`.
4. Si hay stock: agrega al carrito y actualiza el badge del contador.
5. Si no hay stock: responde con mensaje de error, sin modificar el carrito.

> El stock solo considera ventas en estado `En Proceso` o `Entregado`. Las ventas `Pendiente` no bloquean stock.

### Modificar cantidad
Botones `+` / `−` en el carrito lateral (drawer). Cada cambio valida el stock en tiempo real. Si la cantidad supera el stock disponible, el sistema rechaza el cambio con un mensaje.

### Eliminar / Vaciar
Botón `✕` por ítem o botón "Vaciar" para todo el carrito. Pide confirmación antes de vaciar.

### Comunicación AJAX
Todas las operaciones del carrito son asíncronas (no recargan la página):

| Ruta | Método | Descripción | Respuesta |
|---|---|---|---|
| `/carrito/agregar` | POST | Agrega ítem | `{ok, total_items, msg}` |
| `/carrito/obtener` | GET | Retorna carrito completo | `{ok, carrito[], total}` |
| `/carrito/actualizar` | POST | Cambia cantidad | `{ok, carrito[], total, total_items}` |
| `/carrito/eliminar` | POST | Elimina ítem | `{ok, carrito[], total, total_items}` |
| `/carrito/finalizar` | POST | Confirma compra | `{ok, id_venta, factura{}}` |

---

## Parte 2: Checkout — Confirmar la compra

### Proceso al confirmar

Todo se ejecuta dentro de una **transacción de base de datos**:

```
1. Re-verifica el stock de CADA producto del carrito
   → Si alguno no tiene stock suficiente: lanza excepción, rollback completo
   → El mensaje de error indica el nombre del producto afectado

2. Crea el registro en tabla "venta"
   → estado = "Pendiente"
   → fecha = hoy
   → total = suma de (precio_unitario × cantidad) por ítem
   → Sin IVA adicional (precio ya incluye impuestos)

3. Crea un registro en "detalle_venta" por cada ítem

4. Limpia el carrito de la sesión

5. Retorna JSON con datos de la factura
```

> **Importante:** Al crear la venta con estado `Pendiente`, el stock **no se descuenta** todavía. El inventario solo se afecta cuando el admin cambia el estado a `En Proceso` o `Entregado`.

### Cálculo del total

```
total = Σ (precio_unitario × cantidad − descuento) por cada ítem
```

Sin multiplicador de IVA. El precio registrado en el producto ya es el precio final.

---

## Parte 3: Mis Compras (historial del cliente)

- **URL:** `/mis-compras`

Lista todos los pedidos del cliente autenticado usando Eloquent con relaciones (`detalles.producto`). Por cada pedido muestra:
- Número de pedido
- Fecha
- Productos incluidos (lista separada por comas)
- Total
- Estado con badge de color
- Barra de progreso: Pendiente → En Proceso → Entregado
- Filtro por rango de fechas (client-side)

---

## Parte 4: Gestión de Ventas (Administrador)

### ¿Dónde está?
- **URL:** `/ventas`

### Panel principal

4 contadores en la parte superior:
- Pendientes / En Proceso / Entregados / Cancelados
- **Total ingresos** — suma de ventas en estado `En Proceso` o `Entregado`

Tabla paginada con todos los pedidos. Búsqueda en tiempo real.

### Estados y su efecto en el stock

| Estado | ¿Descuenta stock? | Descripción |
|---|---|---|
| `Pendiente` | ❌ No | Recién creado por el cliente |
| `En Proceso` | ✅ Sí | Admin confirma — el stock se descuenta |
| `Entregado` | ✅ Sí | Pedido entregado al cliente |
| `Cancelado` | ❌ No | Cancelado — el stock no se afecta |

> El cambio de estado lo realiza el admin mediante el botón de estado en la tabla de ventas.

### Punto de Venta (POS)

El administrador puede registrar ventas directas sin que el cliente pase por el carrito:

1. Selecciona el cliente (usuario con rol 3)
2. Elige productos y cantidades
3. El sistema valida stock antes de confirmar
4. La venta se crea directamente en estado `Entregado` (venta presencial inmediata)
5. El total se calcula sin IVA adicional

### Ver factura de un pedido

Endpoint AJAX: `GET /ventas/factura/{id_venta}`

Retorna JSON con todos los datos del pedido para mostrar en modal o impresión.

---

## Cálculo del stock disponible

`StockService::disponible(int $idProducto)`:

```php
$ingresado = InventarioProductos::where('id_producto', $id)->sum('cantidad');

$vendido = DetalleVenta::join('venta', ...)
    ->whereIn('venta.estado', ['En Proceso', 'Entregado'])
    ->where('id_producto', $id)
    ->sum('cantidad');

return max(0, $ingresado - $vendido);
```

---

## Tablas involucradas

| Tabla | Uso |
|---|---|
| `venta` | Cabecera de cada pedido |
| `detalle_venta` | Líneas de producto por pedido |
| `inventario_productos` | Stock de productos terminados |
| `producto` | Datos del producto vendido |
| `cliente` | Comprador del pedido |
| `usuarios` | Datos del cliente y del operador |

### Campos de `venta`

| Campo | Descripción |
|---|---|
| `id_venta` | Identificador único |
| `fecha` | Fecha del pedido |
| `estado` | `Pendiente` / `En Proceso` / `Entregado` / `Cancelado` |
| `id_cliente` | FK → cliente |
| `id_usuario` | FK → usuarios (quien registró) |
| `total` | Monto total (sin IVA) |
| `notas` | Instrucciones especiales |
| `metodo_pago` | `Efectivo` / `Transferencia` / `Tarjeta` |

> Los campos `cantidad`, `precio` e `id_producto` de la tabla `venta` son campos heredados del diseño original y no se usan en la lógica actual. El detalle real está en `detalle_venta`.

### Campos de `detalle_venta`

| Campo | Descripción |
|---|---|
| `id_detalle_de_venta` | Identificador único |
| `id_venta` | FK → venta |
| `id_producto` | FK → producto |
| `cantidad` | Unidades compradas |
| `precio_unitario` | Precio al momento de la compra |
| `descuento` | Descuento aplicado (0 por defecto) |

---

## Archivos clave

| Tipo | Archivo |
|---|---|
| Controlador principal | `app/Http/Controllers/VentaController.php` |
| Servicio de stock | `app/Services/StockService.php` |
| Vista catálogo | `resources/views/productos/catalogo.blade.php` |
| Vista checkout | `resources/views/ventas/checkout.blade.php` |
| Vista mis compras | `resources/views/ventas/mis-compras.blade.php` |
| Vista admin ventas | `resources/views/ventas/index.blade.php` |
| Modelo | `app/Models/Venta.php` |
| Modelo | `app/Models/DetalleVenta.php` |
| Modelo | `app/Models/Cliente.php` |

---

## Flujo completo del cliente

```
/catalogo
    ↓ Solo productos activos con stock > 0 disponibles
    ↓ Productos agotados: tarjeta bloqueada, botón deshabilitado
AJAX → /carrito/agregar
    ↓ Verifica stock → agrega o rechaza con mensaje
Drawer del carrito
    ↓ Modifica cantidades / elimina ítems
AJAX → /carrito/finalizar
    ↓ Transacción DB:
        Re-verifica stock de todos los ítems
        Crea venta (estado: Pendiente)
        Crea detalle_venta por ítem
        Limpia carrito
    ↓
/mis-compras → muestra nuevo pedido como "Pendiente"
```

## Flujo del administrador

```
/ventas
    ↓ Ve todos los pedidos
Cambiar estado:
    Pendiente → En Proceso  (stock se descuenta aquí)
    En Proceso → Entregado
    Cualquier estado → Cancelado (stock libre)
    ↓
POS: registra venta directa → estado Entregado inmediato
```
