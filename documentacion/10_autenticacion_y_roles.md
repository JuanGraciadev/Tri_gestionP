# Módulo: Autenticación y Roles

## ¿Para qué sirve?

Este módulo controla quién puede entrar al sistema y qué puede ver o hacer una vez dentro. TRIGESTION tiene tres tipos de usuarios, cada uno con su propio espacio de trabajo y permisos específicos.

---

## Los tres roles del sistema

| ID | Nombre | ¿Qué puede hacer? |
|----|--------|-------------------|
| 1 | **Administrador** | Control total: usuarios, productos, categorías, ventas y reportes |
| 2 | **Trabajador** | Operaciones de planta: lotes, producción, inventarios y devoluciones |
| 3 | **Cliente** | Comprar: catálogo, carrito, checkout y ver sus pedidos |

---

## ¿Cómo funciona el inicio de sesión?

El sistema usa el sistema de autenticación de Laravel (Laravel Breeze) con las siguientes adaptaciones:

- La tabla de usuarios se llama `usuarios` (no `users` como es el estándar)
- La clave primaria es `id_usuario` (no `id`)
- El campo de nombre es `nombres` (no `name`)

Al iniciar sesión correctamente, el sistema detecta el rol del usuario y lo redirige automáticamente a su propio dashboard:

```
Rol 1 → /admin/dashboard     (Panel de Administración)
Rol 2 → /trabajador/dashboard (Panel del Trabajador)
Rol 3 → /cliente/dashboard    (Portal del Cliente)
```

Si un usuario intenta entrar a `/dashboard` sin saber su ruta específica, el sistema lo redirige al lugar correcto según su rol.

---

## ¿Cómo se protegen las rutas?

Hay dos capas de protección:

### Capa 1: Autenticación (`auth`)
Todas las rutas del sistema requieren que el usuario haya iniciado sesión. Si no está autenticado, lo lleva al login.

### Capa 2: Rol (`role:X`)
Además de estar autenticado, el usuario debe tener el rol correcto para acceder a ciertas secciones. Esto lo maneja el middleware `CheckRole`.

**¿Qué pasa si un usuario intenta entrar a una sección que no le corresponde?**
El sistema lo redirige silenciosamente a su propio dashboard sin mostrar error. Por ejemplo, si un cliente intenta abrir `/admin/dashboard`, lo manda de vuelta a `/cliente/dashboard`.

---

## Tabla de acceso por módulo

| Módulo / Ruta | Admin (1) | Trabajador (2) | Cliente (3) |
|---------------|:---------:|:--------------:|:-----------:|
| Panel de Administración `/admin/dashboard` | ✅ | ❌ | ❌ |
| Gestión de Usuarios | ✅ | ❌ | ❌ |
| Categorías | ✅ | ❌ | ❌ |
| Productos (gestión) | ✅ | ❌ | ❌ |
| Ventas (gestión admin) | ✅ | ❌ | ❌ |
| Reportes | ✅ | ❌ | ❌ |
| Panel del Trabajador `/trabajador/dashboard` | ❌ | ✅ | ❌ |
| Producción | ❌ | ✅ | ❌ |
| Lotes | ✅ | ✅ | ❌ |
| Inventario Materia Prima | ✅ | ✅ | ❌ |
| Inventario Productos | ✅ | ✅ | ❌ |
| Devoluciones | ✅ | ✅ | ❌ |
| Portal del Cliente `/cliente/dashboard` | ❌ | ❌ | ✅ |
| Catálogo de Productos | ❌ | ❌ | ✅ |
| Carrito de compras | ❌ | ❌ | ✅ |
| Mis Compras | ❌ | ❌ | ✅ |
| Perfil de usuario `/profile` | ✅ | ✅ | ✅ |

---

## El middleware CheckRole

Este middleware verifica que el rol del usuario coincida con el requerido por la ruta. Se usa en las rutas así:

```php
Route::middleware(['auth', 'role:1'])->group(...)   // Solo admin
Route::middleware(['auth', 'role:2'])->group(...)   // Solo trabajador
Route::middleware(['auth', 'role:3'])->group(...)   // Solo cliente
Route::middleware(['auth', 'role:1,2'])->group(...) // Admin y trabajador
```

Si el rol no coincide, redirige al dashboard correspondiente al rol real del usuario.

---

## Perfil de usuario

Todos los roles pueden editar su propio perfil en `/profile`. Desde ahí pueden cambiar:
- Nombre
- Correo electrónico
- Contraseña

---

## Cierre de sesión

El botón de cerrar sesión está disponible en el menú de usuario de cada pantalla (esquina superior derecha). Al hacer clic, se destruye la sesión y se redirige al login.

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `usuarios` | Almacena todos los usuarios del sistema |
| `rol` | Define los tipos de rol disponibles |

### Campos clave de `usuarios`

| Campo | Descripción |
|-------|-------------|
| `id_usuario` | Identificador único |
| `nombres` | Nombre completo del usuario |
| `email` | Correo electrónico (único) |
| `password` | Contraseña encriptada con bcrypt |
| `id_rol` | Rol asignado (1, 2 o 3) |
| `estado` | 1 = activo, 0 = suspendido |
| `telefono` | Número de contacto |
| `direccion` | Dirección del usuario |
| `documento_numero` | Número de documento de identidad |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Middleware de roles | `app/Http/Middleware/CheckRole.php` |
| Modelo de usuario | `app/Models/User.php` |
| Modelo de rol | `app/Models/Rol.php` |
| Rutas de autenticación | `routes/auth.php` |
| Rutas principales | `routes/web.php` |
| Vistas de login/registro | `resources/views/auth/` |

---

## Flujo de inicio de sesión

```
Usuario abre el sistema en /
    ↓
Ingresa correo y contraseña en el login
    ↓
Sistema verifica credenciales
    ↓
Si son correctas → detecta el rol
    Rol 1 → /admin/dashboard
    Rol 2 → /trabajador/dashboard
    Rol 3 → /cliente/dashboard
    ↓
Si están incorrectas → mensaje de error, se queda en el login
    ↓
Si el usuario está suspendido → no puede ingresar
```
