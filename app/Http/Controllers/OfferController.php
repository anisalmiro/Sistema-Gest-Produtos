<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::with(['product', 'supplier'])->paginate(10);
        return view('offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('offers.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric|min:0',
            'availability' => 'nullable|string|max:255',
            'delivery_time' => 'nullable|string|max:255',
            'quality_rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        Offer::create($request->all());

        return redirect()->route('offers.index')
            ->with('success', 'Oferta criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        $offer->load(['product', 'supplier']);
        return view('offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('offers.edit', compact('offer', 'products', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric|min:0',
            'availability' => 'nullable|string|max:255',
            'delivery_time' => 'nullable|string|max:255',
            'quality_rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        $offer->update($request->all());

        return redirect()->route('offers.index')
            ->with('success', 'Oferta atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('offers.index')
            ->with('success', 'Oferta eliminada com sucesso.');
    }

    /**
     * Show offers for a specific product
     */
    public function byProduct(Product $product)
    {
        $offers = $product->offers()->with('supplier')->get();
        return view('offers.by-product', compact('product', 'offers'));
    }
}
