# Módulo: Inventario de Productos

## ¿Para qué sirve?

El **Inventario de Productos** muestra el stock de productos terminados disponibles para la venta. Cada registro representa una entrada de productos al inventario, generada automáticamente cuando se finaliza una producción.

Este módulo es de **solo consulta**: no existe ningún formulario para agregar o modificar registros manualmente. Todo el stock proviene del módulo de Producción.

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ✅ Consulta |
| Trabajador (rol 2) | ✅ Consulta |
| Cliente (rol 3) | ❌ Sin acceso |

---

## ¿Dónde está en la aplicación?

- **URL:** `/inventario-productos`
- **Menú:** Inventario Productos

---

## ¿Qué información se muestra?

### Contadores en la parte superior
- **Entradas Registradas:** Cuántas producciones han generado entradas al inventario
- **Total Unidades en Stock:** Suma de todas las cantidades positivas registradas
- **Productos con Stock:** Cuántos productos distintos tienen stock disponible

### Tabla de registros
Por cada entrada se muestra:
- **Producto:** Nombre e imagen del producto terminado
- **Lote de Producción:** Código del lote del que proviene
- **Cantidad:** Unidades que ingresaron en esa entrada
- **Stock Acumulado:** Total de unidades disponibles para ese producto (sumando todas sus entradas)
- **Bodega:** Siempre "Principal" para entradas de producción
- **Fecha:** Cuándo se registró la entrada
- **Registrado por:** El operario que finalizó la producción

### Búsqueda en tiempo real
Se puede buscar por nombre de producto, lote o bodega sin recargar la página.

---

## ¿Cómo se calcula el stock disponible?

El stock real disponible de un producto se calcula dinámicamente:

```
Stock disponible = SUM(inventario_productos.cantidad) del producto
                  - SUM(detalle_venta.cantidad) de ventas no canceladas
```

El campo `cantidad` en la tabla puede ser **positivo** (entrada de producción) o **negativo** (salida por venta). De esta manera, el inventario se autoajusta con cada venta.

---

## ¿Cómo entra stock al inventario?

```
Se finaliza una Producción
    ↓ (automático, dentro de una transacción)
Se crea un registro en inventario_productos:
    - fecha = hoy
    - bodega = "Principal"
    - id_produccion = la producción finalizada
    - id_producto = el producto fabricado
    - id_usuario = el operario
    - cantidad = las unidades producidas (positivo)
```

---

## ¿Cómo sale stock del inventario?

```
Cliente hace una compra y se confirma
    ↓ (automático en el checkout)
Se crea un registro en inventario_productos:
    - cantidad = NEGATIVO (las unidades vendidas)
    - vinculado al producto vendido
```

Si una venta se cancela, se crea otro registro **positivo** que devuelve el stock.

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `inventario_productos` | Almacena todas las entradas y salidas de stock |
| `produccion` | Origen de las entradas (vinculado por `id_produccion`) |
| `producto` | El producto al que pertenece cada registro |
| `usuarios` | El operario que registró el movimiento |

### Campos de `inventario_productos`

| Campo | Descripción |
|-------|-------------|
| `id_inventario` | Identificador único |
| `fecha` | Fecha del movimiento |
| `bodega` | Nombre de la bodega ("Principal") |
| `id_produccion` | Producción que generó la entrada (puede ser nulo en salidas) |
| `id_producto` | Producto involucrado |
| `id_usuario` | Responsable del movimiento |
| `cantidad` | Unidades (positivo = entrada, negativo = salida) |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/InventarioProductosController.php` |
| Vista | `resources/views/inventario_productos/index.blade.php` |
| Modelo | `app/Models/InventarioProductos.php` |
| Rutas | `routes/web.php` → grupo `role:1,2` |

---

## Flujo general del stock de productos

```
Producción finalizada
    → +cantidad en inventario_productos (entrada)
    → stock disponible sube

Venta confirmada
    → -cantidad en inventario_productos (salida)
    → stock disponible baja

Venta cancelada
    → +cantidad en inventario_productos (devolución)
    → stock disponible vuelve a subir
```
