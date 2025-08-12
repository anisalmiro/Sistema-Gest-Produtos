@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-pencil"></i> Editar Produto</h1>
    <div>
        <a href="{{ route('products.show', $product) }}" class="btn btn-info me-2">
            <i class="bi bi-eye"></i> Visualizar
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informações do Produto</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Categoria</label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" 
                               id="category" name="category" value="{{ old('category', $product->category) }}" 
                               placeholder="Ex: Equipamentos, Mobiliário, Laboratório">
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Descrição detalhada do produto">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Especificações Técnicas</label>
                        <div id="specifications-container">
                            @if($product->technical_specifications && count($product->technical_specifications) > 0)
                                @foreach($product->technical_specifications as $index => $spec)
                                    @if(isset($spec['key']) && isset($spec['value']) && $spec['key'] && $spec['value'])
                                        <div class="specification-row mb-2">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="technical_specifications[{{ $index }}][key]" 
                                                           placeholder="Especificação" value="{{ old('technical_specifications.'.$index.'.key', $spec['key']) }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="technical_specifications[{{ $index }}][value]" 
                                                           placeholder="Valor" value="{{ old('technical_specifications.'.$index.'.value', $spec['value']) }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-danger remove-specification">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="specification-row mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="technical_specifications[0][key]" 
                                                   placeholder="Especificação" value="{{ old('technical_specifications.0.key') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="technical_specifications[0][value]" 
                                                   placeholder="Valor" value="{{ old('technical_specifications.0.value') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger remove-specification" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-specification">
                            <i class="bi bi-plus"></i> Adicionar Especificação
                        </button>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Atualizar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Informações</h6>
            </div>
            <div class="card-body">
                <p><strong>Criado em:</strong><br>{{ $product->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Última atualização:</strong><br>{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>Ofertas:</strong><br>{{ $product->offers->count() }} ofertas registadas</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Dicas</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <small>Use nomes descritivos para facilitar a busca</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <small>Categorize os produtos para melhor organização</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <small>Mantenha as especificações técnicas atualizadas</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let specificationIndex = {{ $product->technical_specifications ? count($product->technical_specifications) : 1 }};
    
    document.getElementById('add-specification').addEventListener('click', function() {
        const container = document.getElementById('specifications-container');
        const newRow = document.createElement('div');
        newRow.className = 'specification-row mb-2';
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="technical_specifications[${specificationIndex}][key]" 
                           placeholder="Especificação">
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="technical_specifications[${specificationIndex}][value]" 
                           placeholder="Valor">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-specification">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        specificationIndex++;
        updateRemoveButtons();
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-specification')) {
            e.target.closest('.specification-row').remove();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.specification-row');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('.remove-specification');
            removeBtn.disabled = rows.length === 1;
        });
    }
    
    // Initialize remove buttons state
    updateRemoveButtons();
});
</script>
@endpush

