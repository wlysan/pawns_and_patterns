<?php
/**
 * Controller de Produtos
 * Gerencia as operações CRUD dos produtos
 */

// Inclui funções API do produto
require_once 'plugins/product/api/product_api.php';

// Inicializa a sessão se ainda não estiver iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Inicializa a variável global $view_data para uso na view
$view_data = [
    'errors' => [],
    'success' => '',
    'products' => [],
    'current_product' => null,
    'categories' => [],
    'page_title' => 'Products',
    'mode' => 'list' // Modos: list, add, edit, delete, detail
];

/**
 * Função principal que processa as ações do controller
 * baseado na URL e método HTTP
 */
function process_product_request() {
    global $view_data;
    
    // Verifica se o usuário está autenticado (função do plugin user_auth)
    if (function_exists('require_authentication')) {
        require_authentication();
    }
    
    // Obtém o caminho da URL
    $path = $_SERVER['PATH_INFO'] ?? '/';
    $path_parts = explode('/', trim($path, '/'));
    
    // Determina a ação com base na URL
    $base_route = $path_parts[0] ?? '';
    
    // Verifica se há um ID na URL (para ações de edição/visualização)
    $product_id = isset($path_parts[1]) && is_numeric($path_parts[1]) ? (int)$path_parts[1] : null;
    
    // Configura o modo baseado na rota
    switch ($base_route) {
        case 'product_add':
            $view_data['mode'] = 'add';
            $view_data['page_title'] = 'Add New Product';
            handle_add_product();
            break;
            
        case 'product_edit':
            if ($product_id) {
                $view_data['mode'] = 'edit';
                $view_data['page_title'] = 'Edit Product';
                handle_edit_product($product_id);
            } else {
                // Redireciona para a listagem se não for especificado um ID
                header('Location: /index.php/products');
                exit;
            }
            break;
            
        case 'product_delete':
            if ($product_id) {
                $view_data['mode'] = 'delete';
                handle_delete_product($product_id);
            } else {
                // Redireciona para a listagem se não for especificado um ID
                header('Location: /index.php/products');
                exit;
            }
            break;
            
        case 'product_detail':
            if ($product_id) {
                $view_data['mode'] = 'detail';
                $view_data['page_title'] = 'Product Details';
                handle_product_detail($product_id);
            } else {
                // Redireciona para a listagem se não for especificado um ID
                header('Location: /index.php/products');
                exit;
            }
            break;
            
        default: // 'products' ou qualquer outra rota
            $view_data['mode'] = 'list';
            load_products();
            break;
    }
    
    // Carrega categorias para menu dropdown (para todas as telas)
    $view_data['all_categories'] = list_categories(['status' => 'Ativo']);
}

/**
 * Carrega a lista de produtos para exibição
 */
function load_products() {
    global $view_data;
    
    // Configura a paginação
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    
    // Calcula o offset
    $offset = ($page - 1) * $per_page;
    
    // Filtros
    $filters = ['status' => ['operador' => '!=', 'valor' => 'Inativo']];
    
    // Adiciona filtros da busca
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);
        $view_data['search'] = $search;
        
        // Adiciona busca por nome ou SKU
        if (isset($_GET['search_field']) && $_GET['search_field'] === 'sku') {
            $filters['sku'] = ['operador' => 'LIKE', 'valor' => "%$search%"];
        } else {
            $filters['name'] = ['operador' => 'LIKE', 'valor' => "%$search%"];
        }
    }
    
    // Filtro por categoria
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $category_id = (int)$_GET['category'];
        $view_data['selected_category'] = $category_id;
        
        // Necessário uma abordagem diferente para filtrar por categoria
        // Isso seria implementado na função list_products
    }
    
    // Ordenação
    $order_by = [];
    if (isset($_GET['sort_by']) && !empty($_GET['sort_by'])) {
        $sort_by = $_GET['sort_by'];
        $sort_order = (isset($_GET['sort_order']) && $_GET['sort_order'] === 'asc') ? 'ASC' : 'DESC';
        $order_by[$sort_by] = $sort_order;
        
        $view_data['sort_by'] = $sort_by;
        $view_data['sort_order'] = strtolower($sort_order);
    } else {
        $order_by['created_at'] = 'DESC';
    }
    
    // Configuração de paginação
    $pagination = [
        'page' => $page,
        'per_page' => $per_page
    ];
    
    // Carrega produtos
    $products = list_products($filters, $pagination, $order_by);
    $total_products = count_products($filters);
    
    // Informações de paginação para a view
    $view_data['products'] = $products;
    $view_data['pagination'] = [
        'current_page' => $page,
        'per_page' => $per_page,
        'total_items' => $total_products,
        'total_pages' => ceil($total_products / $per_page)
    ];
}

/**
 * Processa a adição de um novo produto
 */
