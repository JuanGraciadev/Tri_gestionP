# Módulo: Ventas y Carrito de Compras

## ¿Para qué sirve?

Este módulo permite a los **clientes** explorar el catálogo de productos, agregar artículos a su carrito y confirmar pedidos. También permite al **administrador** gestionar todos los pedidos del sistema, cambiar su estado y crear ventas directas desde el punto de venta (POS).

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ✅ Gestión de ventas, cambio de estados, POS |
| Trabajador (rol 2) | ❌ Sin acceso |
| Cliente (rol 3) | ✅ Catálogo, carrito, checkout y mis compras |

---

## Parte 1: El Carrito (solo clientes)

### ¿Dónde está?
- Catálogo: `/catalogo`
- Checkout: no hay una URL fija, el carrito se gestiona por AJAX

### ¿Cómo funciona?

El carrito se guarda en la **sesión del navegador**. Mientras el cliente no cierre la sesión, los productos en el carrito se mantienen.

### Agregar un producto al carrito
Desde el catálogo, el cliente hace clic en **"Agregar al Carrito"**. El sistema:
1. Verifica que el producto esté activo.
2. Consulta el stock disponible real (inventario menos ventas activas).
3. Si hay stock suficiente: agrega el producto y aumenta el contador del carrito.
4. Si no hay stock: muestra un mensaje de error sin agregar.

### Ver el carrito
Un ícono de carrito en la cabecera muestra cuántos productos hay. Al hacer clic, el cliente va al checkout donde ve todos los artículos con opción de modificar cantidades.

### Modificar cantidad
El cliente puede aumentar o reducir la cantidad con los botones `+` y `−`. El sistema valida el stock en cada cambio. Si la nueva cantidad supera el stock, muestra error sin actualizar.

### Eliminar un producto
Botón `✕` junto a cada producto. Si se baja la cantidad a 0, también se elimina.

### Vaciar el carrito
Botón "Vaciar" en el checkout. Pide confirmación antes de borrar todo.

### Comunicación AJAX
Todas las operaciones del carrito funcionan sin recargar la página (AJAX). Las respuestas del servidor incluyen:
- Si fue exitoso (`ok: true/false`)
- Mensaje informativo
- Nuevo total del carrito
- Nuevo contador de ítems

---

## Parte 2: Checkout (confirmar compra)

### ¿Cómo funciona?

La página de checkout (`/checkout`) muestra:
- Tabla con todos los productos del carrito (imagen, nombre, precio unitario, cantidad, subtotal)
- Formulario de pago a la derecha con:
  - **Método de pago:** Efectivo, Transferencia o Tarjeta
  - **Notas del pedido:** Campo opcional para instrucciones especiales
  - **Resumen del total**
  - Botón **"Confirmar Pedido"**

### ¿Qué pasa al confirmar?

El sistema ejecuta todo dentro de una **transacción de base de datos** para garantizar consistencia:

```
1. Verifica stock de CADA producto del carrito
    → Si alguno no tiene stock suficiente: cancela todo y muestra error

2. Crea el registro en la tabla "venta"
    → Fecha, total, método de pago, notas, usuario, estado = "Pendiente"

3. Por cada producto del carrito:
    → Crea un registro en "detalle_venta" (producto, cantidad, precio)
    → Crea un registro negativo en "inventario_productos" (descuenta el stock)

4. Limpia el carrito de la sesión

5. Redirige a "Mis Compras" con mensaje de éxito
```

Si cualquier paso falla, se revierten todos los cambios.

---

## Parte 3: Mis Compras (historial del cliente)

### ¿Dónde está?
- **URL:** `/mis-compras`
- **Menú:** Mis Compras

### ¿Qué muestra?
Lista de todos los pedidos realizados por el cliente autenticado. Por cada pedido se ve:
- Número de pedido
- Fecha
- Método de pago
- Estado del pedido (Pendiente, Completada, Cancelada)
- Total pagado
- Desglose de productos (expandible)
- Notas (si las hay)

---

## Parte 4: Gestión de Ventas (solo administrador)

### ¿Dónde está?
- **URL:** `/ventas`
- **Menú:** Ventas y Pedidos

### ¿Qué muestra?

