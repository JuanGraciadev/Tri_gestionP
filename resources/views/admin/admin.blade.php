@extends('layouts.panel')

@section('title', 'Panel de Administración')
@section('subtitle', 'Gestión de usuarios y control total del sistema')

@section('content')
                @if ($errors->any())
                <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-sm font-semibold flex items-center gap-3 animate-fade">
                    <i class="fas fa-circle-exclamation text-lg shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                </div>
                @endif

                {{-- WELCOME BANNER (AdminDashboard style) --}}
                <div class="relative rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-[#090e17] via-slate-900 to-indigo-950 p-8 sm:p-12 text-white shadow-2xl shadow-indigo-950/30 border border-white/10">
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div class="absolute -top-20 -right-20 w-80 h-80 bg-sky-500/20 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-sky-400 font-bold text-xs mb-4 backdrop-blur-md">
                                <i class="fas fa-shield-halved"></i>
                                <span>Panel Maestro &bull; Administrador General</span>
                            </div>
                            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                                Bienvenido, {{ Auth::user()->nombres ?? 'Administrador' }}
                            </h1>
                            <p class="text-slate-300 text-sm sm:text-base mt-2 max-w-xl font-medium opacity-90">
                                Control central de todas las operaciones de Trigestion: planta de purificaci&oacute;n, stock, ventas, clientes y usuarios.
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            <a href="{{ route('ventas.index') }}" class="px-6 py-3.5 rounded-2xl water-gradient text-white font-black text-sm shadow-xl shadow-sky-500/30 hover:shadow-sky-500/50 transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                <i class="fas fa-cart-shopping"></i>
                                <span>Gestionar Ventas</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ALERTA DE STOCK BAJO DE PRODUCTOS (HU-010 / HU-023 / RF 7.10) --}}
                <div id="contenedorAlertasStockAdmin" class="hidden rounded-[2rem] p-6 border border-amber-200 bg-amber-50/90 shadow-sm animate-fade">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-extrabold text-amber-900 tracking-tight">Alerta de Stock en Productos Terminados</h3>
                            <p class="text-xs text-amber-700 font-medium mt-0.5">Existen productos con stock disponible menor o igual a su stock mínimo.</p>
                            <div id="listaAlertasStockAdmin" class="mt-3 flex flex-wrap gap-2"></div>
                        </div>
                        <a href="{{ route('inventario-productos.index') }}" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-700 transition-colors shrink-0">
                            Ver Inventario
                        </a>
                    </div>
                </div>

                {{-- KPI CARDS --}}
                @php
                    $totalUsuarios    = count($usuarios);
                    $usuariosActivos  = count($usuarios->where('estado', 1));
                    $usuariosInactivos = $totalUsuarios - $usuariosActivos;
                    $totalAdmins      = count($usuarios->where('id_rol', 1));
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    {{-- StatCard: Total Usuarios --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-indigo-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">En el Sistema</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">{{ $totalUsuarios }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">Usuarios Registrados</div>
                        </div>
                    </div>
                    {{-- StatCard: Habilitados --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-emerald-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Acceso Permitido</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">{{ $usuariosActivos }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">Habilitados</div>
                        </div>
                    </div>
                    {{-- StatCard: Inhabilitados --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-rose-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Acceso Bloqueado</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">{{ $usuariosInactivos }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">Inhabilitados</div>
                        </div>
                    </div>
                    {{-- StatCard: Admins --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-sky-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Acceso Total</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">{{ $totalAdmins }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">Administradores</div>
                        </div>
                    </div>
                </div>

                {{-- QUICK OPERATIONS MATRIX (AdminDashboard style) --}}
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-800 tracking-tight">M&oacute;dulos y Operaciones Principales</h2>
                            <p class="text-xs text-slate-400 mt-0.5 font-medium">Acceso directo a las &aacute;reas de gesti&oacute;n del sistema.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $modulos = [
                                ['admin.dashboard', 'fa-users', 'Gesti&oacute;n Usuarios', 'Administra roles, accesos y habilitaci&oacute;n de usuarios.', 'Control Total'],
                                ['ventas.index', 'fa-cart-shopping', 'Ventas y Pedidos', 'Supervisa solicitudes web y ventas en punto de venta.', 'Comercial'],
                                ['productos.index', 'fa-tint', 'Gesti&oacute;n de Productos', 'Administra cat&aacute;logo, presentaciones y precios de venta.', 'Cat&aacute;logo'],
                                ['inventario-mp.index', 'fa-truck-ramp-box', 'Entrada de Insumos', 'Registra compras, recepciones y abastecimiento de materia prima.', 'Abastecimiento'],
                                ['inventario-mp.index', 'fa-chart-bar', 'Inventario MP', 'Gr&aacute;ficas de stock, estado de insumos y trazabilidad de movimientos.', 'Almac&eacute;n'],
                                ['inventario-productos.index', 'fa-cubes', 'Productos Terminados', 'Stock de agua embotellada lista para despacho.', 'Log&iacute;stica'],
                                ['reportes.index', 'fa-file-invoice-dollar', 'Reportes y Anal&iacute;ticas', 'Genera estad&iacute;sticas, gr&aacute;ficos de ventas y balance.', 'Estrategia'],
                            ];
                        @endphp
                        @foreach ($modulos as [$route, $icon, $title, $desc, $badge])
                        <a href="{{ route($route) }}" class="glass-card rounded-[2rem] p-7 border border-white/80 premium-shadow text-left hover:-translate-y-1.5 transition-all duration-300 group flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-sky-100 text-sky-600 flex items-center justify-center text-2xl font-black shadow-inner group-hover:scale-110 transition-transform">
                                        <i class="fas {{ $icon }}"></i>
                                    </div>
                                    <span class="text-[10px] font-black uppercase text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">{!! $badge !!}</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-800 group-hover:text-sky-600 transition-colors">{!! $title !!}</h3>
                                    <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">{!! $desc !!}</p>
                                </div>
                            </div>
                            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-sky-600 group-hover:translate-x-1 transition-transform">
                                <span>Ingresar al m&oacute;dulo</span>
                                <i class="fas fa-arrow-right text-[11px]"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- HEADER & BOTON NUEVO REGISTRO --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pt-4">
                    <div>
                        <div class="inline-block px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold text-xs mb-3 shadow-sm">
                            <i class="fas fa-shield-halved mr-1.5"></i>Control Total
                        </div>
                        <h1 class="text-3xl sm:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                            Gesti&oacute;n de <span class="premium-gradient-text">Usuarios</span>
                        </h1>
                        <p class="text-slate-500 mt-2 text-sm sm:text-base font-medium max-w-xl">Administra accesos, roles y permisos de todos los integrantes del sistema en tiempo real.</p>
                    </div>
                    <button onclick="openModal('modalCrear')" class="premium-gradient text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transform hover:-translate-y-1 transition-all flex items-center gap-3 w-full sm:w-auto justify-center">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] text-white/80 uppercase tracking-wider font-bold">Nuevo Registro</div>
                            <div class="text-base">A&ntilde;adir Usuario</div>
                        </div>
                    </button>
                </div>

                {{-- TABLE CARD --}}
                <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/80 premium-shadow">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-800 tracking-tight">Directorio de Integrantes</h2>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Haz clic en el estado de un usuario para inhabilitarlo o habilitarlo inmediatamente.</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                            {{-- Filtros de Estado --}}
                            <div class="flex items-center bg-slate-100/80 p-1 rounded-xl border border-slate-200/60 text-xs font-bold">
                                <button onclick="filtrarUsuarios('todos')" class="filter-usr px-3 py-1.5 rounded-lg transition-all bg-white text-slate-800 shadow-sm" data-filter="todos">Todos ({{ $totalUsuarios }})</button>
                                <button onclick="filtrarUsuarios('1')" class="filter-usr px-3 py-1.5 rounded-lg transition-all text-slate-500 hover:text-slate-800" data-filter="1">Admin</button>
                                <button onclick="filtrarUsuarios('2')" class="filter-usr px-3 py-1.5 rounded-lg transition-all text-slate-500 hover:text-slate-800" data-filter="2">Trabajador</button>
                                <button onclick="filtrarUsuarios('3')" class="filter-usr px-3 py-1.5 rounded-lg transition-all text-slate-500 hover:text-slate-800" data-filter="3">Cliente</button>
                            </div>
                            {{-- Buscar --}}
                            <div class="relative flex-1 md:w-64">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fas fa-search text-sm"></i>
                                </span>
                                <input type="text" id="searchInput" onkeyup="filtrarSearch()" placeholder="Buscar nombre, email..."
                                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 focus:outline-none focus:border-sky-500">
                            </div>
                        </div>
                    </div>

                    {{-- TABLA --}}
                    <div class="overflow-x-auto custom-scrollbar w-full">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                    <th class="pb-3 px-4">Usuario</th>
                                    <th class="pb-3 px-4">Documento / Tel&eacute;fono</th>
                                    <th class="pb-3 px-4">Rol Asignado</th>
                                    <th class="pb-3 px-4">Estado (Clic para alternar)</th>
                                    <th class="pb-3 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm font-medium text-slate-600" id="tablaBody">
                                @forelse ($usuarios as $u)
                                @php
                                    $estado = ($u->estado ?? 1) == 1;
                                    $rolId  = $u->id_rol;
                                    $rolNombre = $u->rol->nombre ?? ($rolId == 1 ? 'Administrador' : ($rolId == 2 ? 'Trabajador' : 'Cliente'));
                                @endphp
                                <tr class="hover:bg-sky-50/40 transition-colors usuario-row" data-rol="{{ $rolId }}">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold shadow-sm
                                                {{ $estado ? 'bg-gradient-to-br from-indigo-50 to-sky-100 text-sky-600 border border-slate-100' : 'bg-rose-50 text-rose-500 border border-rose-100' }}">
                                                {{ strtoupper(substr($u->nombres, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800">{{ $u->nombres }}</div>
                                                <div class="text-xs text-slate-400">{{ $u->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div>{{ $u->documento_numero ?? 'Sin documento' }}</div>
                                        <div class="text-xs text-slate-400">{{ $u->telefono ?? 'Sin tel&eacute;fono' }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                            {{ $rolId == 1 ? 'bg-rose-50 text-rose-600 border border-rose-100' : ($rolId == 2 ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-sky-50 text-sky-600 border border-sky-100') }}">
                                            {{ $rolNombre }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <form method="POST" action="{{ route('admin.usuarios.toggleEstado', $u->id_usuario) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                               class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold transition-all shadow-sm border cursor-pointer
                                               {{ $estado ? 'bg-emerald-50 text-emerald-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-300 border-emerald-200' : 'bg-rose-50 text-rose-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 border-rose-200' }}">
                                                <span class="w-2 h-2 rounded-full {{ $estado ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                                <span>{{ $estado ? 'Habilitado' : 'Inhabilitado' }}</span>
                                                <i class="fas {{ $estado ? 'fa-toggle-on text-emerald-600' : 'fa-toggle-off text-rose-400' }} text-sm ml-1"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button type="button" onclick="openEditModal({{ json_encode($u) }})"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-sky-100 hover:text-sky-600 text-slate-500 inline-flex items-center justify-center transition-colors" title="Editar usuario">
                                                <i class="fas fa-pen-to-square text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center text-slate-400">
                                        <i class="fas fa-users-slash text-4xl mb-3 text-slate-300 block"></i>
                                        <p class="font-bold text-base text-slate-600">No se encontraron usuarios</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    {{-- Modal Crear Usuario --}}
    <div id="modalCrear" class="fixed inset-0 z-50 overflow-y-auto hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalCrear')"></div>
        <div class="relative z-10 w-full max-w-2xl glass-card rounded-2xl sm:rounded-[2rem] p-6 sm:p-8 shadow-2xl border border-white/80 animate-fade my-8 overflow-hidden">
            <div class="flex items-center justify-between pb-4 sm:pb-6 border-b border-slate-100 mb-6">
                <h3 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Crear Nuevo Usuario</h3>
                <button onclick="closeModal('modalCrear')" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nombre Completo *</label>
                        <input type="text" name="nombres" required placeholder="Juan P&eacute;rez" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Correo Electr&oacute;nico *</label>
                        <input type="email" name="email" required placeholder="correo@ejemplo.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Contrase&ntilde;a *</label>
                        <input type="password" name="password" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Rol *</label>
                        <select name="id_rol" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                            <option value="">Selecciona rol...</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id_rol }}">{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Direcci&oacute;n</label>
                        <input type="text" name="direccion" placeholder="Calle 10 # 20-30" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Tel&eacute;fono</label>
                        <input type="text" name="telefono" placeholder="3001234567" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('modalCrear')" class="flex-1 py-3 rounded-xl bg-slate-100 font-bold text-slate-600">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl water-gradient text-white font-bold">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Editar Usuario --}}
    <div id="modalEditar" class="fixed inset-0 z-50 overflow-y-auto hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalEditar')"></div>
        <div class="relative z-10 w-full max-w-2xl glass-card rounded-2xl sm:rounded-[2rem] p-6 sm:p-8 shadow-2xl border border-white/80 animate-fade my-8 overflow-hidden">
            <div class="flex items-center justify-between pb-4 sm:pb-6 border-b border-slate-100 mb-6">
                <h3 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Modificar Usuario</h3>
                <button onclick="closeModal('modalEditar')" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="formEditar" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nombre Completo</label>
                        <input type="text" name="nombres" id="edit_nombres" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Rol Asignado</label>
                        <select name="id_rol" id="edit_rol" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                            @foreach ($roles as $r)
                                <option value="{{ $r->id_rol }}">{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Direcci&oacute;n</label>
                        <input type="text" name="direccion" id="edit_direccion" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Tel&eacute;fono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nueva Clave (Opcional)</label>
                        <input type="password" name="password" placeholder="Dejar vac&iacute;o para mantener actual" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm">
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('modalEditar')" class="flex-1 py-3 rounded-xl bg-slate-100 font-bold text-slate-600">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl water-gradient text-white font-bold">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    window.APP = window.APP || {};
    window.APP.rutas = window.APP.rutas || {};
    window.APP.rutas.updateUser = '{{ route('admin.usuarios.update', '__ID__') }}';
</script>
<script src="{{ asset('js/pages/admin.js') }}"></script>
@endpush
