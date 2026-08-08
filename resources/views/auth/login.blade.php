<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar Sesión - TRIGESTION</title>

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

    <!-- MAIN CONTAINER CARD -->
    <div class="w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl shadow-slate-950/30 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[560px] my-auto">
        
        <!-- LEFT PANEL (BLUE BANNER) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-trigestion-400 via-trigestion-500 to-trigestion-700 p-8 sm:p-10 lg:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            
            <!-- Glow Effect Orbs -->
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

            <!-- HERO TEXT & FEATURES -->
            <div class="my-8 lg:my-0 relative z-10">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight mb-4 tracking-tight">
                    Bienvenido de nuevo.
                </h1>
                
                <p class="text-sky-100 text-sm sm:text-base font-normal leading-relaxed mb-8">
                    Accede a tu panel para gestionar tus pedidos y consultar tu historial de consumo de agua purificada.
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
                        <span class="text-sm font-semibold text-white/95">Despacho en tiempo real</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-white/20 border border-white/30 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-white/95">Acceso 100% seguro</span>
                    </li>
                </ul>
            </div>

            <!-- BOTTOM FOOTER QUOTE -->
            <div class="relative z-10 pt-6 border-t border-white/20">
                <p class="text-xs text-sky-100/90 italic font-medium">
                    "La hidratación perfecta a un clic de distancia."
                </p>
            </div>

        </div>

        <!-- RIGHT PANEL (FORM AREA) -->
        <div class="lg:col-span-7 bg-sky-50/70 p-6 sm:p-8 lg:p-12 flex flex-col justify-between">
            
            <div>
                <!-- BACK LINK -->
                <div class="mb-6">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-trigestion-500 hover:text-trigestion-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Volver al inicio</span>
                    </a>
                </div>

                <!-- TITLE & SUBTITLE -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Iniciar Sesión
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                        Ingresa tus credenciales para ingresar a tu cuenta.
                    </p>
                </div>

                <!-- SESSION STATUS ALERT -->
                @if (session('status'))
                    <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-600 font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- GLOBAL ERROR DISPLAY -->
                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600">
                        <div class="font-bold mb-1">Por favor corrige los siguientes errores:</div>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- CORREO ELECTRÓNICO -->
                    <div>
                        <label for="email" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1.5">
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
                                   placeholder="tu.correo@ejemplo.com" 
                                   required 
                                   autofocus 
                                   class="w-full bg-transparent pl-10 pr-3.5 py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                        </div>
                    </div>

                    <!-- CONTRASEÑA -->
                    <div>
                        <label for="password" class="block text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-1.5">
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
                                   class="w-full bg-transparent pl-10 pr-3.5 py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none rounded-xl">
                        </div>
                    </div>

                    <!-- REMEMBER ME & FORGOT PASSWORD ROW -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                   id="remember_me" 
                                   name="remember" 
                                   class="w-4 h-4 rounded border-slate-300 text-trigestion-500 focus:ring-trigestion-500 cursor-pointer">
                            <span class="text-xs text-slate-600 font-medium">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-trigestion-500 hover:text-trigestion-700 font-bold transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="pt-3">
                        <button type="submit" 
                                class="w-full bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-trigestion-500/30 transition-all duration-200 text-sm sm:text-base tracking-wide transform active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Iniciar Sesión</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- BOTTOM FOOTER LINK -->
            <div class="text-center pt-6 mt-2">
                <p class="text-xs sm:text-sm text-slate-600 font-medium">
                    ¿Aún no tienes cuenta? 
                    <a href="{{ route('register') }}" class="text-trigestion-500 font-bold hover:underline">
                        Regístrate aquí
                    </a>
                </p>
            </div>

        </div>

    </div>

</body>
</html>
