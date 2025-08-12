<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfferComparisonController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rotas de recursos
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('offers', OfferController::class);
    Route::resource('offer-comparisons', OfferComparisonController::class);
    
    // Rota AJAX para obter especificações de categoria
    Route::get('/api/category-specs', [ProductController::class, 'getCategorySpecs'])->name('api.category-specs');
    
    // Rota AJAX para dados do dashboard
    Route::get('/api/dashboard-data', [DashboardController::class, 'getChartData'])->name('api.dashboard-data');
    
    // Rota para comparar ofertas
    Route::get('/compare-offers/{product}', [OfferComparisonController::class, 'compare'])->name('offers.compare');
});

require __DIR__.'/auth.php';