4 contadores en la parte superior:
- Total de pedidos
- Total recaudado (sin cancelados)
- Pedidos pendientes
- Pedidos completados

Tabla con todos los pedidos del sistema:
- Número de pedido
- Cliente / Usuario
- Fecha
- Total
- Método de pago
- Estado con badge de color
- Acciones: Ver detalle, Completar, Cancelar, Pendiente

Búsqueda en tiempo real por número de pedido o nombre de cliente.

### Cambiar el estado de un pedido

El administrador puede cambiar el estado con un solo clic. Los estados disponibles son:

| Estado | Descripción |
|--------|-------------|
| Pendiente | Pedido recibido, pendiente de procesamiento |
| Completada | Pedido entregado al cliente |
| Cancelada | Pedido cancelado — el stock se devuelve automáticamente |

**Importante:** Si se cancela un pedido, el sistema automáticamente crea registros positivos en `inventario_productos` para devolver el stock. Si se reactiva un pedido cancelado, se vuelve a descontar el stock.

### Ver detalle de un pedido
Pantalla completa con toda la información del pedido:
- Datos del cliente
- Fecha y método de pago
- Tabla de productos con cantidades y precios
- Total
- Botones de cambio de estado

---

## Cálculo del stock disponible

El stock disponible se calcula dinámicamente:

```
Stock = SUM(cantidad en inventario_productos) - SUM(cantidad en ventas activas)
```

Las ventas "Canceladas" no se cuentan al calcular el stock, lo que permite que esos productos vuelvan a estar disponibles.

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `venta` | Un registro por pedido realizado |
| `detalle_venta` | Un registro por cada producto dentro del pedido |
| `inventario_productos` | Se descuenta al vender (valores negativos) |
| `producto` | Catálogo de productos disponibles |
| `usuarios` | Cliente que realizó el pedido |

### Campos de `venta`

| Campo | Descripción |
|-------|-------------|
| `id_venta` | Identificador único |
| `fecha` | Fecha del pedido |
| `estado` | Pendiente / Completada / Cancelada |
| `id_usuario` | Cliente que compró |
| `id_producto` | Primer producto (campo heredado) |
| `total` | Monto total del pedido |
| `notas` | Instrucciones especiales |
| `metodo_pago` | Efectivo / Transferencia / Tarjeta |

### Campos de `detalle_venta`

| Campo | Descripción |
|-------|-------------|
| `id_detalle_de_venta` | Identificador único |
| `id_venta` | Pedido al que pertenece |
| `id_producto` | Producto comprado |
| `cantidad` | Unidades compradas |
| `precio_unitario` | Precio al momento de la compra |
| `descuento` | Descuento aplicado (0 por defecto) |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/VentaController.php` |
| Controlador | `app/Http/Controllers/CarritoController.php` |
| Vista (catálogo) | `resources/views/productos/catalogo.blade.php` |
| Vista (checkout) | `resources/views/ventas/checkout.blade.php` |
| Vista (mis compras) | `resources/views/ventas/mis_compras.blade.php` |
| Vista (admin ventas) | `resources/views/ventas/index.blade.php` |
| Vista (detalle venta) | `resources/views/ventas/show.blade.php` |
| Modelo | `app/Models/Venta.php` |
| Modelo | `app/Models/DetalleVenta.php` |
| Rutas | `routes/web.php` → `role:1` (admin) y `role:3` (cliente) |

---

## Flujo completo del cliente

```
Cliente abre Catálogo
    ↓
Hace clic en "Agregar al Carrito" (AJAX)
    → Verifica stock → agrega o muestra error
    ↓
Abre Checkout
    → Ve productos, cantidades, total
    → Selecciona método de pago, escribe notas
    ↓
Confirma el pedido (transacción)
    → Verifica stock final
    → Crea venta + detalles + descuenta inventario
    → Limpia carrito
    → Redirige a Mis Compras con confirmación
```

## Flujo del administrador

```
Admin abre Ventas y Pedidos
    ↓
Ve todos los pedidos con estados y totales
    ↓
Puede cambiar estado:
    Completar → pedido entregado
    Cancelar  → stock devuelto automáticamente
    ↓
Puede ver el detalle completo de cada pedido
```