function handle_add_product() {
    global $view_data;
    
    // Configurações iniciais para o formulário
    $view_data['current_product'] = [
        'name' => '',
        'sku' => '',
        'description' => '',
        'short_description' => '',
        'price' => '',
        'sale_price' => '',
        'stock_quantity' => 0,
        'weight' => '',
        'dimensions' => '',
        'attributes' => [],
        'images' => [],
        'categories' => [],
        'primary_category' => null,
        'featured' => false,
        'status' => 'Pendente'
    ];
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Coleta dados do formulário
        $product_data = collect_product_form_data();
        
        // Validação
        $errors = validate_product_data($product_data);
        
        // Se não houver erros, salva o produto
        if (empty($errors)) {
            $product_id = create_product($product_data);
            
            if ($product_id) {
                // Redireciona para a listagem com mensagem de sucesso
                header('Location: /index.php/products/sucesso/add');
                exit;
            } else {
                $errors['general'] = 'Failed to create product. Please try again.';
            }
        }
        
        $view_data['errors'] = $errors;
        
        // Mantém os dados inseridos para preenchimento do formulário
        $view_data['current_product'] = $product_data;
    }
}

/**
 * Processa a edição de um produto existente
 * @param int $product_id ID do produto a ser editado
 */
function handle_edit_product($product_id) {
    global $view_data;
    
    // Carrega dados do produto
    $product = get_product($product_id);
    
    if (!$product) {
        // Produto não encontrado, redireciona para listagem
        header('Location: /index.php/products/erro/not_found');
        exit;
    }
    
    // Inicializa com os dados existentes
    $view_data['current_product'] = $product;
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Coleta dados do formulário
        $product_data = collect_product_form_data();
        
        // Validação
        $errors = validate_product_data($product_data, $product_id);
        
        // Se não houver erros, atualiza o produto
        if (empty($errors)) {
            $success = update_product($product_id, $product_data);
            
            if ($success) {
                // Redireciona para a listagem com mensagem de sucesso
                header('Location: /index.php/products/sucesso/update');
                exit;
            } else {
                $errors['general'] = 'Failed to update product. Please try again.';
            }
        }
        
        $view_data['errors'] = $errors;
        
        // Atualiza os dados do produto com os valores enviados
        $view_data['current_product'] = $product_data;
        $view_data['current_product']['id'] = $product_id;
    }
}

/**
 * Processa a exibição detalhada de um produto
 * @param int $product_id ID do produto a ser visualizado
 */
function handle_product_detail($product_id) {
    global $view_data;
    
    // Carrega dados do produto
    $product = get_product($product_id);
    
    if (!$product) {
        // Produto não encontrado, redireciona para listagem
        header('Location: /index.php/products/erro/not_found');
        exit;
    }
    
    // Atualiza o título da página com o nome do produto
    $view_data['page_title'] = $product['name'];
    
    // Define o produto atual para a view
    $view_data['current_product'] = $product;
    
    // Carrega produtos relacionados (da mesma categoria)
    $related_products = [];
    if (!empty($product['categories'])) {
        $category_ids = array_column($product['categories'], 'id');
        $primary_category = $product['primary_category'] ?? $category_ids[0];
        
        // Busca produtos da mesma categoria principal
        $filters = [
            'id' => ['operador' => '!=', 'valor' => $product_id],
            'status' => 'Ativo'
        ];
        $pagination = ['page' => 1, 'per_page' => 4];
        
        // Implementação simplificada - em um sistema real, seria necessário
        // um join ou uma lógica mais complexa para filtrar por categoria
        $all_products = list_products($filters, $pagination);
        
        // Filtra manualmente produtos da mesma categoria
        foreach ($all_products as $related) {
            $related_categories = array_column($related['categories'] ?? [], 'id');
            if (in_array($primary_category, $related_categories)) {
                $related_products[] = $related;
            }
        }
        
        // Limita a 4 produtos relacionados
        $related_products = array_slice($related_products, 0, 4);
    }
    
    $view_data['related_products'] = $related_products;
}

/**
 * Processa a exclusão de um produto
 * @param int $product_id ID do produto a ser excluído
 */
function handle_delete_product($product_id) {
    // Verifica se a confirmação foi enviada
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        $success = delete_product($product_id);
        
        if ($success) {
            // Redireciona para a listagem com mensagem de sucesso
            header('Location: /index.php/products/sucesso/delete');
            exit;
        } else {
            // Redireciona com mensagem de erro
            header('Location: /index.php/products/erro/delete');
            exit;
        }
    } else {
        // Carrega dados do produto para confirmação
        global $view_data;
        $product = get_product($product_id);
        
        if (!$product) {
            // Produto não encontrado, redireciona para listagem
            header('Location: /index.php/products/erro/not_found');
            exit;
        }
        
        $view_data['current_product'] = $product;
        
        // Redireciona para a página de produtos com um modal de confirmação
        header('Location: /index.php/products/confirmar_exclusao/' . $product_id);
        exit;
    }
}

