# Módulo: Inventario de Materia Prima

## ¿Para qué sirve?

El **Inventario de Materia Prima** lleva el control del stock de envases y garrafones vacíos disponibles para ser llenados en el proceso de producción. Este inventario se alimenta de dos fuentes:

1. **Lotes:** Cuando se agrega un detalle a un lote, el sistema registra automáticamente la entrada de envases.
2. **Devoluciones:** Cuando un cliente devuelve un garrafón en buen estado, también ingresa como entrada al inventario.

El stock **disminuye** cuando se finaliza una producción que consume esos envases.

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ✅ Consulta |
| Trabajador (rol 2) | ✅ Consulta |
| Cliente (rol 3) | ❌ Sin acceso |

> **Nota:** No existe un formulario para crear registros manualmente. Todo se genera automáticamente desde Lotes o Devoluciones.

---

## ¿Dónde está en la aplicación?

- **URL:** `/inventario-mp`
- **Menú:** Inventario MP

---

## ¿Qué información se muestra?

La pantalla principal tiene:

### Contadores en la parte superior
- **Registros totales:** Cuántas entradas hay en el inventario
- **Unidades disponibles:** Suma total del campo `ingreso` de todos los registros
- **Registros con stock:** Cuántos registros tienen más de 0 unidades

### Tabla de registros
Por cada entrada del inventario se muestra:
- **Lote:** Código del lote del que proviene (o "—" si vino de una devolución)
- **Tipo de Envase:** Ej. "Garrafón"
- **Capacidad:** Ej. "20 Litros"
- **Proveedor:** Nombre del proveedor del envase
- **Stock (Ingreso):** Unidades actuales disponibles en ese registro
  - Badge verde si tiene stock
  - Badge rojo si ya no tiene
- **Bodega:** Siempre "Bodega Principal" para entradas de lotes
- **Fecha:** Cuándo fue registrado

### Búsqueda en tiempo real
Un campo de búsqueda filtra los registros por lote, tipo de envase, proveedor o bodega sin recargar la página.

---

## ¿Cómo sube el stock?

### Desde un Lote
```
Operario agrega un detalle al lote:
    unidades = 100, tipo_envase = "Garrafón", capacidad = "20L"
        ↓ (automático)
Nuevo registro en inventario_materia_prima:
    ingreso = 100, bodega = "Bodega Principal", id_detalles = ID del detalle
```

### Desde una Devolución
```
Cliente devuelve 5 garrafones en buen estado
        ↓ (automático)
Nuevo registro en inventario_materia_prima:
    ingreso = 5, bodega = "Bodega Principal", id_retornables = ID de la devolución
```

---

## ¿Cómo baja el stock?

```
Se finaliza una producción vinculada a un registro de materia prima:
        ↓
El campo "ingreso" del registro se reduce:
    nuevo_ingreso = max(0, ingreso_actual - cantidad_producida)
```

El valor nunca puede quedar negativo — el mínimo es 0.

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `inventario_materia_prima` | Tabla central con los registros de stock |
| `detalles` | Origen de las entradas por lote |
| `lote` | Lote padre del detalle |
| `devolucion_retornables` | Origen de entradas por devolución de garrafones |

### Campos de `inventario_materia_prima`

| Campo | Descripción |
|-------|-------------|
| `id_inventario_materia` | Identificador único |
| `ingreso` | Unidades disponibles actualmente (es el stock) |
| `fecha` | Fecha del registro |
| `bodega` | Nombre de la bodega |
| `id_detalles` | Si vino de un lote (puede ser nulo) |
| `id_retornables` | Si vino de una devolución (puede ser nulo) |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/InventarioMateriaPrimaController.php` |
| Vista | `resources/views/inventario_mp/index.blade.php` |
| Modelo | `app/Models/InventarioMateriaPrima.php` |
| Rutas | `routes/web.php` → grupo `role:1,2` |

---

## Flujo general del stock de materia prima

```
Entra stock:
    Por Lote → al agregar detalle → +ingreso
    Por Devolución → garrafón en buen estado → +ingreso

Sale stock:
    Al finalizar una Producción → ingreso -= cantidad_producida

Límite:
    ingreso nunca puede ser < 0
```
