# Documentação Técnica - Sistema de Gestão de Produtos v2.0

## Arquitetura da Aplicação

### Visão Geral
O sistema foi desenvolvido seguindo o padrão MVC (Model-View-Controller) do Laravel, com uma arquitetura modular e extensível que suporta especificações dinâmicas por categoria.

### Componentes Principais

#### 1. Autenticação (Laravel Breeze)
- **Middleware**: `auth`, `verified`
- **Rotas protegidas**: Todas as funcionalidades principais
- **Gestão de sessões**: Cookies seguros e tokens CSRF
- **Views**: Login, registo, recuperação de password

#### 2. Dashboard Interativo
- **Controller**: `DashboardController`
- **Estatísticas**: Contadores em tempo real
- **Gráficos**: Chart.js para visualização de dados
- **Widgets**: Atividade recente e ações rápidas

#### 3. Sistema de Especificações Dinâmicas
- **Modelos**: `Category`, `SpecificationTemplate`
- **Tipos de campo**: text, number, select, date
- **Validação**: Automática baseada no tipo
- **Armazenamento**: JSON no campo `dynamic_specifications`

## Modelos de Dados

### User (Utilizador)
```php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];
}
```

### Product (Produto)
```php
class Product extends Model
{
    protected $fillable = [
        'name', 'category_id', 'description', 'dynamic_specifications'
    ];
    
    protected $casts = [
        'dynamic_specifications' => 'array'
    ];
    
    // Relacionamentos
    public function category() { return $this->belongsTo(Category::class); }
    public function offers() { return $this->hasMany(Offer::class); }
}
```

### Category (Categoria)
```php
class Category extends Model
{
    protected $fillable = ['name', 'description', 'active'];
    protected $casts = ['active' => 'boolean'];
    
    // Relacionamentos
    public function products() { return $this->hasMany(Product::class); }
    public function specificationTemplates() { return $this->hasMany(SpecificationTemplate::class); }
}
```

### SpecificationTemplate (Template de Especificação)
```php
class SpecificationTemplate extends Model
{
    protected $fillable = [
        'category_id', 'field_name', 'field_label', 'field_type', 
        'field_options', 'required', 'order'
    ];
    
    protected $casts = [
        'field_options' => 'array',
        'required' => 'boolean'
    ];
    
    // Relacionamentos
    public function category() { return $this->belongsTo(Category::class); }
}
```

### Supplier (Fornecedor)
```php
class Supplier extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'contact_person', 'rating'
    ];
    
    // Relacionamentos
    public function offers() { return $this->hasMany(Offer::class); }
}
```

### Offer (Oferta)
```php
class Offer extends Model
{
    protected $fillable = [
        'product_id', 'supplier_id', 'price', 'quantity', 
        'delivery_time', 'quality_rating', 'notes'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'quality_rating' => 'integer'
    ];
    
    // Relacionamentos
    public function product() { return $this->belongsTo(Product::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
}
```

## Controladores

### DashboardController
```php
class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'products' => Product::count(),
            'suppliers' => Supplier::count(),
            'offers' => Offer::count(),
            'categories' => Category::where('active', true)->count(),
        ];
        
        // Dados para gráficos
        $productsByCategory = Category::withCount('products')->get();
        $recentProducts = Product::latest()->limit(5)->get();
        $recentOffers = Offer::latest()->limit(5)->get();
        
        return view('dashboard', compact('stats', ...));
    }
}
```

### ProductController
```php
class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Validação
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);
        
        // Criar produto
        $product = new Product();
        $product->fill($request->only(['name', 'category_id', 'description']));
        
        // Processar especificações dinâmicas
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
        
        $product->save();
        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso!');
    }
}
```

## Base de Dados

### Migrações Principais

#### Tabela Products
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
    $table->text('description')->nullable();
    $table->json('dynamic_specifications')->nullable();
    $table->timestamps();
});
```

#### Tabela Categories
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->boolean('active')->default(true);
    $table->timestamps();
});
```

