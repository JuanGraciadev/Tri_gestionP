# Base de Datos: Estructura General

## ¿Para qué sirve este documento?

Aquí se describe de manera clara y completa todas las tablas que usa TRIGESTION, para qué sirve cada una y cómo se relacionan entre sí. Es útil para entender el sistema sin tener que leer código.

---

## Diagrama de relaciones (simplificado)

```
rol ──────────── usuarios
                    │
                    ├──── lote ──────── detalles ──── inventario_materia_prima
                    │                                        │
                    │                                        │ (también vinculada)
                    │                                 devolucion_retornables
                    │                                        │
                    ├──── categoria ── producto              │
                    │                    │                   │
                    │                    ├──── produccion ───┘
                    │                    │         │
                    │                    │         └──── inventario_productos
                    │                    │
                    │                    └──── detalle_venta ── venta ── cliente
                    │
                    └──── venta (también tiene id_usuario)
```

---

## Descripción de cada tabla

---

### `rol`
Guarda los tipos de usuario del sistema.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_rol` | Entero (PK) | Identificador del rol |
| `nombre` | Texto | Nombre del rol (Administrador, Trabajador, Cliente) |

---

### `usuarios`
Todos los usuarios del sistema, sin importar su rol.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_usuario` | Entero (PK) | Identificador único |
| `nombres` | Texto | Nombre completo |
| `email` | Texto | Correo (único en el sistema) |
| `password` | Texto | Contraseña encriptada |
| `id_rol` | Entero (FK → rol) | Rol asignado |
| `estado` | Entero | 1 = activo, 0 = suspendido |
| `telefono` | Texto | Teléfono (opcional) |
| `direccion` | Texto | Dirección |
| `documento_numero` | Texto | Cédula o ID (opcional) |
| `remember_token` | Texto | Token de sesión persistente |

---

### `cliente`
Tabla de vinculación entre un usuario con rol 3 y sus compras. Cada cliente es también un usuario.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_cliente` | Entero (PK) | Identificador del cliente |
| `id_usuario` | Entero (FK → usuarios) | El usuario asociado |

---

### `categoria`
Categorías para organizar los productos del catálogo.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_categoria` | Entero (PK) | Identificador único |
| `nombre` | Texto | Nombre de la categoría |
| `descripcion` | Texto | Descripción (opcional) |
| `imagen` | Texto | Ruta de la imagen (opcional) |
| `estado` | Booleano | 1 = activa, 0 = inactiva |

---

### `producto`
Catálogo de productos disponibles para venta.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_producto` | Entero (PK) | Identificador único |
| `nombre` | Texto | Nombre del producto |
| `precio` | Decimal | Precio de venta |
| `img` | Texto | Ruta de la imagen |
| `id_usuario` | Entero (FK → usuarios) | Quién lo creó |
| `id_categoria` | Entero (FK → categoria) | Categoría del producto |
| `estado` | Booleano | 1 = activo, 0 = inactivo |
| `retornable` | Booleano | 1 = el envase debe devolverse |

---

### `lote`
Lotes de materia prima (envases) que ingresan a bodega.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_lote` | Entero (PK) | Identificador único |
| `codigo_lote` | Texto | Código legible del lote |
| `id_usuario` | Entero (FK → usuarios) | Quién registró el lote |

---

### `detalles`
Líneas de detalle de cada lote (qué envases llegaron).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_detalles` | Entero (PK) | Identificador único |
| `unidades` | Entero | Cantidad de envases |
| `tipo_envase` | Texto | Tipo (Garrafón, Botella, etc.) |
| `capacidad` | Texto | Capacidad (20L, 500ml, etc.) |
| `proveedor` | Texto | Nombre del proveedor |
| `id_lote` | Entero (FK → lote) | Lote al que pertenece |

---

### `inventario_materia_prima`
Stock de envases disponibles para producción. Se llena desde lotes o devoluciones y se descuenta al finalizar producciones.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_inventario_materia` | Entero (PK) | Identificador único |
| `ingreso` | Entero | Unidades disponibles (el stock actual) |
| `fecha` | Fecha | Cuándo se registró |
| `bodega` | Texto | Nombre de la bodega |
| `id_detalles` | Entero (FK → detalles) | Origen: detalle de lote (puede ser nulo) |
| `id_retornables` | Entero (FK → devolucion_retornables) | Origen: devolución (puede ser nulo) |

