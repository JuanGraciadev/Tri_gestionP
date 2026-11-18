@extends('layouts.guest')

@section('title', 'Crear Cuenta')

@section('content')
    <div class="w-full flex flex-col items-center justify-center">

    <!-- Success Modal -->
    @if (session('registro_exitoso'))
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md">
            <div class="relative z-10 w-full max-w-md">
                <div class="glass w-full rounded-2xl sm:rounded-[2.5rem] shadow-2xl p-10 text-center animate-slide border border-white/60">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 text-white text-4xl shadow-lg shadow-emerald-400/30 mb-6">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2">
                        &iexcl;Registro Exitoso! &nbsp;🎉
                    </h2>
                    <p class="text-slate-500 font-medium text-sm mb-8 leading-relaxed">
                        {{ session('registro_exitoso') }}
                    </p>
                    <a href="{{ route('login') }}"
                       class="w-full water-gradient text-white font-bold py-4 rounded-2xl hover:shadow-lg hover:shadow-sky-300 transition-all flex items-center justify-center gap-2 text-base">
                        <i class="fas fa-arrow-right-to-bracket"></i>
                        <span>Ir al Inicio de Sesi&oacute;n</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-4xl flex items-center justify-center">
        <div class="glass w-full rounded-2xl sm:rounded-[2.5rem] shadow-2xl p-6 sm:p-10 md:p-12 animate-fade border border-white/60">

            <!-- Back Link -->
            <div class="mb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-sky-600 transition-colors group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span>Volver al Inicio (Index)</span>
                </a>
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 text-white text-2xl shadow-lg shadow-sky-500/30 mb-4">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">
                    Crear Cuenta de Cliente
                </h1>
                <p class="text-slate-500 text-sm font-medium mt-1">
                    Reg&iacute;strate para realizar pedidos de agua purificada y monitorear tus compras.
                </p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-sm font-semibold flex items-center gap-3 animate-fade">
                    <i class="fas fa-circle-exclamation text-lg shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nombres -->
                    <div>
                        <label for="nombres" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">
                            Nombre Completo *
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text"
                                   id="nombres"
                                   name="nombres"
                                   value="{{ old('nombres') }}"
                                   placeholder="Juan P&eacute;rez"
                                   required
                                   autofocus
                                   class="glass-input w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-sky-500 font-medium text-sm text-slate-700">
                        </div>
                    </div>

                    <!-- Correo Electrónico -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">
                            Correo Electr&oacute;nico *
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="juan@correo.com"
                                   required
                                   class="glass-input w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-sky-500 font-medium text-sm text-slate-700">
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">
                            Contrase&ntilde;a *
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="M&iacute;nimo 4 caracteres"
                                   required
                                   class="glass-input w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-sky-500 font-medium text-sm text-slate-700">
                        </div>
                    </div>

                    <!-- Cédula / Documento -->
                    <div>
                        <label for="documento_numero" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">
                            C&eacute;dula / Documento
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text"
                                   id="documento_numero"
                                   name="documento_numero"
                                   value="{{ old('documento_numero') }}"
                                   placeholder="12345678"
                                   class="glass-input w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-sky-500 font-medium text-sm text-slate-700">
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">
                            Tel&eacute;fono / M&oacute;vil
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text"
                                   id="telefono"
                                   name="telefono"
                                   value="{{ old('telefono') }}"
                                   placeholder="3001234567"
                                   class="glass-input w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-sky-500 font-medium text-sm text-slate-700">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="direccion" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">
                            Direcci&oacute;n de Entrega
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-location-dot"></i>
                            </span>
                            <input type="text"
                                   id="direccion"
                                   name="direccion"
                                   value="{{ old('direccion') }}"
                                   placeholder="Calle 10 # 20 - 30"
                                   class="glass-input w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-sky-500 font-medium text-sm text-slate-700">
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="sm:col-span-2">
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">
                            Confirmar Contrase&ntilde;a *
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-shield-halved"></i>
                            </span>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                   required
                                   class="glass-input w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-sky-500 font-medium text-sm text-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="pt-2">
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input type="checkbox"
                               name="terms"
                               required
                               class="w-4 h-4 mt-0.5 rounded border-slate-300 text-sky-500 focus:ring-sky-500 shrink-0 cursor-pointer">
                        <span class="text-[11px] sm:text-xs text-slate-600 font-medium leading-tight">
                            Acepto los <a href="#" class="text-sky-600 font-bold hover:underline">t&eacute;rminos de servicio</a> y la pol&iacute;tica de tratamiento de datos personales de Trigestion.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                            class="w-full water-gradient text-white font-bold py-4 rounded-xl sm:rounded-2xl hover:shadow-lg hover:shadow-sky-300 transition-all flex items-center justify-center gap-2 text-base">
                        <i class="fas fa-check"></i>
                        <span>Registrarme Ahora</span>
                    </button>
                </div>
            </form>

            <!-- Bottom Footer Link -->
            <div class="mt-6 text-center pt-6 border-t border-slate-200">
                <p class="text-slate-500 font-medium text-sm">
                    &iquest;Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-sky-600 font-bold hover:text-sky-700 hover:underline transition-all ml-1.5">
                        Inicia sesi&oacute;n aqu&iacute;
                    </a>
                </p>
            </div>

        </div>
    </div>
    </div>
@endsection
