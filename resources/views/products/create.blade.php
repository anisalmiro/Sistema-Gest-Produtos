@extends('layouts.app')

@section('title', 'Criar Produto')
@section('subtitle', 'Adicionar um novo produto ao sistema')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Informações do Produto</h3>
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-lg text-xl"></i>
                </a>
            </div>
        </div>

        <form action="{{ route('products.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome do Produto -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Produto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Digite o nome do produto"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoria -->
                <div class="md:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoria
                    </label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="loadCategorySpecs()">
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Descrição detalhada do produto">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Especificações Dinâmicas -->
            <div id="dynamic-specs" class="mt-8" style="display: none;">
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="bi bi-gear mr-2"></i>Especificações Técnicas
                    </h4>
                    <div id="specs-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Especificações serão carregadas dinamicamente -->
                    </div>
                </div>
            </div>

            <!-- Especificações Manuais (Compatibilidade) -->
            <div class="mt-8">
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="bi bi-plus-circle mr-2"></i>Especificações Adicionais
                    </h4>
                    <div id="manual-specs">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <input type="text" 
                                   name="specifications[0][key]" 
                                   placeholder="Nome da especificação"
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <input type="text" 
                                   name="specifications[0][value]" 
                                   placeholder="Valor"
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <button type="button" 
                            onclick="addSpecification()" 
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="bi bi-plus mr-1"></i>Adicionar Especificação
                    </button>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('products.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all transform hover:-translate-y-0.5">
                    <i class="bi bi-check mr-2"></i>Criar Produto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let specCount = 1;

function addSpecification() {
    const container = document.getElementById('manual-specs');
    const newSpec = document.createElement('div');
    newSpec.className = 'grid grid-cols-1 md:grid-cols-2 gap-4 mb-4';
    newSpec.innerHTML = `
        <input type="text" 
               name="specifications[${specCount}][key]" 
               placeholder="Nome da especificação"
               class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <div class="flex">
            <input type="text" 
                   name="specifications[${specCount}][value]" 
                   placeholder="Valor"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="button" 
                    onclick="this.parentElement.parentElement.remove()" 
                    class="px-3 py-2 bg-red-500 text-white rounded-r-lg hover:bg-red-600">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newSpec);
    specCount++;
}

function loadCategorySpecs() {
    const categoryId = document.getElementById('category_id').value;
    const dynamicSpecs = document.getElementById('dynamic-specs');
    const specsContainer = document.getElementById('specs-container');
    
    if (!categoryId) {
        dynamicSpecs.style.display = 'none';
        return;
    }
    
    fetch(`{{ route('api.category-specs') }}?category_id=${categoryId}`)
        .then(response => response.json())
        .then(specs => {
            specsContainer.innerHTML = '';
            
            if (specs.length > 0) {
                dynamicSpecs.style.display = 'block';
                
                specs.forEach(spec => {
                    const fieldDiv = document.createElement('div');
                    fieldDiv.className = spec.field_type === 'textarea' ? 'md:col-span-2' : '';
                    
                    let fieldHtml = `
                        <label for="spec_${spec.field_name}" class="block text-sm font-medium text-gray-700 mb-2">
                            ${spec.field_label}
                            ${spec.required ? '<span class="text-red-500">*</span>' : ''}
                            ${spec.unit ? `<span class="text-gray-500 text-xs">(${spec.unit})</span>` : ''}
                        </label>
                    `;
                    
                    if (spec.field_type === 'select') {
                        fieldHtml += `<select id="spec_${spec.field_name}" name="spec_${spec.field_name}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" ${spec.required ? 'required' : ''}>`;
                        fieldHtml += '<option value="">Selecione uma opção</option>';
                        spec.field_options.forEach(option => {
                            fieldHtml += `<option value="${option}">${option}</option>`;
                        });
                        fieldHtml += '</select>';
                    } else if (spec.field_type === 'textarea') {
                        fieldHtml += `<textarea id="spec_${spec.field_name}" name="spec_${spec.field_name}" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" ${spec.required ? 'required' : ''}></textarea>`;
                    } else if (spec.field_type === 'boolean') {
                        fieldHtml += `
                            <div class="flex items-center">
                                <input type="checkbox" id="spec_${spec.field_name}" name="spec_${spec.field_name}" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="spec_${spec.field_name}" class="ml-2 text-sm text-gray-700">Sim</label>
                            </div>
                        `;
                    } else {
                        const inputType = spec.field_type === 'number' ? 'number' : 'text';
                        fieldHtml += `<input type="${inputType}" id="spec_${spec.field_name}" name="spec_${spec.field_name}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" ${spec.required ? 'required' : ''}>`;
                    }
                    
                    if (spec.description) {
                        fieldHtml += `<p class="mt-1 text-xs text-gray-500">${spec.description}</p>`;
                    }
                    
                    fieldDiv.innerHTML = fieldHtml;
                    specsContainer.appendChild(fieldDiv);
                });
            } else {
                dynamicSpecs.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar especificações:', error);
            dynamicSpecs.style.display = 'none';
        });
}

// Carregar especificações se uma categoria já estiver selecionada
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('category_id').value) {
        loadCategorySpecs();
    }
});
</script>
@endpush

