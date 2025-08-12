<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_can_view_products_index()
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Produtos');
    }

    public function test_can_view_product_create_form()
    {
        $response = $this->get('/products/create');
        $response->assertStatus(200);
        $response->assertSee('Criar Produto');
    }

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Mesa de Teste',
            'category' => 'Mobiliário',
            'description' => 'Mesa para testes automatizados'
        ];

        $response = $this->post('/products', $productData);
        $response->assertRedirect('/products');
        
        $this->assertDatabaseHas('products', [
            'name' => 'Mesa de Teste',
            'category' => 'Mobiliário'
        ]);
    }

    public function test_can_view_product()
    {
        $product = Product::factory()->create([
            'name' => 'Produto de Teste',
            'category' => 'Categoria Teste'
        ]);

        $response = $this->get("/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertSee('Produto de Teste');
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete("/products/{$product->id}");
        $response->assertRedirect('/products');
        
        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }
}

