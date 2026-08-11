# Módulo: Devoluciones de Garrafones

## ¿Para qué sirve?

Este módulo maneja el ciclo de retorno de los **garrafones y envases retornables**. Cuando un cliente devuelve un garrafón, el operario registra la devolución y determina si el envase está en buen estado (puede reutilizarse) o está dañado (debe descartarse).

Este módulo es exclusivo para productos marcados como **"retornables"** en el catálogo. Los productos desechables normales no participan en este flujo.

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ✅ Acceso completo |
| Trabajador (rol 2) | ✅ Acceso completo |
| Cliente (rol 3) | ❌ Sin acceso |

---

## ¿Dónde está en la aplicación?

- **URL:** `/devoluciones`
- **Menú:** Devoluciones

---

## El ciclo del garrafón

Un garrafón no es un producto desechable. Su ciclo de vida es:

```
1. El garrafón llega a bodega (vía Lotes)
        ↓ sube al Inventario de Materia Prima
2. Se usa en Producción (se llena de agua)
        ↓ baja del Inventario de Materia Prima
3. Se vende al cliente con el agua
        ↓ baja del Inventario de Productos
4. El cliente devuelve el garrafón vacío
        ↓ se registra la devolución
5a. Si está en buen estado → vuelve al Inventario de Materia Prima
        ↓ puede volver a llenarse y venderse
5b. Si está dañado → NO vuelve al inventario
        ↓ queda fuera del ciclo permanentemente
```

---

## ¿Qué operaciones están disponibles?

### 1. Ver historial y balances
La pantalla muestra:
- **Balances por cliente:** Cuántos garrafones le fueron entregados a cada cliente, cuántos ha devuelto y cuántos aún tiene pendientes de devolución
- **Historial completo:** Todos los registros de devoluciones con fecha, producto, cantidad, estado y operario

En la parte superior hay 3 contadores:
- Total de garrafones entregados
- Total de garrafones devueltos
- Total en consumo (aún no devueltos)

### 2. Registrar una devolución
Al hacer clic en **"Registrar Devolución"** aparece un formulario con:

| Campo | Obligatorio | Descripción |
|-------|-------------|-------------|
| Cliente | ✅ | El usuario que devuelve el garrafón |
| Producto | ✅ | Solo se muestran productos retornables |
| Cantidad | ✅ | Número de garrafones devueltos (mínimo 1) |
| Estado del envase | ✅ | Apto para uso / Dañado |
| Bodega | Opcional | Bodega destino (por defecto "Bodega Principal") |

**Validación importante:** El sistema verifica que la cantidad a devolver no supere la cantidad que el cliente tiene pendiente de devolución. No se puede registrar más devoluciones de las que corresponden.

---

## ¿Qué pasa cuando se guarda una devolución?

### Si el garrafón está **APTO**:
```
1. Se registra en devolucion_retornables
        ↓ (transacción de base de datos)
2. Se crea una entrada en inventario_materia_prima:
    - ingreso = cantidad devuelta
    - bodega = "Bodega Principal"
    - id_retornables = ID de la devolución
```
El garrafón **vuelve a estar disponible** para ser llenado en producción.

### Si el garrafón está **DAÑADO**:
```
1. Se registra en devolucion_retornables
        (solo el registro histórico)
```
**No se crea ninguna entrada** en el inventario de materia prima. El garrafón queda fuera del ciclo productivo permanentemente.

---

## Diferencia entre un garrafón apto y uno dañado

| Condición | ¿Vuelve al inventario? | ¿Puede volver a venderse? |
|-----------|----------------------|--------------------------|
| Apto, buen estado | ✅ Sí | ✅ Sí |
| Dañado / Roto / Contaminado | ❌ No | ❌ No |

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `devolucion_retornables` | Registra cada devolución con sus datos |
| `inventario_materia_prima` | Recibe la entrada si el garrafón está apto |
| `producto` | Se usa `retornable = 1` para filtrar los productos válidos |
| `usuarios` | El operario que registra y el cliente que devuelve |

### Campos de `devolucion_retornables`

| Campo | Descripción |
|-------|-------------|
| `id_retornables` | Identificador único |
| `cantidad` | Unidades devueltas |
| `id_producto` | Producto retornable devuelto |
| `id_usuario` | Usuario que devuelve |
| `fecha` | Fecha y hora automática |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/DevolucionController.php` |
| Vista | `resources/views/devoluciones/index.blade.php` |
| Modelo | `app/Models/DevolucionRetornables.php` |
| Request | `app/Http/Requests/StoreDevolucionRequest.php` |
| Rutas | `routes/web.php` → grupo `role:1,2` |

---

## Flujo resumido

```
Operario abre Devoluciones
    ↓
Ve balances por cliente (cuántos tiene pendientes)
    ↓
Hace clic en "Registrar Devolución"
    → Selecciona cliente, producto, cantidad y estado del envase
    ↓
Si está APTO:
    → Registro en devolucion_retornables
    → Entrada en inventario_materia_prima
    → Garrafón disponible para producción

Si está DAÑADO:
    → Solo registro en devolucion_retornables
    → Garrafón descartado, no vuelve al stock
```
