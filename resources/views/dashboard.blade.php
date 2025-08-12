@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Visão geral do sistema de gestão de produtos')

@section('content')
<div class="space-y-6">
    <!-- Cartões de estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Produtos -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-box-seam text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Produtos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Fornecedores -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-building text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Fornecedores</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_suppliers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Ofertas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-currency-euro text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Ofertas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_offers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Categorias -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-tags text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Categorias Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e estatísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Produtos por Categoria -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Produtos por Categoria</h3>
                <i class="bi bi-pie-chart text-gray-400"></i>
            </div>
            
            @if($productsByCategory->count() > 0)
                <div class="space-y-3">
                    @foreach($productsByCategory as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="{{ $category['icon'] ?? 'bi-tag' }} text-blue-600 mr-2"></i>
                                <span class="text-sm text-gray-700">{{ $category['name'] }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_products'] > 0 ? ($category['count'] / $stats['total_products']) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $category['count'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="bi bi-pie-chart text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">Nenhum produto encontrado</p>
                </div>
            @endif
        </div>

        <!-- Ofertas por Mês -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Ofertas por Mês</h3>
                <i class="bi bi-graph-up text-gray-400"></i>
            </div>
            
            @if($offersPerMonth->count() > 0)
                <div class="space-y-3">
                    @foreach($offersPerMonth as $month)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">{{ \Carbon\Carbon::createFromFormat('Y-m', $month['month'])->format('M Y') }}</span>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $offersPerMonth->max('count') > 0 ? ($month['count'] / $offersPerMonth->max('count')) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $month['count'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="bi bi-graph-up text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">Nenhuma oferta encontrada</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Listas de atividades recentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Produtos Recentes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Produtos Recentes</h3>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
            
            @if($recentProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentProducts as $product)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="{{ $product->category->icon ?? 'bi-box' }} text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Sem categoria' }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $product->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="bi bi-box-seam text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">Nenhum produto recente</p>
                    <a href="{{ route('products.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                        Criar primeiro produto
                    </a>
                </div>
            @endif
        </div>

        <!-- Ofertas Recentes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Ofertas Recentes</h3>
                <a href="{{ route('offers.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todas <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
            
            @if($recentOffers->count() > 0)
                <div class="space-y-3">
                    @foreach($recentOffers as $offer)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="bi bi-currency-euro text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $offer->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $offer->supplier->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">€{{ number_format($offer->price, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $offer->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="bi bi-currency-euro text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">Nenhuma oferta recente</p>
                    <a href="{{ route('offers.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                        Criar primeira oferta
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Fornecedores -->
    @if($topSuppliers->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Top Fornecedores</h3>
                <a href="{{ route('suppliers.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($topSuppliers as $supplier)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="bi bi-building text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $supplier->name }}</p>
                            <p class="text-xs text-gray-500">{{ $supplier->offers_count }} oferta(s)</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Ações Rápidas -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Ações Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('products.create') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                <i class="bi bi-plus-circle text-blue-600 text-xl mr-3"></i>
                <span class="text-blue-700 font-medium">Novo Produto</span>
            </a>
            
            <a href="{{ route('suppliers.create') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <i class="bi bi-building-add text-green-600 text-xl mr-3"></i>
                <span class="text-green-700 font-medium">Novo Fornecedor</span>
            </a>
            
            <a href="{{ route('offers.create') }}" class="flex items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                <i class="bi bi-currency-euro text-yellow-600 text-xl mr-3"></i>
                <span class="text-yellow-700 font-medium">Nova Oferta</span>
            </a>
            
            <a href="{{ route('offer-comparisons.index') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                <i class="bi bi-graph-up text-purple-600 text-xl mr-3"></i>
                <span class="text-purple-700 font-medium">Comparar Ofertas</span>
            </a>
        </div>
    </div>
</div>
@endsection

