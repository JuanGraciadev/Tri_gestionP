# Módulo: Categorías

## ¿Para qué sirve?

Las categorías son la forma de organizar los productos del catálogo. Cada producto pertenece a una categoría (por ejemplo: "Garrafones", "Botellas Pequeñas", "Presentaciones Especiales"). Este módulo permite crear, editar, activar/desactivar y eliminar esas categorías.

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ✅ Acceso completo |
| Trabajador (rol 2) | ❌ Sin acceso |
| Cliente (rol 3) | ❌ Sin acceso |

---

## ¿Dónde está en la aplicación?

- **URL:** `/categorias`
- **Menú:** Categorías (panel izquierdo del Administrador)

---

## ¿Qué puede hacer el Administrador?

### 1. Ver todas las categorías
La pantalla principal muestra tarjetas con todas las categorías registradas. Para cada una se muestra:
- Nombre
- Descripción
- Imagen (si tiene)
- Cantidad de productos asociados
- Estado (Activa / Inactiva)

En la parte superior aparecen 3 contadores:
- Total de categorías
- Categorías activas
- Total de productos en el sistema

### 2. Crear una categoría
Al hacer clic en **"Nueva Categoría"** aparece un formulario con:

| Campo | Obligatorio | Descripción |
|-------|-------------|-------------|
| Nombre | ✅ | Nombre de la categoría |
| Descripción | Opcional | Texto descriptivo |
| Imagen | Opcional | JPG, PNG, WEBP o GIF (máx. 5 MB) |

### 3. Editar una categoría
Se pueden modificar el nombre, descripción e imagen. Si no se sube una imagen nueva, se conserva la anterior.

### 4. Activar / Desactivar una categoría
Las categorías inactivas no aparecen en el catálogo del cliente. Sirve para ocultar temporalmente una línea de productos sin eliminarla.

### 5. Eliminar una categoría
Solo se puede eliminar si **no tiene productos asociados**. Si la categoría tiene productos, el sistema muestra un aviso indicando cuántos productos debe reasignar o eliminar primero.

### 6. Agregar un producto rápido a una categoría
Existe un acceso rápido para crear un producto directamente desde la vista de categorías, especificando nombre, precio y seleccionando la categoría destino.

---

## Reglas de negocio importantes

- Una categoría con productos **no puede eliminarse**.
- Una categoría **inactiva no aparece** en el catálogo público del cliente.
- Si se sube una imagen, el archivo anterior en el servidor no se elimina automáticamente (solo al eliminar la categoría completa).

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `categoria` | Almacena las categorías con nombre, descripción, imagen y estado |
| `producto` | Se relaciona con categorías mediante `id_categoria` |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/CategoriaController.php` |
| Vista | `resources/views/categorias/index.blade.php` |
| Modelo | `app/Models/Categoria.php` |
| Request (crear) | `app/Http/Requests/StoreCategoriaRequest.php` |
| Request (editar) | `app/Http/Requests/UpdateCategoriaRequest.php` |
| Rutas | `routes/web.php` → grupo `role:1` |

---

## Flujo resumido

```
Admin abre Categorías
    ↓
Ve todas las categorías con contadores
    ↓
Puede crear nueva → sube imagen opcional → categoría activa por defecto
Puede editar → modifica datos o imagen
Puede activar/desactivar → controla visibilidad en catálogo
Puede eliminar → solo si no tiene productos
Puede agregar producto rápido → desde la misma pantalla
```
