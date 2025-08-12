@extends('layouts.app')

@section('title', 'Comparar Ofertas - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-graph-up"></i> Comparar Ofertas</h1>
    <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar ao Produto
    </a>
</div>

<!-- Product Info -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="card-title mb-1">{{ $product->name }}</h5>
                @if($product->category)
                    <span class="badge bg-secondary me-2">{{ $product->category }}</span>
                @endif
                @if($product->description)
                    <p class="text-muted mt-2 mb-0">{{ Str::limit($product->description, 100) }}</p>
                @endif
            </div>
            <div class="col-md-4 text-end">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $offers->count() }}</h4>
                        <small class="text-muted">Ofertas</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">€{{ number_format($offers->min('price'), 2) }}</h4>
                        <small class="text-muted">Menor Preço</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comparison Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Comparação de Ofertas</h5>
        <div>
            <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="calculateBestOffer()">
                <i class="bi bi-calculator"></i> Calcular Melhor Oferta
            </button>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#saveComparisonModal">
                <i class="bi bi-save"></i> Guardar Comparação
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%;">Critério</th>
                        @foreach($offers as $offer)
                            <th class="text-center" style="width: {{ 80 / $offers->count() }}%;">
                                <strong>{{ $offer->supplier->name }}</strong>
                                <br><small class="text-muted">Oferta #{{ $offer->id }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <!-- Price Row -->
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-currency-euro text-success"></i> Preço
                            <br><small class="text-muted">Menor é melhor</small>
                        </td>
                        @foreach($offers as $offer)
                            <td class="text-center {{ $offer->price == $offers->min('price') ? 'table-success' : '' }}">
                                <span class="fw-bold">€{{ number_format($offer->price, 2) }}</span>
                                @if($offer->price == $offers->min('price'))
                                    <br><small class="text-success"><i class="bi bi-check-circle"></i> Melhor preço</small>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Quality Row -->
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-star text-warning"></i> Qualidade
                            <br><small class="text-muted">Maior é melhor</small>
                        </td>
                        @foreach($offers as $offer)
                            <td class="text-center {{ $offer->quality_rating == $offers->max('quality_rating') && $offer->quality_rating ? 'table-success' : '' }}">
                                @if($offer->quality_rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $offer->quality_rating)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                    <br><span class="fw-bold">{{ $offer->quality_rating }}/5</span>
                                    @if($offer->quality_rating == $offers->max('quality_rating'))
                                        <br><small class="text-success"><i class="bi bi-check-circle"></i> Melhor qualidade</small>
                                    @endif
                                @else
                                    <span class="text-muted">Não avaliado</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Availability Row -->
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-box text-info"></i> Disponibilidade
                        </td>
                        @foreach($offers as $offer)
                            <td class="text-center">
                                @if($offer->availability)
                                    @if($offer->availability == 'Em Stock')
                                        <span class="badge bg-success">{{ $offer->availability }}</span>
                                    @elseif($offer->availability == 'Sob Encomenda')
                                        <span class="badge bg-warning">{{ $offer->availability }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $offer->availability }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Delivery Time Row -->
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-truck text-primary"></i> Tempo de Entrega
                        </td>
                        @foreach($offers as $offer)
                            <td class="text-center">
                                {{ $offer->delivery_time ?? 'Não informado' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Notes Row -->
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-chat-text text-secondary"></i> Observações
                        </td>
                        @foreach($offers as $offer)
                            <td class="text-center">
                                @if($offer->notes)
                                    <small>{{ Str::limit($offer->notes, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Score Row (calculated by JavaScript) -->
                    <tr id="scoreRow" style="display: none;">
                        <td class="fw-bold bg-light">
                            <i class="bi bi-trophy text-warning"></i> Pontuação Total
                            <br><small class="text-muted">Baseada nos critérios</small>
                        </td>
                        @foreach($offers as $offer)
                            <td class="text-center bg-light" id="score-{{ $offer->id }}">
                                <span class="fw-bold">-</span>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Criteria Weights -->
        <div class="row mt-4" id="criteriaWeights" style="display: none;">
            <div class="col-md-12">
                <h6>Pesos dos Critérios (para cálculo automático):</h6>
                <div class="row">
                    <div class="col-md-3">
                        <label for="priceWeight" class="form-label">Preço</label>
                        <input type="range" class="form-range" id="priceWeight" min="0" max="100" value="40" oninput="updateWeights()">
                        <small class="text-muted"><span id="priceWeightValue">40</span>%</small>
                    </div>
                    <div class="col-md-3">
                        <label for="qualityWeight" class="form-label">Qualidade</label>
                        <input type="range" class="form-range" id="qualityWeight" min="0" max="100" value="30" oninput="updateWeights()">
                        <small class="text-muted"><span id="qualityWeightValue">30</span>%</small>
                    </div>
                    <div class="col-md-3">
                        <label for="availabilityWeight" class="form-label">Disponibilidade</label>
                        <input type="range" class="form-range" id="availabilityWeight" min="0" max="100" value="20" oninput="updateWeights()">
                        <small class="text-muted"><span id="availabilityWeightValue">20</span>%</small>
                    </div>
                    <div class="col-md-3">
                        <label for="deliveryWeight" class="form-label">Entrega</label>
                        <input type="range" class="form-range" id="deliveryWeight" min="0" max="100" value="10" oninput="updateWeights()">
                        <small class="text-muted"><span id="deliveryWeightValue">10</span>%</small>
                    </div>
                </div>
                <small class="text-muted">Total: <span id="totalWeight">100</span>%</small>
            </div>
        </div>
    </div>
</div>

<!-- Save Comparison Modal -->
<div class="modal fade" id="saveComparisonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('offer-comparisons.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Guardar Comparação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="comparison_date" value="{{ now()->format('Y-m-d H:i:s') }}">
                    <input type="hidden" name="comparison_criteria" id="comparisonCriteria">
                    
                    <div class="mb-3">
                        <label for="selected_offer_id" class="form-label">Oferta Selecionada</label>
                        <select class="form-select" id="selected_offer_id" name="selected_offer_id">
                            <option value="">Nenhuma selecionada</option>
                            @foreach($offers as $offer)
                                <option value="{{ $offer->id }}">
                                    {{ $offer->supplier->name }} - €{{ number_format($offer->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="criteria_notes" class="form-label">Notas sobre os Critérios</label>
                        <textarea class="form-control" id="criteria_notes" name="criteria_notes" rows="3" 
                                  placeholder="Justificação da escolha, critérios considerados, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Comparação</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const offers = @json($offers);

function calculateBestOffer() {
    document.getElementById('scoreRow').style.display = '';
    document.getElementById('criteriaWeights').style.display = '';
    updateWeights();
}

function updateWeights() {
    const priceWeight = parseInt(document.getElementById('priceWeight').value);
    const qualityWeight = parseInt(document.getElementById('qualityWeight').value);
    const availabilityWeight = parseInt(document.getElementById('availabilityWeight').value);
    const deliveryWeight = parseInt(document.getElementById('deliveryWeight').value);
    
    // Update weight displays
    document.getElementById('priceWeightValue').textContent = priceWeight;
    document.getElementById('qualityWeightValue').textContent = qualityWeight;
    document.getElementById('availabilityWeightValue').textContent = availabilityWeight;
    document.getElementById('deliveryWeightValue').textContent = deliveryWeight;
    
    const total = priceWeight + qualityWeight + availabilityWeight + deliveryWeight;
    document.getElementById('totalWeight').textContent = total;
    
    // Calculate scores
    calculateScores(priceWeight, qualityWeight, availabilityWeight, deliveryWeight);
}

function calculateScores(priceWeight, qualityWeight, availabilityWeight, deliveryWeight) {
    const prices = offers.map(o => o.price);
    const qualities = offers.map(o => o.quality_rating || 0);
    const maxPrice = Math.max(...prices);
    const minPrice = Math.min(...prices);
    const maxQuality = Math.max(...qualities);
    
    let bestScore = 0;
    let bestOfferId = null;
    
    offers.forEach(offer => {
        let score = 0;
        
        // Price score (inverted - lower price is better)
        if (maxPrice !== minPrice) {
            const priceScore = ((maxPrice - offer.price) / (maxPrice - minPrice)) * 100;
            score += (priceScore * priceWeight) / 100;
        }
        
        // Quality score
        if (maxQuality > 0) {
            const qualityScore = ((offer.quality_rating || 0) / maxQuality) * 100;
            score += (qualityScore * qualityWeight) / 100;
        }
        
        // Availability score
        let availabilityScore = 0;
        if (offer.availability === 'Em Stock') availabilityScore = 100;
        else if (offer.availability === 'Sob Encomenda') availabilityScore = 60;
        else if (offer.availability === 'Indisponível') availabilityScore = 0;
        else availabilityScore = 30; // Unknown
        score += (availabilityScore * availabilityWeight) / 100;
        
        // Delivery score (simplified - could be more complex)
        let deliveryScore = 50; // Default
        if (offer.delivery_time) {
            if (offer.delivery_time.includes('1-2') || offer.delivery_time.includes('imediato')) deliveryScore = 100;
            else if (offer.delivery_time.includes('3-5')) deliveryScore = 80;
            else if (offer.delivery_time.includes('5-7')) deliveryScore = 60;
            else deliveryScore = 40;
        }
        score += (deliveryScore * deliveryWeight) / 100;
        
        // Update display
        const scoreElement = document.getElementById(`score-${offer.id}`);
        const isWinner = score > bestScore;
        if (isWinner) {
            bestScore = score;
            bestOfferId = offer.id;
        }
        
        scoreElement.innerHTML = `<span class="fw-bold">${score.toFixed(1)}</span>`;
    });
    
    // Highlight winner
    offers.forEach(offer => {
        const scoreElement = document.getElementById(`score-${offer.id}`);
        if (offer.id === bestOfferId) {
            scoreElement.classList.add('table-success');
            scoreElement.innerHTML += '<br><small class="text-success"><i class="bi bi-trophy"></i> Melhor oferta</small>';
            
            // Auto-select in modal
            document.getElementById('selected_offer_id').value = offer.id;
        } else {
            scoreElement.classList.remove('table-success');
        }
    });
    
    // Save criteria for modal
    const criteria = {
        priceWeight,
        qualityWeight,
        availabilityWeight,
        deliveryWeight,
        bestOfferId,
        bestScore
    };
    document.getElementById('comparisonCriteria').value = JSON.stringify(criteria);
}
</script>
@endpush

