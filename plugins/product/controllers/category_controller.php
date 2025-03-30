<?php
/**
 * Controller de Categorias de Produtos
 * Gerencia as operações CRUD das categorias de produtos
 */

// Inicializa a sessão se ainda não estiver iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Inicializa a variável global $view_data para uso na view
$view_data = [
    'errors' => [],
    'success' => '',
    'categories' => [],
    'current_category' => null,
    'page_title' => 'Product Categories',
    'mode' => 'list' // Modos: list, add, edit, delete
];

/**
 * Função principal que processa as ações do controller
 * baseado na URL e método HTTP
 */
function process_category_request() {
    global $view_data;
    
    // Obtém o caminho da URL
    $path = $_SERVER['PATH_INFO'] ?? '/';
    $path_parts = explode('/', trim($path, '/'));
    
    // Determina a ação com base na URL
    $base_route = $path_parts[0] ?? '';
    
    // Verifica se há um ID na URL (para ações de edição/visualização)
    $category_id = isset($path_parts[1]) && is_numeric($path_parts[1]) ? (int)$path_parts[1] : null;
    
    // Configura o modo baseado na rota
    switch ($base_route) {
        case 'category_add':
            $view_data['mode'] = 'add';
            $view_data['page_title'] = 'Add New Category';
            handle_add_category();
            break;
            
        case 'category_edit':
            if ($category_id) {
                $view_data['mode'] = 'edit';
                $view_data['page_title'] = 'Edit Category';
                handle_edit_category($category_id);
            } else {
                // Redireciona para a listagem se não for especificado um ID
                header('Location: /index.php/category');
                exit;
            }
            break;
            
        case 'category_delete':
            if ($category_id) {
                $view_data['mode'] = 'delete';
                handle_delete_category($category_id);
            } else {
                // Redireciona para a listagem se não for especificado um ID
                header('Location: /index.php/category');
                exit;
            }
            break;
            
        default: // 'category' ou qualquer outra rota
            $view_data['mode'] = 'list';
            load_categories();
            break;
    }
    
    // Carrega categorias para menu dropdown (para todas as telas)
    $view_data['all_categories'] = list_categories(['status' => 'Ativo']);
}

/**
 * Carrega a lista de categorias para exibição
 */
function load_categories() {
    global $view_data;
    
    // Carrega todas as categorias ativas
    $all_categories = list_categories(['status' => 'Ativo']);
    
    // Constrói árvore hierárquica para exibição
    $view_data['categories'] = build_category_tree($all_categories);
}

/**
 * Processa a adição de uma nova categoria
 */
function handle_add_category() {
    global $view_data;
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parent_id = (int)($_POST['parent_id'] ?? 0);
        $status = $_POST['status'] ?? 'Ativo';
        
        // Processa campos de atributos dinâmicos
        $attributes = [];
        
        // Processa campos de atributos dinâmicos
        if (isset($_POST['attr_key']) && isset($_POST['attr_value']) && is_array($_POST['attr_key']) && is_array($_POST['attr_value'])) {
            $keys = $_POST['attr_key'];
            $values = $_POST['attr_value'];
            
            for ($i = 0; $i < count($keys); $i++) {
                if (!empty($keys[$i])) {
                    $attributes[$keys[$i]] = $values[$i] ?? '';
                }
            }
        }
        
        // Debug - mostra os atributos recebidos
        error_log('Attributes received: ' . print_r($attributes, true));
        
        // Validação
        $errors = [];
        
        if (empty($name)) {
            $errors['name'] = 'Category name is required';
        }
        
        // Se não houver erros, salva a categoria
        if (empty($errors)) {
            $category_data = [
                'name' => $name,
                'description' => $description,
                'parent_id' => $parent_id,
                'attributes' => $attributes,
                'status' => $status
            ];
            
            $category_id = create_category($category_data);
            
            if ($category_id) {
                // Redireciona para a listagem com mensagem de sucesso
                header('Location: /index.php/category/sucesso/add');
                exit;
            } else {
                $errors['general'] = 'Failed to create category. Please try again.';
            }
        }
        
        $view_data['errors'] = $errors;
        
        // Mantém os dados inseridos para preenchimento do formulário
        $view_data['current_category'] = [
            'name' => $name,
            'description' => $description,
            'parent_id' => $parent_id,
            'attributes' => $attributes,
            'status' => $status
        ];
    }
}