/**
 * Coleta dados do formulário de produto
 * @return array Dados do produto
 */
function collect_product_form_data() {
    $product_data = [
        'name' => trim($_POST['name'] ?? ''),
        'sku' => trim($_POST['sku'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'short_description' => trim($_POST['short_description'] ?? ''),
        'price' => (float)($_POST['price'] ?? 0),
        'sale_price' => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
        'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
        'weight' => !empty($_POST['weight']) ? (float)$_POST['weight'] : null,
        'dimensions' => trim($_POST['dimensions'] ?? ''),
        'featured' => isset($_POST['featured']) && $_POST['featured'] == '1',
        'status' => $_POST['status'] ?? 'Pendente'
    ];
    
    // Processa categorias
    $product_data['categories'] = isset($_POST['categories']) && is_array($_POST['categories']) 
        ? array_map('intval', $_POST['categories']) 
        : [];
    
    $product_data['primary_category'] = isset($_POST['primary_category']) 
        ? (int)$_POST['primary_category'] 
        : (empty($product_data['categories']) ? null : $product_data['categories'][0]);
    
    // Processa atributos dinâmicos
    $attributes = [];
    if (isset($_POST['attr_key']) && isset($_POST['attr_value']) && is_array($_POST['attr_key'])) {
        $keys = $_POST['attr_key'];
        $values = $_POST['attr_value'];
        
        foreach ($keys as $i => $key) {
            if (!empty($key) && isset($values[$i])) {
                $attributes[$key] = $values[$i];
            }
        }
    }
    $product_data['attributes'] = $attributes;
    
    // Processa imagens
    $images = [];
    if (isset($_POST['images']) && is_array($_POST['images'])) {
        foreach ($_POST['images'] as $image) {
            if (!empty($image)) {
                $images[] = $image;
            }
        }
    }
    $product_data['images'] = $images;
    
    return $product_data;
}

/**
 * Valida dados do produto
 * @param array $product_data Dados do produto a validar
 * @param int|null $product_id ID do produto (para edição)
 * @return array Erros de validação
 */
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
        $where = ['sku' => $product_data['sku'], 'is_deleted' => false];
        if ($product_id) {
            $where['id'] = ['operador' => '!=', 'valor' => $product_id];
        }
        
        $existing = read('products', $where);
        if (!empty($existing)) {
            $errors['sku'] = 'This SKU already exists. Please use a unique SKU.';
        }
    }
    
    if ($product_data['price'] <= 0) {
        $errors['price'] = 'Price must be greater than zero';
    }
    
    if (!empty($product_data['sale_price']) && $product_data['sale_price'] >= $product_data['price']) {
        $errors['sale_price'] = 'Sale price must be lower than the regular price';
    }
    
    if ($product_data['stock_quantity'] < 0) {
        $errors['stock_quantity'] = 'Stock quantity cannot be negative';
    }
    
    // Validação de categorias
    if (empty($product_data['categories'])) {
        $errors['categories'] = 'At least one category must be selected';
    }
    
    if (!empty($product_data['primary_category']) && !in_array($product_data['primary_category'], $product_data['categories'])) {
        $errors['primary_category'] = 'Primary category must be one of the selected categories';
    }
    
    return $errors;
}

/**
 * Verifica se há mensagens de sucesso/erro na URL
 * Usa o sistema de actions para recuperar mensagens
 */
function check_messages() {
    global $view_data;
    
    // Obtém a ação da URL
    $action = get_action();
    
    if (!$action) {
        return;
    }
    
    // Para tratamento de confirmação de exclusão
    if (strpos($action, 'confirmar_exclusao') !== false) {
        $parts = explode('|', $action);
        if (isset($parts[1]) && is_numeric($parts[1])) {
            $view_data['confirm_delete'] = (int)$parts[1];
            $view_data['delete_product'] = get_product($view_data['confirm_delete']);
        }
    }
    
    // Mensagens de sucesso
    if (strpos($action, 'sucesso') !== false) {
        $parts = explode('|', $action);
        $success_type = $parts[1] ?? '';
        
        switch ($success_type) {
            case 'add':
                $view_data['success'] = 'Product created successfully!';
                break;
            case 'update':
                $view_data['success'] = 'Product updated successfully!';
                break;
            case 'delete':
                $view_data['success'] = 'Product deleted successfully!';
                break;
        }
    }
    
    // Mensagens de erro
    if (strpos($action, 'erro') !== false) {
        $parts = explode('|', $action);
        $error_type = $parts[1] ?? '';
        
        switch ($error_type) {
            case 'not_found':
                $view_data['errors']['general'] = 'Product not found!';
                break;
            case 'delete':
                $view_data['errors']['general'] = 'Failed to delete product. Please try again.';
                break;
        }
    }
}

// Processa a requisição para produtos
process_product_request();

// Verifica mensagens de sucesso/erro
check_messages();