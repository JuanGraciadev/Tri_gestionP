# TRIGESTION — Sistema de Gestión Empresarial

> Plataforma Laravel para distribuidora de agua purificada. Gestiona el ciclo completo: materia prima → producción → inventario → ventas → devoluciones.

---

## Stack tecnológico

| Tecnología | Versión | Uso |
|---|---|---|
| Laravel | 13 | Framework PHP principal |
| PHP | 8.x | Lenguaje de programación |
| MySQL | 8.x | Base de datos |
| Tailwind CSS | CDN | Estilos y diseño visual |
| Alpine.js | 3.x | Interactividad (menús, dropdowns) |
| SweetAlert2 | 11 | Alertas y confirmaciones |
| Font Awesome | 6.5 | Iconografía |
| Fetch API | — | AJAX para el carrito sin recargar página |

---

## Instalación local

```bash
git clone <repo>
cd Tri-gestionP
composer install
cp .env.example .env
php artisan key:generate
# Configura DB en .env
php artisan migrate
php artisan serve
```

---

## Roles del sistema

| Rol | ID | Panel | Acceso |
|---|---|---|---|
| Administrador | 1 | `/admin/dashboard` | Control total |
| Trabajador | 2 | `/trabajador/dashboard` | Producción e inventarios |
| Cliente | 3 | `/cliente/dashboard` | Catálogo y pedidos |

El middleware `CheckRole` redirige automáticamente al panel correcto según el rol. Un usuario suspendido no puede iniciar sesión.

---

## Documentación completa

Ver carpeta [`documentacion/`](./documentacion/00_indice.md) para el detalle de cada módulo.

---

## Convenciones del proyecto

- Sin `timestamps` de Laravel — fechas manejadas manualmente donde aplica
- PKs descriptivas: `id_producto`, `id_usuario`, `id_venta`, etc.
- `DB::transaction()` en todas las operaciones críticas (checkout, finalizar producción, devoluciones)
- Carrito en sesión del servidor, no en BD
- Sin IVA — los precios ya incluyen todos los impuestos
- Stock calculado dinámicamente: `inventario_productos - ventas confirmadas (En Proceso + Entregado)`
- Ventas `Pendiente` NO descuentan stock hasta ser confirmadas
