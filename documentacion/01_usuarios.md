# Módulo: Gestión de Usuarios

## ¿Para qué sirve?

Este módulo le permite al **Administrador** manejar todas las cuentas del sistema. Desde aquí puede crear nuevos usuarios (trabajadores, clientes u otros administradores), editar su información, cambiar su contraseña y activar o suspender su acceso.

---

## ¿Quién puede usarlo?

| Rol | Acceso |
|-----|--------|
| Administrador (rol 1) | ✅ Acceso completo |
| Trabajador (rol 2) | ❌ Sin acceso |
| Cliente (rol 3) | ❌ Sin acceso |

---

## ¿Dónde está en la aplicación?

- **URL:** `/admin/dashboard`
- **Menú:** Gestión Usuarios (panel izquierdo del Administrador)

---

## ¿Qué puede hacer el Administrador?

### 1. Ver todos los usuarios
Al entrar al dashboard, el admin ve una tabla con todos los usuarios registrados. Cada fila muestra:
- Nombre completo
- Correo electrónico
- Dirección
- Rol (Administrador, Trabajador o Cliente)
- Estado (Activo / Suspendido)

También aparecen 4 contadores en la parte superior:
- Total de usuarios
- Cuentas activas
- Administradores registrados
- Clientes registrados

### 2. Crear un nuevo usuario
Al hacer clic en **"Añadir Usuario"** se abre un formulario con los siguientes campos:

| Campo | Obligatorio | Descripción |
|-------|-------------|-------------|
| Nombres completos | ✅ | Nombre y apellido del usuario |
| Dirección | ✅ | Dirección física |
| Correo electrónico | ✅ | Debe ser único en el sistema |
| Contraseña | ✅ | Mínimo 4 caracteres |
| Rol | ✅ | Administrador, Trabajador o Cliente |
| Teléfono | Opcional | Número de contacto |
| Número de documento | Opcional | Cédula o identificación |

El usuario se crea activo por defecto.

### 3. Editar un usuario
Al hacer clic en el ícono de editar de cualquier usuario, se puede modificar:
- Nombre
- Dirección
- Rol
- Contraseña (si se deja en blanco, no cambia)

### 4. Activar / Suspender un usuario
Un botón de candado permite cambiar el estado del usuario entre **Activo** y **Suspendido**. Un usuario suspendido no puede iniciar sesión.

---

## Filtros y búsqueda disponibles

- **Filtro por rol:** Botones para ver solo Admins, solo Trabajadores o solo Clientes
- **Búsqueda en tiempo real:** Campo de texto que filtra la tabla mientras se escribe

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `usuarios` | Almacena todos los datos de los usuarios |
| `rol` | Guarda los tipos de rol disponibles |

---

## Archivos clave

| Tipo | Archivo |
|------|---------|
| Controlador | `app/Http/Controllers/AdminUsuarioController.php` |
| Vista | `resources/views/admin/admin.blade.php` |
| Modelo | `app/Models/User.php` |
| Modelo | `app/Models/Rol.php` |
| Rutas | `routes/web.php` → grupo `role:1` → prefix `admin` |

---

## Flujo resumido

```
Admin abre dashboard
    ↓
Ve tabla de usuarios con filtros y búsqueda
    ↓
Puede crear → llena formulario → usuario queda activo
Puede editar → cambia datos → se guardan
Puede suspender/activar → cambia estado → afecta el acceso al login
```
