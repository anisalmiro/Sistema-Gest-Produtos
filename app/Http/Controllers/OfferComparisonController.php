<?php

namespace App\Http\Controllers;

use App\Models\OfferComparison;
use App\Models\Product;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferComparisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comparisons = OfferComparison::with(['product', 'selectedOffer.supplier'])->paginate(10);
        return view('offer-comparisons.index', compact('comparisons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productId = $request->get('product_id');
        $product = null;
        $offers = collect();

        if ($productId) {
            $product = Product::findOrFail($productId);
            $offers = $product->offers()->with('supplier')->get();
        }

        $products = Product::all();
        return view('offer-comparisons.create', compact('products', 'product', 'offers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'comparison_date' => 'required|date',
            'selected_offer_id' => 'nullable|exists:offers,id',
            'criteria_notes' => 'nullable|string',
            'comparison_criteria' => 'nullable|array'
        ]);

        OfferComparison::create($request->all());

        return redirect()->route('offer-comparisons.index')
            ->with('success', 'Comparação de ofertas criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferComparison $offerComparison)
    {
        $offerComparison->load(['product', 'selectedOffer.supplier']);
        $offers = $offerComparison->product->offers()->with('supplier')->get();
        
        return view('offer-comparisons.show', compact('offerComparison', 'offers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfferComparison $offerComparison)
    {
        $products = Product::all();
        $offers = $offerComparison->product->offers()->with('supplier')->get();
        
        return view('offer-comparisons.edit', compact('offerComparison', 'products', 'offers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfferComparison $offerComparison)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'comparison_date' => 'required|date',
            'selected_offer_id' => 'nullable|exists:offers,id',
            'criteria_notes' => 'nullable|string',
            'comparison_criteria' => 'nullable|array'
        ]);

        $offerComparison->update($request->all());

        return redirect()->route('offer-comparisons.index')
            ->with('success', 'Comparação de ofertas atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferComparison $offerComparison)
    {
        $offerComparison->delete();

        return redirect()->route('offer-comparisons.index')
            ->with('success', 'Comparação de ofertas eliminada com sucesso.');
    }

    /**
     * Compare offers for a specific product
     */
    public function compare(Product $product)
    {
        $offers = $product->offers()->with('supplier')->get();
        
        if ($offers->isEmpty()) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Este produto não tem ofertas para comparar.');
        }

        return view('offer-comparisons.compare', compact('product', 'offers'));
    }

    /**
     * Get offers for a product via AJAX
     */
    public function getOffersByProduct(Product $product)
    {
        $offers = $product->offers()->with('supplier')->get();
        return response()->json($offers);
    }
}