/**
 * Processa a edição de uma categoria existente
 * @param int $category_id ID da categoria a ser editada
 */
function handle_edit_category($category_id) {
    global $view_data;
    
    // Carrega dados da categoria
    $category = get_category($category_id);
    
    if (!$category) {
        // Categoria não encontrada, redireciona para listagem
        header('Location: /index.php/category/erro/not_found');
        exit;
    }
    
    // Inicializa com os dados existentes
    $view_data['current_category'] = $category;
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parent_id = (int)($_POST['parent_id'] ?? 0);
        $status = $_POST['status'] ?? 'Ativo';
        
        // Valida para evitar auto-referência (categoria pai sendo ela mesma)
        if ($parent_id == $category_id) {
            $parent_id = $category['parent_id']; // Mantém o pai original se tentar selecionar a si mesmo
        }
        
        // Processa campos de atributos dinâmicos
        $attributes = [];
        
        // Processa campos de atributos dinâmicos
        if (isset($_POST['attr_key']) && isset($_POST['attr_value']) && is_array($_POST['attr_key']) && is_array($_POST['attr_value'])) {
            $keys = $_POST['attr_key'];
            $values = $_POST['attr_value'];
            
            for ($i = 0; $i < count($keys); $i++) {
                if (!empty($keys[$i])) {
                    $attributes[$keys[$i]] = $values[$i] ?? '';
                }
            }
        }
        
        // Debug - mostra os atributos recebidos
        error_log('Attributes received for update: ' . print_r($attributes, true));
        
        // Validação
        $errors = [];
        
        if (empty($name)) {
            $errors['name'] = 'Category name is required';
        }
        
        // Se não houver erros, atualiza a categoria
        if (empty($errors)) {
            $category_data = [
                'name' => $name,
                'description' => $description,
                'parent_id' => $parent_id,
                'attributes' => $attributes,
                'status' => $status
            ];
            
            $success = update_category($category_id, $category_data);
            
            if ($success) {
                // Redireciona para a listagem com mensagem de sucesso
                header('Location: /index.php/category/sucesso/update');
                exit;
            } else {
                $errors['general'] = 'Failed to update category. Please try again.';
            }
        }
        
        $view_data['errors'] = $errors;
        
        // Atualiza os dados da categoria com os valores enviados
        $view_data['current_category'] = [
            'id' => $category_id,
            'name' => $name,
            'description' => $description,
            'parent_id' => $parent_id,
            'attributes' => $attributes,
            'status' => $status
        ];
    }
}

/**
 * Processa a exclusão de uma categoria
 * @param int $category_id ID da categoria a ser excluída
 */
function handle_delete_category($category_id) {
    // Verifica se a confirmação foi enviada
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        $success = delete_category($category_id);
        
        if ($success) {
            // Redireciona para a listagem com mensagem de sucesso
            header('Location: /index.php/category/sucesso/delete');
            exit;
        } else {
            // Redireciona com mensagem de erro
            header('Location: /index.php/category/erro/delete');
            exit;
        }
    } else {
        // Redireciona para a página de categoria com um modal de confirmação
        header('Location: /index.php/category/confirmar_exclusao/' . $category_id);
        exit;
    }
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
            $view_data['delete_category'] = get_category($view_data['confirm_delete']);
        }
    }
    
    // Mensagens de erro
    if (strpos($action, 'erro') !== false) {
        $parts = explode('|', $action);
        $error_type = $parts[1] ?? '';
        
        switch ($error_type) {
            case 'not_found':
                $view_data['errors']['general'] = 'Category not found!';
                break;
            case 'delete':
                $view_data['errors']['general'] = 'Failed to delete category. Please try again.';
                break;
        }
    }
}

// Processa a requisição para categorias
process_category_request();

// Verifica mensagens de sucesso/erro
check_messages();