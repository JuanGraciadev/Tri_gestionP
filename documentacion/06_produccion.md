# Módulo: Producción

## ¿Para qué sirve?

El módulo de **Producción** registra los procesos de llenado y fabricación de productos terminados (agua purificada en sus diferentes presentaciones). Cuando una producción se finaliza, el sistema automáticamente:

1. Registra los productos terminados en el **Inventario de Productos** (aumenta el stock disponible para venta).
2. Descuenta las unidades de materia prima usadas del **Inventario de Materia Prima** (si se vinculó una entrada de MP).

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ❌ Sin acceso a este módulo |
| Trabajador (rol 2) | ✅ Puede iniciar y finalizar producciones |
| Cliente (rol 3) | ❌ Sin acceso |

---

## ¿Dónde está en la aplicación?

- **URL:** `/produccion`
- **Menú:** Producción (panel del Trabajador)

---

## ¿Qué operaciones están disponibles?

### 1. Ver todas las producciones
La pantalla muestra tarjetas con cada proceso de producción. En la parte superior aparecen 3 contadores:
- Producciones **En Producción** (en proceso)
- Producciones **Finalizadas**
- Total de **Unidades Producidas** (suma de finalizadas)

Cada tarjeta muestra:
- Código del lote de producción
- Producto que se está fabricando
- Cantidad a producir
- Operario responsable
- Materia prima vinculada (si aplica)
- Descripción o notas
- Estado con badge animado: `En Proceso` (parpadeante) o `Finalizada`
- Botón **"Finalizar Producción"** (solo visible si está en proceso)

### 2. Iniciar una nueva producción
Al hacer clic en **"Iniciar Producción"** aparece un formulario con:

| Campo | Obligatorio | Descripción |
|-------|-------------|-------------|
| Código de Lote | ✅ | Identificador del lote de producción (ej. `LOTE-2024X`) |
| Cantidad a Producir | ✅ | Número de unidades (mínimo 1) |
| Producto Terminado | ✅ | Qué producto se va a fabricar |
| Materia Prima a Utilizar | Opcional | Registro de MP del que se consumirán envases |
| Descripción | Opcional | Notas o instrucciones del proceso |

La producción se crea con estado **"En Producción"**.

### 3. Finalizar una producción

Al confirmar la finalización (con diálogo de SweetAlert), el sistema ejecuta dentro de una **transacción de base de datos**:

**Paso 1:** Cambia el estado a `"Finalizada"`.

**Paso 2:** Crea un registro en `inventario_productos` con:
- Fecha actual
- Bodega = "Principal"
- Producto = el producto de la producción
- Cantidad = la cantidad producida
- Vinculado a esta producción

> Si ya existe un registro de inventario para esta producción (por alguna razón se intentó finalizar dos veces), no se crea un duplicado.

**Paso 3:** Si había materia prima vinculada, descuenta las unidades usadas:
- `nuevo_ingreso = max(0, ingreso_actual - cantidad_producida)`

---

## Reglas de negocio importantes

- Una producción **ya finalizada no puede finalizarse de nuevo**.
- Si se intenta finalizar dos veces, el sistema muestra un error.
- La transacción garantiza que si algo falla a mitad del proceso, **ningún cambio queda guardado** (todo se revierte).
- El campo `ingreso` de la materia prima nunca puede quedar negativo.

---

## Relación con otros módulos

```
Inventario de Materia Prima
    ↑ se crea desde Lotes
    ↓ se descuenta al finalizar Producción

Producción finaliza
    → crea entrada en Inventario de Productos (+stock)
    → descuenta entrada de Inventario de Materia Prima (-stock)
```

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `produccion` | Registra cada proceso de producción |
| `inventario_productos` | Se llena al finalizar (productos terminados) |
| `inventario_materia_prima` | Se descuenta al finalizar (envases usados) |

### Campos de `produccion`

| Campo | Descripción |
|-------|-------------|
| `id_produccion` | Identificador único |
| `lote_produccion` | Código del lote (texto libre) |
| `cantidad` | Unidades a producir |
| `estado` | "En Producción" o "Finalizada" |
| `descripcion` | Notas del proceso |
| `id_usuario` | Operario que inició la producción |
| `id_producto` | Producto que se produce |
| `id_inventario_materia` | MP vinculada (opcional) |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/ProduccionController.php` |
| Vista | `resources/views/produccion/index.blade.php` |
| Modelo | `app/Models/Produccion.php` |
| Request | `app/Http/Requests/StoreProduccionRequest.php` |
| Rutas | `routes/web.php` → grupo `role:2` |

---

## Flujo resumido

```
Trabajador abre Producción
    ↓
Hace clic en "Iniciar Producción"
    → llena formulario (lote, cantidad, producto, MP opcional)
    → producción queda en estado "En Producción"
    ↓
Cuando la producción física termina:
    Hace clic en "Finalizar Producción" → confirma en el diálogo
    ↓ (transacción de base de datos)
    1. Estado → "Finalizada"
    2. Se registra el producto terminado en Inventario de Productos
    3. Se descuenta la MP usada (si estaba vinculada)
```
