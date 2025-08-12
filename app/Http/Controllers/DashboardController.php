<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Offer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_products' => Product::count(),
            'total_suppliers' => Supplier::count(),
            'total_offers' => Offer::count(),
            'total_categories' => Category::count(),
        ];

        // Produtos por categoria
        $productsByCategory = Category::withCount('products')
            ->where('active', true)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->products_count,
                    'icon' => $category->icon,
                ];
            });

        // Produtos criados nos últimos 7 dias
        $recentProducts = Product::with('category')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ofertas recentes
        $recentOffers = Offer::with(['product', 'supplier'])
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Estatísticas de ofertas por mês (últimos 6 meses)
        $offersPerMonth = Offer::select(
                DB::raw('strftime("%Y-%m", created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'count' => $item->count,
                ];
            });

        // Top fornecedores por número de ofertas
        $topSuppliers = Supplier::withCount('offers')
            ->orderBy('offers_count', 'desc')
            ->limit(5)
            ->get()
            ->filter(function ($supplier) {
                return $supplier->offers_count > 0;
            });

        return view('dashboard', compact(
            'stats',
            'productsByCategory',
            'recentProducts',
            'recentOffers',
            'offersPerMonth',
            'topSuppliers'
        ));
    }

    /**
     * Obter dados para gráficos via AJAX
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'products_by_category':
                return response()->json(
                    Category::withCount('products')
                        ->where('active', true)
                        ->get()
                        ->map(function ($category) {
                            return [
                                'label' => $category->name,
                                'value' => $category->products_count,
                            ];
                        })
                );

            case 'offers_per_month':
                return response()->json(
                    Offer::select(
                            DB::raw('strftime("%Y-%m", created_at) as month'),
                            DB::raw('count(*) as count')
                        )
                        ->where('created_at', '>=', now()->subMonths(12))
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get()
                        ->map(function ($item) {
                            return [
                                'month' => $item->month,
                                'count' => $item->count,
                            ];
                        })
                );

            default:
                return response()->json([]);
        }
    }
}

