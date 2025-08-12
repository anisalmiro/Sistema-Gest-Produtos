@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box"></i> {{ $product->name }}</h1>
    <div>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Product Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informações do Produto</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nome:</strong>
                        <p>{{ $product->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Categoria:</strong>
                        <p>
                            @if($product->category)
                                <span class="badge bg-secondary">{{ $product->category }}</span>
                            @else
                                <span class="text-muted">Não definida</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($product->description)
                    <div class="mt-3">
                        <strong>Descrição:</strong>
                        <p>{{ $product->description }}</p>
                    </div>
                @endif

                @if($product->technical_specifications && count($product->technical_specifications) > 0)
                    <div class="mt-3">
                        <strong>Especificações Técnicas:</strong>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-bordered">
                                @foreach($product->technical_specifications as $spec)
                                    @if(isset($spec['key']) && isset($spec['value']) && $spec['key'] && $spec['value'])
                                        <tr>
                                            <td class="fw-bold" style="width: 30%;">{{ $spec['key'] }}</td>
                                            <td>{{ $spec['value'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Offers -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Ofertas ({{ $product->offers->count() }})</h5>
                <div>
                    @if($product->offers->count() > 1)
                        <a href="{{ route('offers.compare', $product) }}" class="btn btn-success btn-sm me-2">
                            <i class="bi bi-graph-up"></i> Comparar Ofertas
                        </a>
                    @endif
                    <a href="{{ route('offers.create', ['product_id' => $product->id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Nova Oferta
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($product->offers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fornecedor</th>
                                    <th>Preço</th>
                                    <th>Disponibilidade</th>
                                    <th>Tempo de Entrega</th>
                                    <th>Qualidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->offers as $offer)
                                    <tr>
                                        <td>
                                            <strong>{{ $offer->supplier->name }}</strong>
                                            @if($offer->supplier->email)
                                                <br><small class="text-muted">{{ $offer->supplier->email }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">€{{ number_format($offer->price, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($offer->availability)
                                                <span class="badge bg-info">{{ $offer->availability }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $offer->delivery_time ?? '-' }}
                                        </td>
                                        <td>
                                            @if($offer->quality_rating)
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $offer->quality_rating)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            @else
                                                <span class="text-muted">Não avaliado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('offers.show', $offer) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('offers.edit', $offer) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-tag display-4 text-muted"></i>
                        <h5 class="text-muted mt-3">Nenhuma oferta encontrada</h5>
                        <p class="text-muted">Adicione ofertas de fornecedores para este produto.</p>
                        <a href="{{ route('offers.create', ['product_id' => $product->id]) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Adicionar Oferta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Estatísticas</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $product->offers->count() }}</h4>
                        <small class="text-muted">Ofertas</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $product->offerComparisons->count() }}</h4>
                        <small class="text-muted">Comparações</small>
                    </div>
                </div>
                
                @if($product->offers->count() > 0)
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success">€{{ number_format($product->offers->min('price'), 2) }}</h5>
                            <small class="text-muted">Menor Preço</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-danger">€{{ number_format($product->offers->max('price'), 2) }}</h5>
                            <small class="text-muted">Maior Preço</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Comparisons -->
        @if($product->offerComparisons->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Comparações Recentes</h6>
                </div>
                <div class="card-body">
                    @foreach($product->offerComparisons->take(3) as $comparison)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted">{{ $comparison->comparison_date->format('d/m/Y') }}</small>
                                @if($comparison->selectedOffer)
                                    <br><small class="text-success">{{ $comparison->selectedOffer->supplier->name }}</small>
                                @endif
                            </div>
                            <a href="{{ route('offer-comparisons.show', $comparison) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

