# Documentação do Plugin de Produtos - Paws&Patterns

## Visão Geral

O Plugin de Produtos para a loja online Paws&Patterns é um sistema modular desenvolvido para gerenciar o catálogo de produtos e categorias da boutique pet. O plugin foi projetado com uma abordagem modular que separa claramente responsabilidades entre diferentes componentes, facilitando a manutenção e extensibilidade.

Este plugin permite o gerenciamento completo (CRUD) de produtos e categorias, incluindo recursos como:
- Organização hierárquica de categorias
- Produtos com múltiplas categorias
- Atributos personalizáveis para produtos
- Suporte a imagens múltiplas
- Preços promocionais
- Metadados serializados
- Exclusão lógica (soft delete)

## Estrutura de Pastas

```
plugins/
└── product/
    ├── api/
    │   ├── category_api.php      # Funções de API para categorias
    │   ├── product_api.php       # Funções de API para produtos  
    │   ├── product_helpers.php   # Funções auxiliares para produtos
    │   └── utils_api.php         # Funções utilitárias compartilhadas
    ├── controllers/
    │   ├── category_controller.php  # Controlador para categorias
    │   └── product_controller.php   # Controlador para produtos
    ├── css/
    │   ├── categories.css        # Estilos para páginas de categorias
    │   ├── menu_styles.css       # Estilos para o menu lateral
    │   └── products.css          # Estilos para páginas de produtos
    ├── database/
    │   ├── categories.sql        # Estrutura de tabelas para categorias
    │   └── products.sql          # Estrutura de tabelas para produtos
    ├── hooks/
    │   └── menu_hooks.php        # Hooks para integração com o menu
    ├── js/
    │   └── products.js           # JavaScript para funcionalidades de produtos
    ├── views/
    │   ├── category_view.php     # View principal para categorias
    │   ├── product_detail_view.php  # View de detalhes do produto
    │   ├── product_form_view.php    # View de formulário de produto
    │   ├── product_list_view.php    # View de listagem de produtos
    │   └── product_view.php         # View principal para produtos
    ├── autoload.php              # Arquivo de carregamento automático
    ├── index.php                 # Arquivo de entrada (vazio)
    └── plugin_routes.php         # Definições de rotas do plugin
```

## Componentes Principais

### 1. Arquivos de Inicialização

- **autoload.php**: Carrega todas as dependências do plugin, incluindo APIs, helpers e hooks.
- **index.php**: Arquivo de entrada vazio (mantido por convenção).
- **plugin_routes.php**: Define as rotas disponíveis no plugin e associa cada rota a um controlador e uma view.

### 2. Camada de API (api/)

Esta camada fornece funções para interação direta com o banco de dados e processamento de dados:

- **category_api.php**: Manipula operações CRUD para categorias.
- **product_api.php**: Manipula operações CRUD para produtos.
- **product_helpers.php**: Contém funções auxiliares específicas para produtos.
- **utils_api.php**: Contém funções utilitárias compartilhadas como geração de slugs.

### 3. Controladores (controllers/)

Os controladores processam as solicitações do usuário, interagem com as APIs e preparam dados para as views:

- **category_controller.php**: Gerencia ações relacionadas a categorias.
- **product_controller.php**: Gerencia ações relacionadas a produtos.

### 4. Views (views/)

As views são responsáveis pela apresentação visual dos dados:

- **category_view.php**: Interface para visualização e gerenciamento de categorias.
- **product_view.php**: Interface principal para produtos.
- **product_list_view.php**: Listagem de produtos com filtros e paginação.
- **product_form_view.php**: Formulário para adição/edição de produtos.
- **product_detail_view.php**: Visualização detalhada de um produto específico.

### 5. Banco de Dados (database/)

Contém os scripts SQL para criação das tabelas necessárias:

- **categories.sql**: Estrutura da tabela de categorias e relações.
- **products.sql**: Estrutura das tabelas de produtos, variações e tags.

### 6. Assets (css/ e js/)

Recursos estáticos para a interface do usuário:

- **CSS**: Estilos específicos para categorias, produtos e menus.
- **JavaScript**: Funcionalidades interativas para o gerenciamento de produtos.

### 7. Hooks (hooks/)

Contém os hooks que integram o plugin com o sistema principal:

- **menu_hooks.php**: Integra o plugin com o menu lateral do dashboard.

## Fluxo de Funcionamento

### 1. Inicialização

1. O sistema carrega o arquivo `autoload.php` do plugin.
2. O `autoload.php` inclui todos os arquivos necessários (APIs, helpers, hooks, etc.).
3. Os hooks são registrados no sistema principal.

