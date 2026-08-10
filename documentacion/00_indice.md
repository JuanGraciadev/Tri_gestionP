# TRIGESTION — Documentación del Sistema

## ¿Qué es TRIGESTION?

TRIGESTION es un sistema de gestión empresarial desarrollado en **Laravel 13** para una empresa distribuidora de agua purificada. Maneja todo el ciclo operativo del negocio: desde el ingreso de materia prima (envases) hasta la venta al cliente final, pasando por la producción, los inventarios y las devoluciones de garrafones.

---

## Tecnologías utilizadas

| Tecnología | Uso |
|------------|-----|
| Laravel 13 | Framework PHP principal |
| PHP | Lenguaje de programación |
| MySQL / SQLite | Base de datos |
| Tailwind CSS (CDN) | Estilos y diseño visual |
| Alpine.js | Interactividad en las vistas (menús, dropdowns) |
| SweetAlert2 | Alertas y confirmaciones |
| Font Awesome 6 | Iconos |
| AJAX (Fetch API) | Operaciones del carrito sin recargar página |

---

## Estructura de roles

| Rol | ¿Quién es? | ¿Qué puede hacer? |
|-----|------------|-------------------|
| **Administrador** | Dueño o gerente | Control total del sistema |
| **Trabajador** | Personal de planta | Producción, lotes e inventarios |
| **Cliente** | Comprador | Catálogo, pedidos y sus compras |

---

## Índice de módulos documentados

| # | Archivo | Módulo | Roles con acceso |
|---|---------|--------|-----------------|
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

---

## Flujo general del negocio

El siguiente diagrama muestra cómo se conectan los módulos en el día a día:

```
1. ADMINISTRADOR crea categorías y productos
        ↓
2. TRABAJADOR registra lotes de envases que llegan a bodega
        ↓ (automático)
   Los envases entran al Inventario de Materia Prima
        ↓
3. TRABAJADOR inicia una producción (llenado de agua)
        ↓
4. TRABAJADOR finaliza la producción
        ↓ (automático, transacción)
   Los productos terminados entran al Inventario de Productos
   Los envases salen del Inventario de Materia Prima
        ↓
5. CLIENTE explora el catálogo
        ↓
6. CLIENTE agrega productos al carrito (AJAX, con verificación de stock)
        ↓
7. CLIENTE confirma el pedido (checkout)
        ↓ (automático, transacción)
   Se registra la venta y sus detalles
   Los productos salen del Inventario de Productos
        ↓
8. ADMINISTRADOR cambia el estado del pedido
   (Pendiente → En Proceso → Completada / Cancelada)
        ↓
9. CLIENTE devuelve el garrafón vacío
        ↓
10. TRABAJADOR registra la devolución
    Si el garrafón está APTO → vuelve al Inventario de Materia Prima (paso 3)
    Si está DAÑADO → sale del ciclo permanentemente
```

---

## Paneles por rol

### Panel del Administrador (`/admin/dashboard`)
Acceso a: Usuarios · Categorías · Productos · Ventas y Pedidos · Reportes · Inventarios · Lotes · Devoluciones

### Panel del Trabajador (`/trabajador/dashboard`)
Acceso a: Producción · Inventario MP · Gestión de Lotes · Inventario Productos · Devoluciones

### Portal del Cliente (`/cliente/dashboard`)
Acceso a: Catálogo de Productos · Carrito · Mis Compras

---

## Rutas principales del sistema

| URL | Módulo | Rol |
|-----|--------|-----|
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

---

## Convenciones del proyecto

- **Sin timestamps automáticos:** Ningún modelo usa `created_at` / `updated_at` de Laravel. Las fechas se registran manualmente donde son necesarias.
- **Claves primarias personalizadas:** Todas las tablas usan nombres descriptivos como `id_producto`, `id_usuario`, `id_venta`.
- **Transacciones en operaciones críticas:** Producción finalizar, checkout y cambio de estado con cancelación usan `DB::transaction()` para garantizar consistencia.
- **Carrito en sesión:** El carrito del cliente se almacena en la sesión del servidor, no en la base de datos.
- **AJAX para carrito:** Las operaciones del carrito (agregar, actualizar, eliminar) funcionan sin recargar la página.
- **SweetAlert2 para confirmaciones:** Todas las acciones destructivas (eliminar, cancelar, finalizar) piden confirmación con un diálogo visual.
- **Sidebar dinámico:** Un solo partial (`partials/sidebar.blade.php`) detecta el rol del usuario y muestra el menú correspondiente.