---

### `devolucion_retornables`
Registro de garrafones devueltos por clientes.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_retornables` | Entero (PK) | Identificador único |
| `cantidad` | Entero | Garrafones devueltos |
| `id_producto` | Entero (FK → producto) | Producto retornable devuelto |
| `id_usuario` | Entero (FK → usuarios) | Cliente que devuelve |
| `fecha` | DateTime | Fecha y hora automática |

---

### `produccion`
Procesos de producción (llenado de agua en envases).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_produccion` | Entero (PK) | Identificador único |
| `lote_produccion` | Texto | Código del lote de producción |
| `cantidad` | Entero | Unidades a producir |
| `estado` | Texto | "En Producción" o "Finalizada" |
| `descripcion` | Texto | Notas del proceso (opcional) |
| `id_usuario` | Entero (FK → usuarios) | Operario responsable |
| `id_producto` | Entero (FK → producto) | Producto que se fabrica |
| `id_inventario_materia` | Entero (FK → inventario_materia_prima) | MP vinculada (opcional) |

---

### `inventario_productos`
Stock de productos terminados. Valores positivos son entradas (de producción), negativos son salidas (de ventas).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_inventario` | Entero (PK) | Identificador único |
| `fecha` | Fecha | Fecha del movimiento |
| `bodega` | Texto | Nombre de la bodega |
| `id_produccion` | Entero (FK → produccion) | Producción de origen (puede ser nulo) |
| `id_producto` | Entero (FK → producto) | Producto involucrado |
| `id_usuario` | Entero (FK → usuarios) | Responsable del movimiento |
| `cantidad` | Entero | Unidades (positivo = entrada, negativo = salida) |

---

### `venta`
Cabecera de cada pedido realizado por un cliente.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_venta` | Entero (PK) | Identificador único |
| `fecha` | Fecha | Fecha del pedido |
| `cantidad` | Entero | Total de unidades (campo legacy) |
| `precio` | Decimal | Precio referencial (campo legacy) |
| `estado` | Texto | Pendiente / Completada / Cancelada |
| `id_cliente` | Entero (FK → cliente) | Cliente que compró |
| `id_usuario` | Entero (FK → usuarios) | Usuario que registró |
| `id_producto` | Entero (FK → producto) | Primer producto (campo legacy) |
| `total` | Decimal | Monto total del pedido |
| `notas` | Texto | Instrucciones especiales |
| `metodo_pago` | Texto | Efectivo / Transferencia / Tarjeta |

---

### `detalle_venta`
Líneas de producto de cada pedido. Un pedido puede tener múltiples líneas.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_detalle_de_venta` | Entero (PK) | Identificador único |
| `precio_unitario` | Decimal | Precio al momento de la compra |
| `descuento` | Decimal | Descuento aplicado (0 por defecto) |
| `id_venta` | Entero (FK → venta) | Pedido al que pertenece |
| `cantidad` | Entero | Unidades compradas |
| `id_producto` | Entero (FK → producto) | Producto comprado |

---

## Resumen de relaciones clave

| Relación | Tipo | Descripción |
|----------|------|-------------|
| `usuarios` → `rol` | Muchos a uno | Cada usuario tiene un rol |
| `cliente` → `usuarios` | Uno a uno | Un cliente es un usuario |
| `producto` → `categoria` | Muchos a uno | Cada producto tiene una categoría |
| `lote` → `usuarios` | Muchos a uno | Un usuario registra el lote |
| `detalles` → `lote` | Muchos a uno | Varios detalles por lote |
| `inventario_materia_prima` → `detalles` | Uno a uno | Un registro por detalle de lote |
| `inventario_materia_prima` → `devolucion_retornables` | Uno a uno | Un registro por devolución apta |
| `produccion` → `producto` | Muchos a uno | Cada producción fabrica un producto |
| `produccion` → `inventario_materia_prima` | Muchos a uno | Una producción puede consumir una entrada de MP |
| `inventario_productos` → `produccion` | Uno a uno | Una entrada de stock por producción |
| `venta` → `cliente` | Muchos a uno | Un cliente tiene muchas ventas |
| `detalle_venta` → `venta` | Muchos a uno | Una venta tiene varios detalles |
| `detalle_venta` → `producto` | Muchos a uno | Cada línea referencia un producto |
