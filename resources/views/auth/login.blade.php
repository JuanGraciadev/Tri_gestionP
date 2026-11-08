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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        trigestion: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#00669e',
                            800: '#005282',
                            900: '#082f49',
                        }
                    },
                    animation: {
                        'fade': 'fadeIn 0.6s ease-out',
                        'slide': 'slideIn 0.5s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'scale(0.98)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        slideIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-input {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
        }

        /* Water gradient */
        .water-gradient {
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
        }

        /* Background Blobs */
        .bg-blobs {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 {
            position: absolute;
            top: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            border-radius: 9999px;
            background: rgba(56, 189, 248, 0.08);
            filter: blur(80px);
        }
        .blob-2 {
            position: absolute;
            bottom: -10%;
            left: 10%;
            width: 450px;
            height: 450px;
            border-radius: 9999px;
            background: rgba(79, 70, 229, 0.05);
            filter: blur(90px);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.05); }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.2); border-radius: 10px; }
    </style>
</head>
<body class="font-sans bg-slate-50 min-h-screen flex items-center justify-center p-3 sm:p-6 relative overflow-hidden">

    <!-- Background Ambience Blobs -->
    <div class="bg-blobs">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-5xl flex items-center justify-center">
        <div class="glass w-full rounded-2xl sm:rounded-[2.5rem] shadow-[0_30px_60px_-15px_rgba(14,165,233,0.15)] overflow-hidden flex flex-col lg:flex-row min-h-0 lg:min-h-[620px] animate-fade border border-white/60">

            <!-- Left Side: Branding / Info -->
            <div class="hidden lg:flex lg:w-1/2 water-gradient p-12 text-white flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-sky-300/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-12">
                        <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner">
                            <i class="fas fa-droplet text-2xl text-white"></i>
                        </div>
                        <span class="text-3xl font-black tracking-tight">TRIGESTION</span>
                    </div>
                    <h2 class="text-4xl xl:text-5xl font-black leading-[1.1] mb-6 tracking-tight">
                        Gestiona la pureza con precisi&oacute;n.
                    </h2>
                    <p class="text-base xl:text-lg text-sky-50 leading-relaxed opacity-90 font-medium">
                        La plataforma definitiva para el control integral de inventario, producci&oacute;n y ventas de agua embotellada.
                    </p>
                </div>

                <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-sky-100 uppercase tracking-widest mb-1">
                            Certificaci&oacute;n
                        </div>
                        <div class="text-sm font-bold tracking-wider">
                            CALIDAD GARANTIZADA
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="w-full lg:w-1/2 p-6 sm:p-10 lg:p-14 flex flex-col justify-center bg-white/60 relative">
                <!-- Back Link -->
                <div class="mb-4">
                    <a href="{{ url('/') }}" class="self-start inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-sky-600 transition-colors group">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        <span>Volver al Inicio (Index)</span>
                    </a>
                </div>

                <div class="mb-6 sm:mb-8 text-center lg:text-left">
                    <!-- Mobile brand -->
                    <div class="lg:hidden flex items-center justify-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-lg shadow-sky-500/30 text-white text-2xl">
                            <i class="fas fa-droplet"></i>
                        </div>
                        <span class="text-2xl sm:text-3xl font-black text-slate-800">TRIGESTION</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-800 mb-2 tracking-tight">
                        &iexcl;Bienvenido!
                    </h1>
                    <p class="text-slate-500 font-medium text-sm sm:text-base">
                        Ingresa tus credenciales para acceder al sistema.
                    </p>
                </div>

                <!-- Session Status Alert -->
                @if (session('status'))
                    <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 text-sm font-semibold flex items-center gap-3 animate-fade">
                        <i class="fas fa-circle-check text-lg shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Global Error Display -->
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-sm font-semibold flex items-center gap-3 animate-fade">
                        <i class="fas fa-circle-exclamation text-lg shrink-0"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
                    @csrf

                    <!-- Correo Electrónico -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">
                            Correo Electr&oacute;nico
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-sky-500 transition-colors">
                                <i class="fas fa-envelope text-base"></i>
                            </span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="tu@correo.com"
                                   required
                                   autofocus
                                   class="glass-input w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 focus:bg-white transition-all text-slate-700 font-medium shadow-sm text-sm sm:text-base">
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center px-1">
                            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest">
                                Contrase&ntilde;a
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700">
                                    &iquest;Olvidaste tu clave?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-sky-500 transition-colors">
                                <i class="fas fa-lock text-base"></i>
                            </span>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                   required
                                   class="glass-input w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 focus:bg-white transition-all text-slate-700 font-medium shadow-sm text-sm sm:text-base">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-2 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   id="remember_me"
                                   name="remember"
                                   class="w-4 h-4 rounded border-slate-300 text-sky-500 focus:ring-sky-500 cursor-pointer">
                            <span class="text-xs text-slate-600 font-medium">Recordarme</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full water-gradient text-white font-bold py-4 rounded-xl sm:rounded-2xl hover:shadow-lg hover:shadow-sky-300 transform active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base">
                            <span>Acceder al Panel</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>

                <!-- Bottom Footer Link -->
                <div class="mt-8 text-center pt-6 border-t border-slate-200/70">
                    <p class="text-slate-500 font-medium text-sm">
                        &iquest;No tienes cuenta de cliente?
                        <a href="{{ route('register') }}" class="text-sky-600 font-bold hover:text-sky-700 hover:underline transition-all ml-1.5">
                            Crear una ahora
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