### 2. Roteamento

1. Quando uma URL é acessada (como `/index.php/products`), o sistema identifica a rota.
2. O sistema consulta `plugin_routes.php` para determinar o controlador e a view associados.
3. O controlador correspondente é carregado (ex: `product_controller.php`).

### 3. Controladores e Ações

1. O controlador recebe a solicitação e determina a ação a ser executada.
2. O controlador chama as funções de API apropriadas para processar dados.
3. O controlador prepara os dados para a view (armazenados em `$view_data`).

### 4. Renderização da View

1. A view correspondente é carregada.
2. A view acessa os dados preparados pelo controlador via `$view_data`.
3. A view renderiza a interface do usuário com os dados.

## API para Uso no Front-end

O plugin oferece várias funções de API que podem ser utilizadas diretamente no front-end do site para exibir produtos aos clientes:

### Listagem de Produtos

```php
// Obter produtos recentes
$latest_products = get_new_products(8); // Obtém 8 produtos mais recentes

// Obter produtos em destaque
$featured_products = get_featured_products(4); // Obtém 4 produtos em destaque

// Obter produtos em promoção
$sale_products = get_sale_products(6); // Obtém 6 produtos em promoção
```

### Exibição de Produtos por Categoria

```php
// Obter produtos de uma categoria específica
$cat_products = get_products_by_category(5, 12); // Produtos da categoria ID 5, máximo 12 produtos
```

### Busca de Produtos

```php
// Pesquisar produtos com filtros
$search_results = search_products('dog collar', [
    'price_min' => 10,
    'price_max' => 50,
    'sort_by' => 'price',
    'sort_order' => 'asc'
], 1, 24); // Página 1, 24 produtos por página
```

### Detalhes de um Produto

```php
// Obter dados detalhados de um produto
$product = get_product(123); // Produto com ID 123
```

### Formatação de Preços

```php
// Formatar preço para exibição
$formatted_price = format_product_price(29.99); // Retorna "€29.99"

// Calcular desconto
$discount = get_product_discount_percent(50, 40); // Retorna 20 (%)
```

## Integrando na Frente da Loja (Cliente)

Para integrar este plugin na parte pública do site (front-end), você pode:

### 1. Criar Templates Específicos

Desenvolva templates específicos no tema que utilizem as funções de API:

```php
<!-- Exemplo de template para página de produtos em destaque -->
<div class="featured-products">
    <h2>Featured Products</h2>
    <div class="product-grid">
        <?php foreach (get_featured_products(8) as $product): ?>
            <div class="product-card">
                <img src="<?php echo get_product_main_image($product); ?>" alt="<?php echo $product['name']; ?>">
                <h3><?php echo $product['name']; ?></h3>
                <p class="price"><?php echo format_product_price($product['price']); ?></p>
                <a href="/shop/product/<?php echo $product['slug']; ?>" class="btn">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
```

### 2. Endpoints de API para JavaScript

Você também pode criar endpoints de API RESTful que o JavaScript do front-end pode chamar:

```php
// Em um arquivo de API
function product_api_endpoint() {
    header('Content-Type: application/json');
    
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'featured':
            $count = isset($_GET['count']) ? (int)$_GET['count'] : 4;
            echo json_encode(get_featured_products($count));
            break;
            
        case 'product':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            echo json_encode(get_product($id));
            break;
            
        case 'search':
            $term = $_GET['term'] ?? '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            echo json_encode(search_products($term, [], $page, 12));
            break;
            
        default:
            echo json_encode(['error' => 'Unknown action']);
    }
    
    exit;
}
```

### 3. Exemplo JavaScript para Carregar Produtos

```javascript
// Carregar produtos em destaque
fetch('/api/products?action=featured&count=4')
    .then(response => response.json())
    .then(products => {
        const container = document.querySelector('.featured-products');
        
        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            
            productCard.innerHTML = `
                <img src="${product.main_image}" alt="${product.name}">
                <h3>${product.name}</h3>
                <p class="price">€${parseFloat(product.price).toFixed(2)}</p>
                <a href="/shop/product/${product.slug}" class="btn">View Details</a>
            `;
            
            container.appendChild(productCard);
        });
    });
```

## Manipulação de URLs

### URLs do Painel Administrativo

O plugin usa o seguinte padrão de URLs para o painel administrativo:

- **Categorias**:
  - `/index.php/category` - Listagem de categorias
  - `/index.php/category_add` - Adicionar categoria
  - `/index.php/category_edit/[id]` - Editar categoria com ID específico

