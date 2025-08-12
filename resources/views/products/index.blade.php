@extends('layouts.app')

@section('title', 'Produtos')
@section('subtitle', 'Gerir produtos e especificações técnicas')

@section('content')
<div class="space-y-6">
    <!-- Header com botão de criar -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Lista de Produtos</h3>
            <p class="text-sm text-gray-600">{{ $products->total() }} produto(s) encontrado(s)</p>
        </div>
        <a href="{{ route('products.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus mr-2"></i>
            Novo Produto
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <input type="text" 
                       placeholder="Pesquisar produtos..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas as categorias</option>
                <!-- Categorias serão carregadas dinamicamente -->
            </select>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="bi bi-funnel mr-2"></i>Filtrar
            </button>
        </div>
    </div>

    <!-- Lista de produtos -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 card-hover">
                    <div class="p-6">
                        <!-- Header do card -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $product->name }}</h4>
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="{{ $product->category->icon ?? 'bi-tag' }} mr-1"></i>
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="bi bi-tag mr-1"></i>
                                        {{ $product->category ?? 'Sem categoria' }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="p-1 text-gray-400 hover:text-blue-600 rounded">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="p-1 text-gray-400 hover:text-green-600 rounded">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja eliminar este produto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-600 rounded">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Descrição -->
                        @if($product->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $product->description }}</p>
                        @endif

                        <!-- Especificações -->
                        @php
                            $specs = $product->getFormattedSpecifications();
                        @endphp
                        
                        @if(count($specs) > 0)
                            <div class="space-y-2">
                                <h5 class="text-sm font-medium text-gray-700">Especificações:</h5>
                                <div class="space-y-1">
                                    @foreach(array_slice($specs, 0, 3) as $key => $value)
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-500">{{ $key }}:</span>
                                            <span class="text-gray-700 font-medium">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                    @if(count($specs) > 3)
                                        <p class="text-xs text-gray-400">+{{ count($specs) - 3 }} mais...</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Footer do card -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    Criado em {{ $product->created_at->format('d/m/Y') }}
                                </span>
                                <div class="flex items-center space-x-2">
                                    @if($product->offers_count > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-currency-euro mr-1"></i>
                                            {{ $product->offers_count }} oferta(s)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <!-- Estado vazio -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-box-seam text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
            <p class="text-gray-600 mb-6">Comece criando o seu primeiro produto no sistema.</p>
            <a href="{{ route('products.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all">
                <i class="bi bi-plus mr-2"></i>
                Criar Primeiro Produto
            </a>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection

