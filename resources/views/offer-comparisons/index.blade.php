@extends('layouts.app')

@section('title', 'Comparações de Ofertas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-graph-up"></i> Comparações de Ofertas</h1>
    <a href="{{ route('offer-comparisons.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Comparação
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($comparisons->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Data da Comparação</th>
                            <th>Oferta Selecionada</th>
                            <th>Fornecedor Escolhido</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comparisons as $comparison)
                            <tr>
                                <td>{{ $comparison->id }}</td>
                                <td>
                                    <strong>{{ $comparison->product->name }}</strong>
                                    @if($comparison->product->category)
                                        <br><small class="text-muted">{{ $comparison->product->category }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $comparison->comparison_date->format('d/m/Y H:i') }}
                                    <br><small class="text-muted">{{ $comparison->comparison_date->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($comparison->selectedOffer)
                                        <span class="badge bg-success">Oferta #{{ $comparison->selectedOffer->id }}</span>
                                        <br><small class="text-muted">€{{ number_format($comparison->selectedOffer->price, 2) }}</small>
                                    @else
                                        <span class="text-muted">Nenhuma selecionada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($comparison->selectedOffer)
                                        <strong>{{ $comparison->selectedOffer->supplier->name }}</strong>
                                        @if($comparison->selectedOffer->supplier->email)
                                            <br><small class="text-muted">{{ $comparison->selectedOffer->supplier->email }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('offer-comparisons.show', $comparison) }}" class="btn btn-sm btn-outline-info btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('offers.compare', $comparison->product) }}" class="btn btn-sm btn-outline-success btn-action">
                                            <i class="bi bi-graph-up"></i>
                                        </a>
                                        <a href="{{ route('offer-comparisons.edit', $comparison) }}" class="btn btn-sm btn-outline-warning btn-action">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('offer-comparisons.destroy', $comparison) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja eliminar esta comparação?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $comparisons->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-graph-up display-1 text-muted"></i>
                <h3 class="text-muted mt-3">Nenhuma comparação encontrada</h3>
                <p class="text-muted">Comece comparando ofertas para os seus produtos.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-box"></i> Ver Produtos
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

