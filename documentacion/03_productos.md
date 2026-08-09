# Módulo: Productos

## ¿Para qué sirve?

Este módulo gestiona el catálogo de productos de TRIGESTION: las diferentes presentaciones de agua purificada que se ofrecen a los clientes (garrafones, botellas, etc.). El administrador puede crear, editar, habilitar/deshabilitar y eliminar productos. Los clientes los ven en su catálogo para hacer pedidos.

---

## ¿Quién puede usarlo?

| Rol | Tipo de acceso |
|-----|----------------|
| Administrador (rol 1) | ✅ Gestión completa (crear, editar, eliminar, activar/desactivar) |
| Trabajador (rol 2) | ❌ Sin acceso a la gestión |
| Cliente (rol 3) | ✅ Solo lectura — ve el catálogo para comprar |

---

## ¿Dónde está en la aplicación?

- **Gestión (admin):** `/productos` → Menú: Productos
- **Catálogo (cliente):** `/catalogo` → Menú: Catálogo de Productos

---

## ¿Qué puede hacer el Administrador?

### 1. Ver todos los productos
La pantalla muestra una grilla con tarjetas de cada producto. En la parte superior hay 4 contadores:
- Total de productos
- Productos activos
- Productos inactivos
- Total de categorías

Cada tarjeta del producto muestra:
- Imagen del producto
- Nombre
- Precio
- Categoría
- Si es retornable (badge especial)
- Estado (Activo / Inactivo)
- Botones de acción: Editar, Habilitar/Inhabilitar, Eliminar

También hay una barra de búsqueda en tiempo real que filtra por nombre.

### 2. Crear un producto
Al hacer clic en **"Nuevo Producto"** aparece un formulario con:

| Campo | Obligatorio | Descripción |
|-------|-------------|-------------|
| Nombre | ✅ | Nombre del producto (ej. "Garrafón 20L") |
| Precio | ✅ | Precio de venta (número con decimales) |
| Categoría | ✅ | A qué categoría pertenece |
| ¿Es retornable? | Opcional | Activa el ciclo de devolución del envase |
| Imagen | Opcional | JPG, PNG, WEBP o GIF (máx. 5 MB) |

El producto se crea activo por defecto.

### 3. Editar un producto
Se pueden modificar todos los campos. Si no se sube una nueva imagen, se conserva la existente.

### 4. Habilitar / Inhabilitar un producto
Un producto **inactivo** no aparece en el catálogo del cliente ni puede venderse. Útil para retirar temporalmente un producto sin eliminarlo.

### 5. Eliminar un producto
Elimina el producto permanentemente. Si tenía imagen guardada en el servidor, también se elimina el archivo.

---

## ¿Qué ve el Cliente? (Catálogo)

El cliente accede a `/catalogo` y ve todos los productos activos con:
- Imagen
- Nombre
- Precio
- Categoría
- Botón **"Agregar al Carrito"**

El cliente puede filtrar por categoría usando los botones en la parte superior, y buscar por nombre con la barra de búsqueda.

---

## El campo "Retornable"

Este campo es muy importante. Si un producto tiene `retornable = true` (como un garrafón), significa que el envase debe devolverse después de la venta. El módulo de **Devoluciones** usa este campo para identificar qué productos participan del ciclo de reutilización.

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `producto` | Almacena todos los datos del producto |
| `categoria` | Relación de cada producto con su categoría |
| `usuarios` | El usuario que creó el producto queda registrado |

### Campos de la tabla `producto`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_producto` | Entero (PK) | Identificador único |
| `nombre` | Texto | Nombre del producto |
| `precio` | Decimal | Precio de venta |
| `img` | Texto | Ruta de la imagen |
| `id_usuario` | Entero (FK) | Quien creó el producto |
| `id_categoria` | Entero (FK) | Categoría a la que pertenece |
| `estado` | Booleano | 1 = activo, 0 = inactivo |
| `retornable` | Booleano | 1 = el envase se devuelve |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/ProductoController.php` |
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
    Abre Productos → ve grilla con todos los productos
    Crea → llena formulario → sube imagen opcional → activo por defecto
    Edita → modifica datos
    Inhabilita → deja de aparecer en catálogo
    Elimina → borra producto e imagen

Cliente:
    Abre Catálogo → ve solo productos activos
    Filtra por categoría o busca por nombre
    Hace clic en "Agregar al Carrito" → producto va al carrito
```
