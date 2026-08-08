<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Crear Cuenta Nueva - TRIGESTION</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Custom Theme Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        trigestion: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#009ee3',
                            600: '#0081c2',
                            700: '#00669e',
                            800: '#005282',
                            900: '#082f49',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased min-h-screen bg-cover bg-center bg-no-repeat bg-fixed flex items-center justify-center p-4 sm:p-6 lg:p-10"
      style="background-image: linear-gradient(135deg, rgba(14, 165, 233, 0.45) 0%, rgba(2, 132, 199, 0.55) 100%), url('{{ asset("images/water_splash_bg.png") }}');">

    <!-- SUCCESS ANIMATED MODAL OVERLAY -->
    @if (session('success'))
        <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md transition-all duration-300">
            <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center shadow-2xl border border-sky-100 transform transition-all scale-100">
                
                <!-- ANIMATED CHECKMARK ICON -->
                <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner animate-bounce">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <!-- TITLE & MESSAGE -->
                <h3 class="text-2xl font-black text-slate-900 mb-2">¡Registro Exitoso! 🎉</h3>
                <p class="text-slate-600 text-sm font-medium leading-relaxed mb-8">
                    {{ session('success') }}
                </p>

                <!-- ACTION BUTTONS -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('login') }}" 
                       class="w-full bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-trigestion-500/30 transition-all text-sm flex items-center justify-center gap-2">
                        <span>Ir a Iniciar Sesión</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <button onclick="document.getElementById('successModal').remove()" 
                            class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs transition-colors">
                        Cerrar aviso
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- MAIN CONTAINER CARD -->
    <div class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-slate-950/30 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px] my-auto">
        
        <!-- LEFT PANEL (BLUE BANNER) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-trigestion-400 via-trigestion-500 to-trigestion-700 p-8 sm:p-10 lg:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            
            <!-- Subtle Glow Circle Effect -->
            <div class="absolute -top-20 -left-20 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-sky-300/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- BRAND HEADER -->
            <div class="relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group">
                    <img src="{{ asset('img/tr.png') }}" 
                         alt="TRIGESTION Logo" 
                         class="h-7 w-auto object-contain brightness-200 filter"
                         onerror="this.onerror=null; this.src='{{ asset('img/triges.png') }}';">
                    <span class="text-xl font-black tracking-wider uppercase text-white">TRIGESTION</span>
                </a>
            </div>

            <!-- HERO TEXT & BENEFITS -->
            <div class="my-8 lg:my-0 relative z-10">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight mb-4 tracking-tight">
                    Únete a la revolución de la pureza.
                </h1>
                
                <p class="text-sky-100 text-sm sm:text-base font-normal leading-relaxed mb-8">
                    Crea tu cuenta hoy y comienza a disfrutar de la mejor hidratación con la comodidad que mereces.
                </p>

                <!-- CHECKLIST -->
                <ul class="space-y-3.5">
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-white/20 border border-white/30 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-white/95">Pedidos en un solo clic</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-white/20 border border-white/30 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-white/95">Seguimiento en tiempo real</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-white/20 border border-white/30 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-white/95">Historial de consumos</span>
                    </li>
                </ul>
            </div>

            <!-- BOTTOM FOOTER QUOTE -->
            <div class="relative z-10 pt-6 border-t border-white/20">
                <p class="text-xs text-sky-100/90 italic font-medium">
                    "Hidratación inteligente para personas excepcionales."
                </p>
            </div>

        </div>

        <!-- RIGHT PANEL (FORM AREA) -->
        <div class="lg:col-span-7 bg-sky-50/70 p-6 sm:p-8 lg:p-10 flex flex-col justify-between">
            
            <div>
                <!-- BACK LINK -->
                <div class="mb-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-trigestion-500 hover:text-trigestion-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Volver al inicio</span>
                    </a>
                </div>

                <!-- TITLE & SUBTITLE -->
                <div class="mb-6">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Crear Cuenta Nueva
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                        Completa tus datos para empezar tu experiencia con Trigestion.
                    </p>
                </div>

                <!-- ERROR ALERT IF ANY -->
                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600 animate-fade-in">
                        <div class="font-bold mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Por favor corrige los siguientes errores:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                    @csrf

                    <!-- FORM GRID 2 COLUMNS -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        
                        <!-- NOMBRES COMPLETOS -->
                        <div>
                            <label for="nombres" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1">
                                NOMBRES COMPLETOS
                            </label>
                            <div class="relative flex items-center bg-white border border-slate-200/90 rounded-xl shadow-sm focus-within:border-trigestion-500 focus-within:ring-2 focus-within:ring-trigestion-500/20 transition-all">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <input type="text" 
                                       id="nombres" 
                                       name="nombres" 
                                       value="{{ old('nombres', old('name')) }}" 
                                       placeholder="Ej. Juan Pérez" 
                                       required 
                                       autofocus 
                                       class="w-full bg-transparent pl-10 pr-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                            </div>
                        </div>

                        <!-- DIRECCIÓN DE ENTREGA -->
                        <div>
                            <label for="direccion" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1">
                                DIRECCIÓN DE ENTREGA
                            </label>
                            <div class="relative flex items-center bg-white border border-slate-200/90 rounded-xl shadow-sm focus-within:border-trigestion-500 focus-within:ring-2 focus-within:ring-trigestion-500/20 transition-all">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                <input type="text" 
                                       id="direccion" 
                                       name="direccion" 
                                       value="{{ old('direccion') }}" 
                                       placeholder="Ej. Calle 10 #45-67" 
                                       class="w-full bg-transparent pl-10 pr-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                            </div>
                        </div>

                        <!-- CORREO ELECTRÓNICO -->
                        <div>
                            <label for="email" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1">
                                CORREO ELECTRÓNICO
                            </label>
                            <div class="relative flex items-center bg-white border border-slate-200/90 rounded-xl shadow-sm focus-within:border-trigestion-500 focus-within:ring-2 focus-within:ring-trigestion-500/20 transition-all">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="jjgc710@gmail.com" 
                                       required 
                                       class="w-full bg-transparent pl-10 pr-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                            </div>
                        </div>

                        <!-- DOCUMENTO / IDENTIFICACIÓN -->
                        <div>
                            <label for="documento_numero" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1">
                                DOCUMENTO / IDENTIFICACIÓN
                            </label>
                            <div class="relative flex items-center bg-white border border-slate-200/90 rounded-xl shadow-sm focus-within:border-trigestion-500 focus-within:ring-2 focus-within:ring-trigestion-500/20 transition-all">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6"/>
                                    </svg>
                                </span>
                                <input type="text" 
                                       id="documento_numero" 
                                       name="documento_numero" 
                                       value="{{ old('documento_numero') }}" 
                                       placeholder="Número de Cédula/NIT" 
                                       class="w-full bg-transparent pl-10 pr-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                            </div>
                        </div>

                        <!-- TELÉFONO DE CONTACTO (FULL WIDTH IN GRID) -->
                        <div class="sm:col-span-2">
                            <label for="telefono" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1">
                                TELÉFONO DE CONTACTO
                            </label>
                            <div class="relative flex items-center bg-white border border-slate-200/90 rounded-xl shadow-sm focus-within:border-trigestion-500 focus-within:ring-2 focus-within:ring-trigestion-500/20 transition-all">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                                <input type="text" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}" 
                                       placeholder="Ej. 300 123 4567" 
                                       class="w-full bg-transparent pl-10 pr-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                            </div>
                        </div>

                        <!-- CONTRASEÑA -->
                        <div>
                            <label for="password" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1">
                                CONTRASEÑA
                            </label>
                            <div class="relative flex items-center bg-white border border-slate-200/90 rounded-xl shadow-sm focus-within:border-trigestion-500 focus-within:ring-2 focus-within:ring-trigestion-500/20 transition-all">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••" 
                                       required 
                                       class="w-full bg-transparent pl-10 pr-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                            </div>
                        </div>

                        <!-- CONFIRMAR CONTRASEÑA -->
                        <div>
                            <label for="password_confirmation" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1">
                                CONFIRMAR CONTRASEÑA
                            </label>
                            <div class="relative flex items-center bg-white border border-slate-200/90 rounded-xl shadow-sm focus-within:border-trigestion-500 focus-within:ring-2 focus-within:ring-trigestion-500/20 transition-all">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </span>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="••••••••" 
                                       required 
                                       class="w-full bg-transparent pl-10 pr-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                            </div>
                        </div>

                    </div>

                    <!-- TERMS CHECKBOX -->
                    <div class="pt-2">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" 
                                   name="terms" 
                                   required 
                                   class="w-4 h-4 mt-0.5 rounded border-slate-300 text-trigestion-500 focus:ring-trigestion-500 shrink-0 cursor-pointer">
                            <span class="text-[11px] sm:text-xs text-slate-600 font-medium leading-tight">
                                Acepto los <a href="#" class="text-trigestion-500 font-bold hover:underline">términos de servicio</a> y la política de tratamiento de datos personales y comerciales de Trigestion.
                            </span>
                        </label>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-trigestion-500/30 transition-all duration-200 text-sm sm:text-base tracking-wide transform active:scale-[0.99] flex items-center justify-center">
                            Completar Registro
                        </button>
                    </div>
                </form>
            </div>

            <!-- BOTTOM FOOTER LINK -->
            <div class="text-center pt-4 mt-2">
                <p class="text-xs sm:text-sm text-slate-600 font-medium">
                    ¿Ya eres cliente? 
                    <a href="{{ route('login') }}" class="text-trigestion-500 font-bold hover:underline">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>

        </div>

    </div>

</body>
</html>