- **Produtos**:
  - `/index.php/products` - Listagem de produtos
  - `/index.php/product_add` - Adicionar produto
  - `/index.php/product_edit/[id]` - Editar produto
  - `/index.php/product_detail/[id]` - Visualizar detalhes do produto

### URLs do Front-end (Recomendadas)

Para o front-end do site (cliente), recomendamos um formato mais amigável para SEO:

- **Páginas de Categoria**:
  - `/shop/category/[slug]` - Exibir produtos de uma categoria

- **Páginas de Produto**:
  - `/shop/product/[slug]` - Exibir detalhes de um produto

- **Páginas de Busca**:
  - `/shop/search?q=[termo]` - Resultados de busca

## Segurança

### Autenticação e Autorização

O plugin utiliza o sistema de autenticação existente (`user_auth`) para garantir que apenas usuários autorizados possam acessar o painel administrativo. As funções do controlador verificam a autenticação antes de executar qualquer ação:

```php
// Verifica se o usuário está autenticado
if (function_exists('require_authentication')) {
    require_authentication();
}
```

### Validação de Dados

O plugin implementa validação rigorosa de dados tanto no lado do cliente (JavaScript) quanto no lado do servidor (PHP):

```php
// Exemplo de validação de produto
function validate_product_data($product_data, $product_id = null) {
    $errors = [];
    
    // Validações básicas
    if (empty($product_data['name'])) {
        $errors['name'] = 'Product name is required';
    }
    
    if (empty($product_data['sku'])) {
        $errors['sku'] = 'SKU is required';
    } else {
        // Verifica se o SKU já existe
        // ...
    }
    
    // Mais validações...
    
    return $errors;
}
```

### Sanitização de Dados

Todos os dados exibidos nas views são sanitizados para prevenir ataques XSS:

```php
<h3><?php echo htmlspecialchars($product['name']); ?></h3>
```

## Personalização

O plugin foi projetado para ser facilmente personalizável:

### Adicionando Novos Campos a Produtos

1. Adicione o campo à tabela `products` no banco de dados
2. Atualize as funções de API em `product_api.php`
3. Adicione o campo ao formulário em `product_form_view.php`
4. Atualize as validações em `product_controller.php`

### Criando Novos Tipos de Visualização

Para adicionar uma nova visualização de produtos (ex: grade, tabela, etc.):

1. Crie um novo arquivo de view em `views/`
2. Atualize `product_controller.php` para suportar a nova visualização
3. Adicione uma nova rota em `plugin_routes.php`

### Estendendo para Outros Idiomas

O sistema atual está configurado para inglês (mercado irlandês), mas pode ser adaptado para suportar múltiplos idiomas:

1. Extraia todas as strings visíveis ao usuário para arquivos de tradução
2. Implemente um sistema de seleção de idioma
3. Carregue o arquivo de tradução apropriado com base na preferência do usuário

## Notas de Implementação

### Armazenamento de Dados Estruturados

O plugin utiliza PHP `serialize()` para armazenar estruturas de dados complexas:

```php
// Serializar atributos
if (isset($product_data['attributes']) && is_array($product_data['attributes'])) {
    $product_data['attributes'] = serialize($product_data['attributes']);
}

// Desserializar atributos
if (isset($product['attributes']) && !empty($product['attributes'])) {
    $product['attributes'] = unserialize($product['attributes']);
}
```

### Sistema de Exclusão Lógica

Todos os registros usam exclusão lógica (soft delete) em vez de exclusão física:

```php
// Excluir produto logicamente
function delete_product($product_id) {
    $delete_data = [
        'is_deleted' => true,
        'deleted_at' => date('Y-m-d H:i:s'),
        'status' => 'Inativo'
    ];
    
    $where = ['id' => $product_id];
    return update('products', $delete_data, $where);
}
```

## Considerações Finais

Este plugin foi desenvolvido especificamente para a boutique online Paws&Patterns, com foco no mercado irlandês. Ele segue as melhores práticas de desenvolvimento e foi projetado para ser extensível e fácil de manter.

Para necessidades futuras, considere:

1. **Integração com Pagamentos**: Adicionar suporte para processamento de pagamentos europeus.
2. **Gestão de Estoque Avançada**: Implementar recursos de alerta de estoque baixo e rastreamento de inventário.
3. **Relatórios**: Desenvolver recursos de relatórios para análise de vendas e desempenho de produtos.
4. **Importação/Exportação**: Adicionar recursos para importação e exportação em massa de produtos.

---

© 2025 Paws&Patterns - Pet Boutique Ireland