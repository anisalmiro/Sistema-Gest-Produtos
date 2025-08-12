# Sistema de Gestão de Produtos - Versão 2.0

Uma aplicação Laravel moderna e completa para gestão de produtos, fornecedores e comparação de propostas, com autenticação, dashboard interativo e especificações dinâmicas por categoria.

## 🚀 Novas Funcionalidades (v2.0)

### ✨ Autenticação e Segurança
- **Sistema de Login/Registo** completo com Laravel Breeze
- **Proteção de rotas** com middleware de autenticação
- **Gestão de utilizadores** com perfis personalizados
- **Sessões seguras** e proteção CSRF

### 📊 Dashboard Interativo
- **Estatísticas em tempo real** do sistema
- **Gráficos informativos** de produtos por categoria
- **Widgets de atividade recente** para produtos e ofertas
- **Métricas de desempenho** e análise de dados
- **Interface moderna** com design responsivo

### 🏷️ Especificações Dinâmicas por Categoria
- **Templates de especificações** personalizáveis por categoria
- **Campos dinâmicos** que se adaptam à categoria selecionada
- **Tipos de campo variados**: texto, número, seleção, data
- **Validação automática** baseada no tipo de campo
- **Gestão flexível** de especificações técnicas

### 🎨 Interface Moderna
- **Design responsivo** compatível com dispositivos móveis
- **Menu lateral** intuitivo e navegação fluida
- **Cores e tipografia** profissionais
- **Componentes Bootstrap** modernos
- **Experiência de utilizador** otimizada

## 📋 Funcionalidades Principais

### Gestão de Produtos
- ✅ Criação, edição e remoção de produtos
- ✅ Especificações técnicas dinâmicas por categoria
- ✅ Categorização automática com templates
- ✅ Pesquisa e filtros avançados
- ✅ Visualização detalhada com especificações

### Gestão de Fornecedores
- ✅ Cadastro completo de fornecedores
- ✅ Informações de contacto e avaliação
- ✅ Histórico de ofertas e desempenho
- ✅ Sistema de classificação por qualidade

### Sistema de Ofertas
- ✅ Registo de propostas de fornecedores
- ✅ Comparação automática de preços
- ✅ Critérios de avaliação personalizáveis
- ✅ Análise de custo-benefício

### Comparação de Propostas
- ✅ Algoritmo inteligente de comparação
- ✅ Critérios múltiplos: preço, qualidade, prazo
- ✅ Relatórios de análise detalhados
- ✅ Recomendações automáticas

## 🛠️ Tecnologias Utilizadas

- **Laravel 10** - Framework PHP moderno
- **Laravel Breeze** - Sistema de autenticação
- **SQLite** - Base de dados leve e eficiente
- **Bootstrap 5** - Framework CSS responsivo
- **Chart.js** - Gráficos interativos
- **Font Awesome** - Ícones profissionais

## 📦 Instalação

### Pré-requisitos
- PHP 8.1 ou superior
- Composer
- SQLite (incluído no PHP)
- Node.js (opcional, para assets)

### Passos de Instalação

1. **Clone o repositório**
```bash
git clone <url-do-repositorio>
cd product-management-system
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure a base de dados**
```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

5. **Inicie o servidor**
```bash
php artisan serve
```

6. **Acesse a aplicação**
- URL: http://localhost:8000
- Crie uma conta ou use as credenciais de teste

## 🎯 Como Usar

### 1. Primeiro Acesso
1. Acesse http://localhost:8000
2. Clique em "Register" para criar uma conta
3. Preencha os dados e faça login
4. Será redirecionado para o dashboard

### 2. Dashboard
- **Visão geral** das estatísticas do sistema
- **Gráficos** de produtos por categoria
- **Atividade recente** de produtos e ofertas
- **Ações rápidas** para criar novos itens

### 3. Gestão de Produtos
1. Navegue para "Produtos" no menu lateral
2. Clique em "Novo Produto"
3. Selecione uma categoria (opcional)
4. As especificações aparecerão automaticamente
5. Preencha os dados e especificações
6. Salve o produto

### 4. Categorias e Especificações
- **Equipamentos de Laboratório**: Potência, voltagem, precisão
- **Mobiliário**: Dimensões, material, cor, peso
- **Equipamentos Informáticos**: Processador, memória, armazenamento
- **Instrumentos de Medição**: Faixa de medição, precisão, calibração

### 5. Fornecedores e Ofertas
1. Cadastre fornecedores em "Fornecedores"
2. Registe ofertas em "Ofertas"
3. Compare propostas em "Comparações"
4. Analise relatórios e tome decisões

## 🔧 Configuração Avançada

### Personalizar Categorias
1. Acesse a base de dados
2. Modifique a tabela `categories`
3. Adicione templates em `specification_templates`
4. Execute `php artisan migrate:refresh --seed`

### Adicionar Novos Tipos de Campo
1. Edite `SpecificationTemplate` model
2. Adicione novos tipos em `field_type`
3. Atualize as views para suportar o novo tipo
4. Teste a funcionalidade

## 📊 Estrutura da Base de Dados

### Tabelas Principais
- `users` - Utilizadores do sistema
- `products` - Produtos cadastrados
- `categories` - Categorias de produtos
- `specification_templates` - Templates de especificações
- `suppliers` - Fornecedores
- `offers` - Ofertas/Propostas
- `offer_comparisons` - Comparações de ofertas

### Relacionamentos
- Produtos pertencem a categorias
- Categorias têm templates de especificações
- Ofertas pertencem a produtos e fornecedores
- Comparações analisam múltiplas ofertas

## 🚀 Funcionalidades Futuras

- [ ] API REST para integração externa
- [ ] Notificações em tempo real
- [ ] Relatórios em PDF
- [ ] Integração com sistemas ERP
- [ ] App móvel nativo
- [ ] Análise preditiva de preços

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

## 📞 Suporte

Para suporte técnico ou dúvidas:
- Email: suporte@sistema.pt
- Documentação: [docs/](docs/)
- Issues: GitHub Issues

---

**Desenvolvido com ❤️ usando Laravel**

