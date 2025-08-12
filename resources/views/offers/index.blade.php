@extends('layouts.app')

@section('title', 'Ofertas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tag"></i> Ofertas</h1>
    <a href="{{ route('offers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Oferta
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($offers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Fornecedor</th>
                            <th>Preço</th>
                            <th>Disponibilidade</th>
                            <th>Tempo de Entrega</th>
                            <th>Qualidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td>
                                <td>
                                    <strong>{{ $offer->product->name }}</strong>
                                    @if($offer->product->category)
                                        <br><small class="text-muted">{{ $offer->product->category }}</small>
                                    @endif
                                </td>
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
                                        <a href="{{ route('offers.show', $offer) }}" class="btn btn-sm btn-outline-info btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('offers.edit', $offer) }}" class="btn btn-sm btn-outline-warning btn-action">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('offers.destroy', $offer) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja eliminar esta oferta?')">
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
                {{ $offers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tag display-1 text-muted"></i>
                <h3 class="text-muted mt-3">Nenhuma oferta encontrada</h3>
                <p class="text-muted">Comece criando a sua primeira oferta.</p>
                <a href="{{ route('offers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Criar Oferta
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

