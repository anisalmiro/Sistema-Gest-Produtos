# Guia de Instalação Rápida

## Pré-requisitos

Antes de instalar a aplicação, certifique-se de que tem os seguintes requisitos:

- PHP 8.1 ou superior
- Composer (gestor de dependências PHP)
- Servidor web (Apache, Nginx ou PHP built-in server)
- Base de dados (SQLite, MySQL ou PostgreSQL)

### Verificar Requisitos

```bash
# Verificar versão do PHP
php --version

# Verificar se o Composer está instalado
composer --version

# Verificar extensões PHP necessárias
php -m | grep -E "(sqlite3|pdo|mbstring|xml|curl|zip|gd|bcmath)"
```

## Instalação Passo a Passo

### 1. Obter o Código

```bash
# Se usando Git
git clone <url-do-repositorio> product-management-system
cd product-management-system

# Ou extrair de arquivo ZIP
unzip product-management-system.zip
cd product-management-system
```

### 2. Instalar Dependências

```bash
composer install
```

### 3. Configurar Ambiente

```bash
# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 4. Configurar Base de Dados

#### Opção A: SQLite (Recomendado para desenvolvimento)

```bash
# Criar arquivo de base de dados
touch database/database.sqlite

# O arquivo .env já está configurado para SQLite
```

#### Opção B: MySQL

```bash
# Editar .env
nano .env
```

Configurar as seguintes linhas:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_management
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

#### Opção C: PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=product_management
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Executar Migrações

```bash
php artisan migrate
```

### 6. Configurar Permissões (Linux/Mac)

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 7. Iniciar Aplicação

#### Desenvolvimento (PHP built-in server)
```bash
php artisan serve
```
Aceder a: http://localhost:8000

#### Produção (Apache/Nginx)
Configurar virtual host apontando para a pasta `public/`

## Configuração Avançada

### Configurar Email (Opcional)

Para notificações por email, editar `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="Sistema de Gestão de Produtos"
```

### Configurar Cache (Produção)

```bash
# Instalar Redis (opcional)
sudo apt install redis-server

# Configurar cache no .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Otimização para Produção

```bash
# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimizar autoloader
composer install --optimize-autoloader --no-dev
```

## Configuração do Servidor Web

### Apache

Criar arquivo `.htaccess` na pasta `public/`:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

Virtual Host:
```apache
<VirtualHost *:80>
    ServerName product-management.local
    DocumentRoot /caminho/para/product-management-system/public
    
    <Directory /caminho/para/product-management-system/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

```nginx
server {
    listen 80;
    server_name product-management.local;
    root /caminho/para/product-management-system/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Dados de Exemplo (Opcional)

Para popular a aplicação com dados de exemplo:

```bash
# Criar seeder
php artisan make:seeder DemoDataSeeder

# Executar seeder
php artisan db:seed --class=DemoDataSeeder
```

## Verificação da Instalação

### Teste Básico

1. Aceder à aplicação no browser
2. Verificar se a página inicial carrega
3. Tentar criar um produto
4. Tentar criar um fornecedor
5. Tentar criar uma oferta

### Teste de Funcionalidades

```bash
# Executar testes automatizados
php artisan test
```

### Verificar Logs

```bash
# Ver logs em tempo real
tail -f storage/logs/laravel.log
```

## Resolução de Problemas

### Erro: "Class not found"
```bash
composer dump-autoload
```

### Erro: "Permission denied"
```bash
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
```

### Erro: "Database connection failed"
- Verificar credenciais no `.env`
- Confirmar que a base de dados existe
- Testar conexão manualmente

### Erro: "Key not found"
```bash
php artisan key:generate
```

### Erro 500 - Internal Server Error
- Verificar logs em `storage/logs/laravel.log`
- Ativar debug mode: `APP_DEBUG=true` no `.env`
- Verificar permissões de pastas

## Backup e Restauro

### Backup

```bash
# Backup da base de dados (SQLite)
cp database/database.sqlite backup/database_$(date +%Y%m%d).sqlite

# Backup da base de dados (MySQL)
mysqldump -u usuario -p product_management > backup/database_$(date +%Y%m%d).sql

# Backup de arquivos
tar -czf backup/files_$(date +%Y%m%d).tar.gz storage/ public/uploads/
```

### Restauro

```bash
# Restaurar base de dados (SQLite)
cp backup/database_20250711.sqlite database/database.sqlite

# Restaurar base de dados (MySQL)
mysql -u usuario -p product_management < backup/database_20250711.sql

# Restaurar arquivos
tar -xzf backup/files_20250711.tar.gz
```

## Atualizações

### Atualizar Aplicação

```bash
# Fazer backup
php artisan down

# Atualizar código
git pull origin main

# Atualizar dependências
composer install --no-dev

# Executar migrações
php artisan migrate

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Reativar aplicação
php artisan up
```

## Suporte

Para questões técnicas:
- Consultar logs em `storage/logs/`
- Verificar documentação técnica em `docs/`
- Contactar equipa de desenvolvimento

---

**Nota**: Este guia assume conhecimentos básicos de administração de sistemas e desenvolvimento web. Para instalações em ambiente de produção, recomenda-se consultar um administrador de sistemas experiente.

