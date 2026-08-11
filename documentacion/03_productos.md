# Módulo: Productos

## ¿Para qué sirve?

Gestiona el catálogo de productos de TRIGESTION: las diferentes presentaciones de agua purificada (garrafones, botellas, etc.). El administrador crea, edita, habilita/deshabilita y elimina productos. Los clientes los ven en el catálogo para hacer pedidos, con visibilidad del stock disponible en tiempo real.

---

## ¿Quién puede usarlo?

| Rol | Tipo de acceso |
|---|---|
| Administrador (rol 1) | ✅ Gestión completa |
| Trabajador (rol 2) | ❌ Sin acceso |
| Cliente (rol 3) | ✅ Solo lectura — catálogo con stock visible |

---

## ¿Dónde está en la aplicación?

- **Gestión (admin):** `/productos`
- **Catálogo (cliente):** `/catalogo`

---

## Gestión de productos (Administrador)

### Ver todos los productos
Grilla de tarjetas con contadores en la parte superior:
- Total de productos
- Productos activos
- Productos inactivos
- Total de categorías

Cada tarjeta muestra imagen, nombre, precio, categoría, badge retornable, estado y botones de acción. Barra de búsqueda en tiempo real.

### Crear un producto

| Campo | Obligatorio | Notas |
|---|---|---|
| Nombre | ✅ | Ej: "Garrafón 20L" |
| Precio | ✅ | Precio final (ya incluye impuestos, sin IVA adicional) |
| Categoría | ✅ | Debe existir y estar activa |
| ¿Es retornable? | Opcional | Activa el ciclo de devolución del envase |
| Imagen | Opcional | JPG, PNG, WEBP o GIF — máx. 5 MB |

El producto se crea **activo** por defecto.

### Editar un producto
Modifica todos los campos. Si no se sube nueva imagen, se conserva la existente.

### Habilitar / Inhabilitar
Un producto **inactivo** no aparece en el catálogo ni puede venderse. No requiere eliminarlo.

### Eliminar
Borra el producto permanentemente junto con su imagen del servidor.

---

## Catálogo para el Cliente

El cliente accede a `/catalogo` y ve todos los productos activos. Para cada producto se muestra:

- Imagen del producto
- Precio
- **Stock disponible** — indicador en verde: `✔ X en stock`
- Si no hay stock: imagen en escala de grises, overlay "Sin Stock", indicador rojo "Agotado"
- Botón **"Agregar al Carrito"** — deshabilitado automáticamente si el stock es 0

El cliente puede filtrar por categoría (botones superiores) y buscar por nombre (buscador en tiempo real).

### Comportamiento del botón según stock

| Condición | Botón | Apariencia de la tarjeta |
|---|---|---|
| Stock > 0 | Activo — "Agregar al Carrito" | Normal, imagen en color |
| Stock = 0 | Deshabilitado — "Sin Disponibilidad" | Opacidad reducida, imagen en gris, overlay "Sin Stock" |

---

## El campo "Retornable"

Si un producto tiene `retornable = true` (como un garrafón), significa que el envase debe devolverse después de la venta. El módulo de **Devoluciones** usa este campo para identificar qué productos participan del ciclo de reutilización.

---

## Cálculo del stock

El stock que se muestra en el catálogo se calcula mediante `StockService::disponible()`:

```
Stock = SUM(inventario_productos.cantidad para este producto)
      − SUM(detalle_venta.cantidad WHERE venta.estado IN ('En Proceso', 'Entregado'))
```

Las ventas en estado `Pendiente` o `Cancelado` **no descuentan** stock.

---

## Tablas involucradas

| Tabla | Uso |
|---|---|
| `producto` | Datos del producto |
| `categoria` | Categoría del producto |
| `inventario_productos` | Stock disponible |
| `detalle_venta` + `venta` | Unidades vendidas (descuento de stock) |

### Campos de `producto`

| Campo | Tipo | Descripción |
|---|---|---|
| `id_producto` | Entero (PK) | Identificador único |
| `nombre` | Texto | Nombre del producto |
| `precio` | Decimal(10,2) | Precio de venta (impuestos incluidos) |
| `img` | Texto | Ruta relativa de la imagen |
| `id_usuario` | FK → usuarios | Quién creó el producto |
| `id_categoria` | FK → categoria | Categoría asignada |
| `estado` | Booleano | 1 = activo, 0 = inactivo |
| `retornable` | Booleano | 1 = el envase debe devolverse |

---

## Archivos clave

| Tipo | Archivo |
|---|---|
| Controlador | `app/Http/Controllers/ProductoController.php` |
| Servicio | `app/Services/StockService.php` |
| Vista (admin) | `resources/views/productos/index.blade.php` |
| Vista (catálogo) | `resources/views/productos/catalogo.blade.php` |
| Modelo | `app/Models/Producto.php` |
| Request (crear) | `app/Http/Requests/StoreProductoRequest.php` |
| Request (editar) | `app/Http/Requests/UpdateProductoRequest.php` |
| Rutas | `routes/web.php` → `role:1` (gestión) y `role:3` (catálogo) |

---

## Flujo resumido

```
Administrador:
    Abre /productos → grilla de tarjetas
    Crear  → formulario → activo por defecto
    Editar → modifica datos
    Inhabilitar → desaparece del catálogo
    Eliminar → borra producto e imagen

Cliente:
    Abre /catalogo → solo productos activos
    Ve stock en tiempo real en cada tarjeta
    Productos sin stock → tarjeta bloqueada
    Agrega al carrito → solo si hay stock
```
