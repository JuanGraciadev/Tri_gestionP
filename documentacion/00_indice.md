# TRIGESTION — Documentación del Sistema

## ¿Qué es TRIGESTION?

TRIGESTION es un sistema de gestión empresarial desarrollado en **Laravel 13** para una empresa distribuidora de agua purificada. Maneja todo el ciclo operativo del negocio: desde el ingreso de materia prima (envases) hasta la venta al cliente final, pasando por la producción, los inventarios y las devoluciones de garrafones.

---

## Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| Laravel 13 | Framework PHP principal |
| PHP | Lenguaje de programación |
| MySQL / SQLite | Base de datos |
| Tailwind CSS (CDN) | Estilos y diseño visual |
| Alpine.js | Interactividad en las vistas (menús, dropdowns) |
| SweetAlert2 | Alertas y confirmaciones |
| Font Awesome 6.5 | Iconos |
| Fetch API (AJAX) | Operaciones del carrito sin recargar página |

---

## Estructura de roles

| Rol | ID | ¿Quién es? | ¿Qué puede hacer? |
|---|---|---|---|
| **Administrador** | 1 | Dueño o gerente | Control total del sistema |
| **Trabajador** | 2 | Personal de planta | Producción, lotes e inventarios |
| **Cliente** | 3 | Comprador | Catálogo, pedidos y sus compras |

---

## Índice de módulos documentados

| # | Archivo | Módulo | Roles con acceso |
|---|---|---|---|
| 1 | [01_usuarios.md](./01_usuarios.md) | Gestión de Usuarios | Admin |
| 2 | [02_categorias.md](./02_categorias.md) | Categorías | Admin |
| 3 | [03_productos.md](./03_productos.md) | Productos | Admin (gestión) / Cliente (catálogo) |
| 4 | [04_lotes.md](./04_lotes.md) | Gestión de Lotes | Admin + Trabajador |
| 5 | [05_inventario_materia_prima.md](./05_inventario_materia_prima.md) | Inventario de Materia Prima | Admin + Trabajador |
| 6 | [06_produccion.md](./06_produccion.md) | Producción | Trabajador |
| 7 | [07_inventario_productos.md](./07_inventario_productos.md) | Inventario de Productos | Admin + Trabajador |
| 8 | [08_devoluciones.md](./08_devoluciones.md) | Devoluciones de Garrafones | Admin + Trabajador |
| 9 | [09_ventas_y_carrito.md](./09_ventas_y_carrito.md) | Ventas y Carrito | Admin (gestión) / Cliente (compras) |
| 10 | [10_autenticacion_y_roles.md](./10_autenticacion_y_roles.md) | Autenticación y Roles | Todos |
| 11 | [11_base_de_datos.md](./11_base_de_datos.md) | Estructura de Base de Datos | Referencia técnica |
| 12 | [12_flujo_completo_y_escalabilidad.md](./12_flujo_completo_y_escalabilidad.md) | Flujo Completo y Escalabilidad | Referencia técnica |

---

## Flujo general del negocio

```
1. ADMINISTRADOR crea categorías y productos
        ↓
2. TRABAJADOR registra lotes de envases que llegan a bodega
        ↓ (automático)
   Los envases entran al Inventario de Materia Prima
        ↓
3. TRABAJADOR inicia una producción (llenado de agua)
        ↓
4. TRABAJADOR finaliza la producción (transacción DB)
        ↓
   Productos terminados → Inventario de Productos (+stock)
   Envases usados → Inventario de Materia Prima (-stock)
        ↓
5. CLIENTE explora el catálogo
   ↳ Solo ve productos activos CON stock disponible
   ↳ Productos sin stock aparecen como "Agotado" (botón deshabilitado)
        ↓
6. CLIENTE agrega productos al carrito (AJAX, verifica stock en tiempo real)
        ↓
7. CLIENTE confirma el pedido (transacción DB)
        ↓
   Se registra la Venta (estado: Pendiente)
   Se crean los registros en detalle_venta
   ⚠ El stock NO se descuenta aún (la venta está Pendiente)
        ↓
8. ADMINISTRADOR cambia el estado del pedido
   Pendiente → En Proceso  (el stock se descuenta aquí)
   En Proceso → Entregado  (venta finalizada)
   Cualquier estado → Cancelado (el stock no se descuenta / se libera)
        ↓
9. CLIENTE devuelve el garrafón vacío
        ↓
10. OPERARIO registra la devolución
    Si APTO  → vuelve al Inventario de Materia Prima (listo para producción)
    Si DAÑADO → sale del ciclo permanentemente
```

