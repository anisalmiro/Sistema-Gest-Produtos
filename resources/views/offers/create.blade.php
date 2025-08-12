@extends('layouts.app')

@section('title', 'Criar Oferta')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-plus-circle"></i> Criar Oferta</h1>
    <a href="{{ route('offers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informações da Oferta</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('offers.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Produto <span class="text-danger">*</span></label>
                                <select class="form-select @error('product_id') is-invalid @enderror" 
                                        id="product_id" name="product_id" required>
                                    <option value="">Selecione um produto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                            @if($product->category)
                                                ({{ $product->category }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id" required>
                                    <option value="">Selecione um fornecedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Preço (€) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="availability" class="form-label">Disponibilidade</label>
                                <select class="form-select @error('availability') is-invalid @enderror" 
                                        id="availability" name="availability">
                                    <option value="">Selecione</option>
                                    <option value="Em Stock" {{ old('availability') == 'Em Stock' ? 'selected' : '' }}>Em Stock</option>
                                    <option value="Sob Encomenda" {{ old('availability') == 'Sob Encomenda' ? 'selected' : '' }}>Sob Encomenda</option>
                                    <option value="Indisponível" {{ old('availability') == 'Indisponível' ? 'selected' : '' }}>Indisponível</option>
                                </select>
                                @error('availability')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="delivery_time" class="form-label">Tempo de Entrega</label>
                                <input type="text" class="form-control @error('delivery_time') is-invalid @enderror" 
                                       id="delivery_time" name="delivery_time" value="{{ old('delivery_time') }}" 
                                       placeholder="Ex: 5-7 dias úteis">
                                @error('delivery_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quality_rating" class="form-label">Avaliação de Qualidade</label>
                        <select class="form-select @error('quality_rating') is-invalid @enderror" 
                                id="quality_rating" name="quality_rating">
                            <option value="">Não avaliado</option>
                            <option value="1" {{ old('quality_rating') == '1' ? 'selected' : '' }}>⭐ (1 estrela)</option>
                            <option value="2" {{ old('quality_rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 estrelas)</option>
                            <option value="3" {{ old('quality_rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 estrelas)</option>
                            <option value="4" {{ old('quality_rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 estrelas)</option>
                            <option value="5" {{ old('quality_rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 estrelas)</option>
                        </select>
                        @error('quality_rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Observações</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Informações adicionais sobre a oferta">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('offers.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Criar Oferta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Dicas</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <small>Verifique se o produto e fornecedor estão corretos</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <small>Inclua informações sobre prazos de entrega</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <small>Avalie a qualidade para facilitar comparações</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

