# Módulo: Gestión de Lotes

## ¿Para qué sirve?

Los **lotes** representan compras o ingresos de materia prima (principalmente envases y garrafones vacíos) que llegan a la bodega. Cada lote tiene un código único y puede contener múltiples líneas de detalle con información específica sobre el tipo de envase, su capacidad, las unidades recibidas y el proveedor.

Cuando se registran los detalles de un lote, el sistema automáticamente genera entradas en el **Inventario de Materia Prima**, incrementando el stock de envases disponibles para producción.

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ✅ Acceso completo |
| Trabajador (rol 2) | ✅ Acceso completo |
| Cliente (rol 3) | ❌ Sin acceso |

---

## ¿Dónde está en la aplicación?

- **URL:** `/lotes`
- **Menú:** Gestión de Lotes

---

## ¿Qué operaciones están disponibles?

### 1. Ver todos los lotes
La pantalla principal muestra una tabla con todos los lotes registrados. Por cada lote se ve:
- Código del lote (ej. `L-202310A`)
- Usuario que lo registró
- Cantidad de detalles que tiene
- Botones: Ver Detalles, Editar, Eliminar

### 2. Crear un lote (Paso 1)
Al hacer clic en **"Nuevo Lote"** aparece un modal donde solo se pide el **Código del Lote**. Al guardar, el sistema redirige automáticamente a la página de detalles del lote recién creado para que el operario ingrese los datos completos.

Este flujo en dos pasos evita crear lotes vacíos.

### 3. Editar el código de un lote
Solo se puede cambiar el código del lote. El usuario responsable no se modifica.

### 4. Eliminar un lote
Cuando se elimina un lote, el sistema borra **en cascada**:
1. Los registros de inventario de materia prima vinculados a los detalles del lote
2. Todos los detalles del lote
3. El lote en sí

Esto garantiza que no queden registros huérfanos en el inventario.

---

## Detalles del Lote (Paso 2)

Cada lote puede tener múltiples líneas de detalle. Para añadir un detalle se necesita:

| Campo | Obligatorio | Descripción |
|-------|-------------|-------------|
| Unidades | ✅ | Cantidad de envases recibidos (mínimo 1) |
| Tipo de Envase | ✅ | Ej. "Garrafón", "Botella 500ml" |
| Capacidad | ✅ | Ej. "20 Litros", "500 ml" |
| Proveedor | ✅ | Nombre del proveedor |

**Importante:** Al guardar un detalle, el sistema automáticamente crea un registro en el Inventario de Materia Prima con:
- Ingreso = cantidad de unidades del detalle
- Bodega = "Bodega Principal"
- Fecha = fecha del día
- Vinculado al detalle recién creado

### Editar un detalle
Se pueden modificar todos los campos del detalle (unidades, tipo de envase, capacidad, proveedor). **No se modifica el inventario de materia prima** al editar el detalle — solo el registro del detalle.

---

## Relación con Inventario de Materia Prima

```
Crear Lote
    ↓
Agregar Detalle (unidades, tipo_envase, capacidad, proveedor)
    ↓ (automático)
Se crea registro en inventario_materia_prima
    (ingreso = unidades del detalle, bodega = 'Bodega Principal')
    ↓
El stock de materia prima aumenta
```

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `lote` | Código del lote y usuario responsable |
| `detalles` | Líneas de detalle de cada lote |
| `inventario_materia_prima` | Stock de envases (se llena automáticamente) |

### Campos de `lote`

| Campo | Descripción |
|-------|-------------|
| `id_lote` | Identificador único |
| `codigo_lote` | Código legible del lote |
| `id_usuario` | Quién registró el lote |

### Campos de `detalles`

| Campo | Descripción |
|-------|-------------|
| `id_detalles` | Identificador único |
| `unidades` | Cantidad de envases |
| `tipo_envase` | Tipo de envase (garrafón, botella, etc.) |
| `capacidad` | Capacidad del envase |
| `proveedor` | Proveedor de los envases |
| `id_lote` | Lote al que pertenece |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/LoteController.php` |
| Vista (lista) | `resources/views/lotes/index.blade.php` |
| Vista (detalles) | `resources/views/lotes/detalles.blade.php` |
| Modelo | `app/Models/Lote.php` |
| Modelo | `app/Models/LoteDetalle.php` |
| Rutas | `routes/web.php` → grupo `role:1,2` |

---

## Flujo resumido

```
Operario abre Gestión de Lotes
    ↓
Hace clic en "Nuevo Lote" → ingresa el código
    ↓ (el sistema redirige automáticamente a Detalles)
Agrega detalles (unidades, envase, capacidad, proveedor)
    ↓ (automático por cada detalle guardado)
Se registra en Inventario de Materia Prima
    ↓
El stock de envases disponibles aumenta
```