#### Tabela Specification Templates
```php
Schema::create('specification_templates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('field_name');
    $table->string('field_label');
    $table->enum('field_type', ['text', 'number', 'select', 'date']);
    $table->json('field_options')->nullable();
    $table->boolean('required')->default(false);
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

### Seeders

#### CategorySeeder
```php
class CategorySeeder extends Seeder
{
    public function run()
    {
        // Criar categorias
        $categories = [
            'Equipamentos de Laboratório',
            'Mobiliário',
            'Equipamentos Informáticos',
            'Instrumentos de Medição'
        ];
        
        foreach ($categories as $categoryName) {
            $category = Category::create(['name' => $categoryName]);
            
            // Criar templates de especificações para cada categoria
            $this->createSpecificationTemplates($category);
        }
    }
}
```

## Frontend

### Layout Principal
- **Framework**: Bootstrap 5
- **Ícones**: Font Awesome
- **Gráficos**: Chart.js
- **Responsividade**: Mobile-first design

### Componentes JavaScript
```javascript
// Especificações dinâmicas
function loadCategorySpecifications(categoryId) {
    if (!categoryId) {
        $('#dynamic-specifications').empty();
        return;
    }
    
    $.get('/products/category-specifications', { category_id: categoryId })
        .done(function(templates) {
            renderSpecificationFields(templates);
        });
}

// Renderizar campos dinâmicos
function renderSpecificationFields(templates) {
    const container = $('#dynamic-specifications');
    container.empty();
    
    templates.forEach(function(template) {
        const field = createSpecificationField(template);
        container.append(field);
    });
}
```

### Estilos CSS Personalizados
```css
/* Sidebar */
.sidebar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* Cards do Dashboard */
.stat-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

/* Formulários */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
```

## Segurança

### Autenticação
- **Middleware**: `auth` em todas as rotas principais
- **Proteção CSRF**: Tokens em todos os formulários
- **Validação**: Server-side em todos os inputs
- **Sanitização**: Escape de dados nas views

### Autorização
- **Políticas**: Controlo de acesso por recurso
- **Gates**: Verificações de permissão personalizadas
- **Middleware**: Verificação de roles e permissões

## Performance

### Otimizações
- **Eager Loading**: Carregamento antecipado de relacionamentos
- **Paginação**: Limitação de resultados por página
- **Cache**: Cache de consultas frequentes
- **Índices**: Índices de base de dados otimizados

### Monitorização
- **Logs**: Laravel Log para debugging
- **Métricas**: Estatísticas de uso no dashboard
- **Profiling**: Laravel Debugbar (desenvolvimento)

## Testes

### Testes Unitários
```php
class ProductTest extends TestCase
{
    public function test_can_create_product()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/products', [
                'name' => 'Produto Teste',
                'description' => 'Descrição teste'
            ]);
            
        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'Produto Teste']);
    }
}
```

### Testes de Integração
- **Autenticação**: Login/logout/registo
- **CRUD**: Operações completas em todos os modelos
- **Especificações**: Criação dinâmica por categoria
- **Dashboard**: Carregamento de estatísticas

## Deployment

### Requisitos de Produção
- **PHP**: 8.1+
- **Base de dados**: MySQL/PostgreSQL (recomendado)
- **Servidor web**: Apache/Nginx
- **SSL**: Certificado HTTPS obrigatório

### Configuração de Produção
```bash
# Otimizações
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrações
php artisan migrate --force

# Permissões
chmod -R 755 storage bootstrap/cache
```

## Manutenção

### Backup
- **Base de dados**: Backup diário automático
- **Ficheiros**: Backup de uploads e logs
- **Configuração**: Versionamento de .env

### Monitorização
- **Logs**: Rotação automática de logs
- **Performance**: Monitoring de queries lentas
- **Erros**: Notificação automática de erros críticos

---

**Documentação atualizada em**: 11/07/2025  
**Versão da aplicação**: 2.0  
**Versão do Laravel**: 10.48.29

