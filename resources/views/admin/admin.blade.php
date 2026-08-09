<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Administración - TRIGESTION</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:   ['"Plus Jakarta Sans"', 'sans-serif'],
                        outfit: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        trigestion: {
                            50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',
                            400:'#38bdf8',500:'#009ee3',600:'#0081c2',700:'#00669e',
                            800:'#005282',900:'#082f49',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family:'Outfit','Plus Jakarta Sans',sans-serif; }
        .bg-blobs { position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:#f8fafc; }
        .blob-1,.blob-2,.blob-3 { position:absolute;filter:blur(80px);border-radius:50%;opacity:.45;animation:float 20s infinite alternate ease-in-out; }
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(99,102,241,.15) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(168,85,247,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(14,165,233,.08) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }
        .glass-card,.glass-panel { background:rgba(255,255,255,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.55);box-shadow:0 10px 30px -10px rgba(0,0,0,.06); }
        .premium-gradient { background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); }
        .premium-gradient-text { background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent; }
        ::-webkit-scrollbar { width:8px;height:8px; }
        ::-webkit-scrollbar-track { background:#f1f5f9; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:4px; }
        ::-webkit-scrollbar-thumb:hover { background:#94a3b8; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">

    <div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div><div class="blob-3"></div></div>

    <div class="flex min-h-screen relative z-10">

        <!-- ═══ SIDEBAR ADMIN (Coincide con sidebar.php original para Rol 1) ═══ -->
        @include('partials.sidebar')

        <!-- ═══ MAIN ═══ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Panel de Administración</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Gestión de usuarios y control total del sistema</p>
                </div>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-trigestion-500 text-white flex items-center justify-center font-black text-base shadow-md shrink-0">
                            {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <p class="text-xs font-extrabold text-slate-900 hidden sm:block truncate max-w-[160px]">{{ Auth::user()->nombres ?? Auth::user()->name }}</p>
                        <svg class="w-4 h-4 text-slate-400 shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50" style="display:none">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-red-600 bg-red-50/60 hover:bg-red-100/80 border border-red-200/50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- BODY (Replicando la vista admin.php original) -->
            <main class="p-8 space-y-8 flex-1 font-outfit">

                <!-- SweetAlert Flash Alert -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon:  '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text:  '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText:  '<i class="fas fa-check mr-2"></i>Entendido',
                            customClass: { popup: 'rounded-[2rem] font-outfit' }
                        });
                    });
                </script>
                @endif

                @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside text-xs font-semibold space-y-1">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <!-- HEADER & BOTÓN NUEVO REGISTRO -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6">
                    <div>
                        <div class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold text-sm mb-3 shadow-sm">
                            <i class="fas fa-shield-halved mr-2"></i>Control Total
                        </div>
                        <h1 class="text-3xl lg:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                            Gestión de <span class="premium-gradient-text">Usuarios</span>
                        </h1>
                        <p class="text-slate-500 mt-2 font-medium">Administra accesos, roles y permisos de todos los integrantes del sistema en tiempo real.</p>
                    </div>
                    <button onclick="openModal('modalCrear')" class="premium-gradient text-white px-8 py-5 rounded-[1.5rem] font-bold shadow-[0_10px_40px_rgba(79,70,229,0.4)] transition-all transform hover:-translate-y-1 flex items-center gap-3 cursor-pointer w-full sm:w-auto justify-center">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 text-white text-xl">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="text-left">
                            <div class="text-xs text-white/80 uppercase tracking-wider font-bold">Nuevo Registro</div>
                            <div class="text-lg">Añadir Usuario</div>
                        </div>
                    </button>
                </div>

                <!-- KPI METRICS -->
                @php
                    $totalUsuarios    = count($usuarios);
                    $usuariosActivos  = count($usuarios->where('estado', 1));
                    $totalAdmins      = count($usuarios->where('id_rol', 1));
                    $totalClientes    = count($usuarios->where('id_rol', 3));
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="glass-card rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-4 font-black"><i class="fas fa-users"></i></div>
                        <div class="text-4xl font-black text-slate-800">{{ $totalUsuarios }}</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Usuarios Totales</div>
                    </div>
                    <div class="glass-card rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 font-black"><i class="fas fa-user-check"></i></div>
                        <div class="text-4xl font-black text-slate-800">{{ $usuariosActivos }}</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Cuentas Activas</div>
                    </div>
                    <div class="glass-card rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl mb-4 font-black"><i class="fas fa-user-shield"></i></div>
                        <div class="text-4xl font-black text-slate-800">{{ $totalAdmins }}</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Administradores</div>
                    </div>
                    <div class="glass-card rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mb-4 font-black"><i class="fas fa-user-tag"></i></div>
                        <div class="text-4xl font-black text-slate-800">{{ $totalClientes }}</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Clientes Registrados</div>
                    </div>
                </div>

                <!-- TABLE TOOLBAR + BODY -->
                <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xl font-bold shadow-md">
                                <i class="fas fa-users-gear"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-800">Directorio de <span class="premium-gradient-text">Usuarios</span></h3>
                                <p class="text-xs text-slate-400 font-semibold">Gestión completa de accesos, roles y permisos</p>
                            </div>
                        </div>

                        <!-- Search & Role filters -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200">
                                <button onclick="filtrarUsuarios('todos')" class="filter-usr active px-4 py-2 rounded-xl font-bold text-xs bg-white text-indigo-600 shadow-sm" data-filter="todos">Todos</button>
                                <button onclick="filtrarUsuarios('1')" class="filter-usr px-4 py-2 rounded-xl font-bold text-xs text-slate-500 hover:text-indigo-600" data-filter="1">Admin</button>
                                <button onclick="filtrarUsuarios('2')" class="filter-usr px-4 py-2 rounded-xl font-bold text-xs text-slate-500 hover:text-indigo-600" data-filter="2">Trabajador</button>
                                <button onclick="filtrarUsuarios('3')" class="filter-usr px-4 py-2 rounded-xl font-bold text-xs text-slate-500 hover:text-indigo-600" data-filter="3">Cliente</button>
                            </div>
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" id="searchInput" onkeyup="filtrarSearch()" placeholder="Buscar usuario..."
                                       class="pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none text-sm font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- USER TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="tablaUsuarios">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                <tr>
                                    <th class="px-8 py-5">Perfil</th>
                                    <th class="px-8 py-5">Contacto</th>
                                    <th class="px-8 py-5">Dirección</th>
                                    <th class="px-8 py-5 text-center">Rol & Estado</th>
                                    <th class="px-8 py-5 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="tablaBody">
                                @forelse ($usuarios as $u)
                                @php
                                    $estado = ($u->estado ?? 1) == 1;
                                    $rolId  = $u->id_rol;
                                    $rolNombre = $u->rol->nombre ?? ($rolId == 1 ? 'Administrador' : ($rolId == 2 ? 'Trabajador' : 'Cliente'));
                                    $rolBadge = match($rolId) {
                                        1 => 'bg-purple-100 text-purple-700 border-purple-200',
                                        2 => 'bg-sky-100 text-sky-700 border-sky-200',
                                        3 => 'bg-amber-100 text-amber-700 border-amber-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                    $avatarGrad = match($rolId) {
                                        1 => 'from-purple-400 to-violet-500',
                                        2 => 'from-sky-400 to-blue-500',
                                        3 => 'from-amber-400 to-orange-500',
                                        default => 'from-slate-400 to-slate-500',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors group usuario-row" data-rol="{{ $rolId }}">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-[1rem] bg-gradient-to-br {{ $avatarGrad }} text-white flex items-center justify-center font-black text-xl shadow-md">
                                                {{ strtoupper(substr($u->nombres, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-800 text-base tracking-tight">{{ $u->nombres }}</div>
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">#{{ str_pad($u->id_usuario, 4, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-semibold text-slate-700">{{ $u->email }}</div>
                                        <div class="text-xs text-slate-400 font-medium">{{ $u->telefono ?? 'Sin teléfono' }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm text-slate-600 font-medium truncate max-w-[200px]">{{ $u->direccion }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $rolBadge }}">
                                                {{ $rolNombre }}
                                            </span>
                                            @if ($estado)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                                            </span>
                                            @else
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400">
                                                <i class="fas fa-ban text-[10px]"></i> Suspendido
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Editar -->
                                            <button type="button" onclick="openEditModal({{ json_encode($u) }})"
                                                    class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-all flex items-center justify-center border border-indigo-100 cursor-pointer" title="Editar">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <!-- Toggle Estado -->
                                            <a href="{{ route('admin.usuarios.toggleEstado', $u->id_usuario) }}"
                                               class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-700 hover:text-white transition-all flex items-center justify-center border border-slate-200"
                                               title="{{ $estado ? 'Suspender cuenta' : 'Activar cuenta' }}">
                                                <i class="fas {{ $estado ? 'fa-user-lock' : 'fa-user-check' }}"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 font-medium">
                                        No hay usuarios registrados.
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

    <!-- ══ Modal Crear Usuario ══ -->
    <div id="modalCrear" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="glass-card rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl border border-white">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                <div>
                    <h3 class="text-2xl font-bold">Nuevo Usuario</h3>
                    <p class="text-xs text-white/70 mt-0.5">Registra un nuevo integrante en el sistema</p>
                </div>
                <button onclick="closeModal('modalCrear')" class="w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white hover:text-red-500 transition-all border border-white/30 cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.usuarios.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Nombres Completos</label>
                        <input type="text" name="nombres" required placeholder="Ej. Juan Pérez" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Dirección</label>
                        <input type="text" name="direccion" required placeholder="Calle, ciudad..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase">Correo Electrónico</label>
                    <input type="email" name="email" required placeholder="correo@ejemplo.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700">
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Contraseña</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Rol Asignado</label>
                        <select name="id_rol" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700 cursor-pointer">
                            <option value="">Seleccione rol...</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id_rol }}">{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalCrear')" class="px-6 py-3 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="premium-gradient text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-200 transition-all cursor-pointer">
                        <i class="fas fa-user-plus mr-1"></i> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══ Modal Editar Usuario ══ -->
    <div id="modalEditar" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="glass-card rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl border border-white">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-800 text-white">
                <div>
                    <h3 class="text-2xl font-bold">Editar Perfil</h3>
                    <p class="text-xs text-slate-300 mt-0.5">Modifica la información del usuario</p>
                </div>
                <button onclick="closeModal('modalEditar')" class="w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white hover:text-red-500 transition-all border border-white/20 cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="formEditar" method="POST" class="p-8 space-y-5">
                @csrf @method('PUT')
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Nombres Completos</label>
                        <input type="text" name="nombres" id="edit_nombres" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Dirección</label>
                        <input type="text" name="direccion" id="edit_direccion" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase">Correo Electrónico (Solo Lectura)</label>
                    <input type="email" id="edit_email" readonly class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-2xl text-slate-400 font-medium outline-none cursor-not-allowed">
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Rol Asignado</label>
                        <select name="id_rol" id="edit_rol" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-indigo-500 font-medium text-slate-700 cursor-pointer">
                            @foreach ($roles as $r)
                                <option value="{{ $r->id_rol }}">{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalEditar')" class="px-6 py-3 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="premium-gradient text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-200 transition-all cursor-pointer">
                        <i class="fas fa-save mr-1"></i> Actualizar Datos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

        function openEditModal(u) {
            const url = "{{ route('admin.usuarios.update', ':id') }}".replace(':id', u.id_usuario);
            document.getElementById('formEditar').action = url;
            document.getElementById('edit_nombres').value   = u.nombres;
            document.getElementById('edit_direccion').value = u.direccion;
            document.getElementById('edit_email').value     = u.email;
            document.getElementById('edit_rol').value       = u.id_rol;
            openModal('modalEditar');
        }

        let currentRolFilter = 'todos';

        function filtrarUsuarios(rol) {
            currentRolFilter = rol;
            document.querySelectorAll('.filter-usr').forEach(btn => {
                if (btn.dataset.filter === rol) {
                    btn.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
                    btn.classList.remove('text-slate-500');
                } else {
                    btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                    btn.classList.add('text-slate-500');
                }
            });
            filtrarTabla();
        }

        function filtrarSearch() {
            filtrarTabla();
        }

        function filtrarTabla() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const rows   = document.querySelectorAll('.usuario-row');

            rows.forEach(row => {
                const matchesRol    = (currentRolFilter === 'todos' || row.dataset.rol === currentRolFilter);
                const matchesSearch = row.innerText.toLowerCase().includes(search);
                if (matchesRol && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        window.addEventListener('click', function(e) {
            ['modalCrear','modalEditar'].forEach(id => {
                if (e.target.id === id) closeModal(id);
            });
        });
    </script>
</body>
</html>
