<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SpecificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('active', true)->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;

        // Processar especificações dinâmicas baseadas na categoria
        if ($request->category_id) {
            $category = Category::with('specificationTemplates')->find($request->category_id);
            $dynamicSpecs = [];
            
            foreach ($category->specificationTemplates as $template) {
                $fieldName = 'spec_' . $template->id;
                if ($request->has($fieldName)) {
                    $dynamicSpecs[$template->field_name] = $request->input($fieldName);
                }
            }
            
            $product->dynamic_specifications = $dynamicSpecs;
        }

        // Processar especificações manuais (compatibilidade)
        if ($request->has('specifications')) {
            $specifications = [];
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $specifications[$spec['key']] = $spec['value'];
                }
            }
            $product->dynamic_specifications = array_merge($product->dynamic_specifications ?? [], $specifications);
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category.specificationTemplates');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->get();
        $product->load('category.specificationTemplates');
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;

        // Processar especificações dinâmicas baseadas na categoria
        if ($request->category_id) {
            $category = Category::with('specificationTemplates')->find($request->category_id);
            $dynamicSpecs = [];
            
            foreach ($category->specificationTemplates as $template) {
                $fieldName = 'spec_' . $template->id;
                if ($request->has($fieldName)) {
                    $dynamicSpecs[$template->field_name] = $request->input($fieldName);
                }
            }
            
            $product->dynamic_specifications = $dynamicSpecs;
        }

        // Processar especificações manuais (compatibilidade)
        if ($request->has('specifications')) {
            $specifications = [];
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $specifications[$spec['key']] = $spec['value'];
                }
            }
            $product->dynamic_specifications = array_merge($product->dynamic_specifications ?? [], $specifications);
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto removido com sucesso!');
    }

    /**
     * Obter especificações de categoria via AJAX
     */
    public function getCategorySpecifications(Request $request): JsonResponse
    {
        $categoryId = $request->get('category_id');
        
        if (!$categoryId) {
            return response()->json([]);
        }

        $category = Category::with('specificationTemplates')->find($categoryId);
        
        if (!$category) {
            return response()->json([]);
        }

        return response()->json($category->specificationTemplates);
    }
}