---

## Lógica de stock

El stock disponible se calcula **dinámicamente** en cada consulta mediante `StockService`:

```
Stock disponible = SUM(inventario_productos.cantidad)
                 − SUM(detalle_venta.cantidad WHERE venta.estado IN ('En Proceso', 'Entregado'))
```

**Estados que descuentan stock:** `En Proceso`, `Entregado`
**Estados que NO descuentan stock:** `Pendiente`, `Cancelado`

Esto permite que los pedidos recién creados no bloqueen inventario hasta que el administrador los confirme.

---

## Precios y facturación

- Los precios registrados en el sistema **ya incluyen todos los impuestos**.
- **No se aplica IVA adicional** en ningún punto del proceso de venta.
- El total de una venta = suma de `(precio_unitario × cantidad) - descuento` por cada línea.

---

## Paneles por rol

### Panel del Administrador (`/admin/dashboard`)
Usuarios · Categorías · Productos · Ventas y Pedidos · Reportes · Inventarios · Lotes · Devoluciones

### Panel del Trabajador (`/trabajador/dashboard`)
Producción · Inventario MP · Gestión de Lotes · Inventario Productos · Devoluciones

### Portal del Cliente (`/cliente/dashboard`)
Catálogo de Productos · Carrito · Mis Compras

---

## Rutas principales del sistema

| URL | Módulo | Rol |
|---|---|---|
| `/` | Landing page pública | Todos (sin sesión) |
| `/admin/dashboard` | Panel Administrador | Admin |
| `/trabajador/dashboard` | Panel Trabajador | Trabajador |
| `/cliente/dashboard` | Portal Cliente | Cliente |
| `/categorias` | Categorías | Admin |
| `/productos` | Gestión Productos | Admin |
| `/catalogo` | Catálogo | Cliente |
| `/lotes` | Gestión de Lotes | Admin + Trabajador |
| `/inventario-mp` | Inventario Materia Prima | Admin + Trabajador |
| `/produccion` | Producción | Trabajador |
| `/inventario-productos` | Inventario Productos | Admin + Trabajador |
| `/devoluciones` | Devoluciones | Admin + Trabajador |
| `/ventas` | Ventas (admin) | Admin |
| `/mis-compras` | Mis Compras | Cliente |
| `/profile` | Perfil de usuario | Todos |

### Rutas AJAX del carrito (solo Cliente)

| URL | Método | Acción |
|---|---|---|
| `/carrito/agregar` | POST | Agrega producto al carrito |
| `/carrito/obtener` | GET | Retorna el carrito actual |
| `/carrito/actualizar` | POST | Cambia la cantidad de un ítem |
| `/carrito/eliminar` | POST | Elimina un ítem del carrito |
| `/carrito/finalizar` | POST | Confirma el pedido (checkout) |

---

## Convenciones del proyecto

- **Sin timestamps automáticos:** Ningún modelo usa `created_at` / `updated_at` de Laravel.
- **Claves primarias personalizadas:** `id_producto`, `id_usuario`, `id_venta`, etc.
- **Transacciones en operaciones críticas:** Checkout, finalizar producción y devoluciones usan `DB::transaction()`.
- **Carrito en sesión:** El carrito del cliente se almacena en la sesión del servidor, no en la BD.
- **Sin IVA:** Los precios ya incluyen impuestos — no se multiplica por ningún factor adicional.
- **Stock por estados confirmados:** Solo `En Proceso` y `Entregado` descuentan stock.
- **Modelo único de devoluciones:** `DevolucionRetornables` (plural) es el único modelo para la tabla `devolucion_retornables`.
- **AJAX para carrito:** Todas las operaciones del carrito funcionan sin recargar la página.
- **SweetAlert2 para confirmaciones:** Acciones destructivas piden confirmación visual.
- **Sidebar dinámico:** `partials/sidebar.blade.php` detecta el rol y muestra el menú correcto.
- **Landing page pública:** `welcome.blade.php` — diseñada en Tailwind con secciones Hero, Calculadora, Beneficios, Productos, FAQ y Footer.
