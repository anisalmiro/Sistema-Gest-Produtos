<!DOCTYPE html>
<html lang="pt" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        
        .card-hover {
            transition: all 0.2s ease-in-out;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.2s ease-in-out;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .nav-link-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
        }
        
        .nav-link:hover {
            background-color: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg sidebar-transition" id="sidebar">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600">
                    <div class="flex items-center">
                        <i class="bi bi-boxes text-white text-2xl mr-2"></i>
                        <h1 class="text-white text-lg font-bold">Sistema de Gestão</h1>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                        <i class="bi bi-speedometer2 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">
                        <i class="bi bi-box-seam mr-3"></i>
                        Produtos
                    </a>
                    
                    <a href="{{ route('suppliers.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg nav-link {{ request()->routeIs('suppliers.*') ? 'nav-link-active' : '' }}">
                        <i class="bi bi-building mr-3"></i>
                        Fornecedores
                    </a>
                    
                    <a href="{{ route('offers.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg nav-link {{ request()->routeIs('offers.*') ? 'nav-link-active' : '' }}">
                        <i class="bi bi-currency-euro mr-3"></i>
                        Ofertas
                    </a>
                    
                    <a href="{{ route('offer-comparisons.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg nav-link {{ request()->routeIs('offer-comparisons.*') ? 'nav-link-active' : '' }}">
                        <i class="bi bi-graph-up mr-3"></i>
                        Comparações
                    </a>
                </nav>
                
                <!-- User Menu -->
                <div class="px-4 py-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="bi bi-person mr-2"></i>Perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="bi bi-box-arrow-right mr-2"></i>Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="ml-64">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                            <p class="text-sm text-gray-600">@yield('subtitle', 'Bem-vindo ao sistema de gestão de produtos')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i class="bi bi-bell text-lg"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i class="bi bi-gear text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                        <i class="bi bi-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                        <i class="bi bi-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="bi bi-exclamation-triangle mr-2"></i>
                            <strong>Existem erros no formulário:</strong>
                        </div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Add any custom JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide flash messages after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                });
            }, 5000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>

