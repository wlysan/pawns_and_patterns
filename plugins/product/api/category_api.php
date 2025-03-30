<?php
/**
 * API de Categorias de Produtos
 * Funções para gerenciamento de categorias de produtos
 */

/**
 * Função para serializar dados de categoria
 * @param array $categoryData Dados da categoria
 * @return string Dados serializados
 */
function serialize_category_data($categoryData) {
    return serialize($categoryData);
}

/**
 * Função para desserializar dados de categoria
 * @param string $serializedData Dados serializados
 * @return array Dados desserializados
 */
function unserialize_category_data($serializedData) {
    if (empty($serializedData)) {
        return [];
    }
    
    $data = @unserialize($serializedData);
    return ($data !== false) ? $data : [];
}

/**
 * Função para criar uma nova categoria
 * @param array $categoryData Dados da categoria
 * @return int|false ID da categoria criada ou false em caso de erro
 */
function create_category($categoryData) {
    // Prepara os dados para inserção
    $data = [
        'name' => $categoryData['name'],
        'slug' => generate_slug($categoryData['name'], 'product_categories'),
        'description' => $categoryData['description'] ?? '',
        'parent_id' => $categoryData['parent_id'] ?? 0,
        'attributes' => serialize_category_data($categoryData['attributes'] ?? []),
        'status' => $categoryData['status'] ?? 'Ativo',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    try {
        // Usa a função create() do api.php para inserir a categoria
        return create('product_categories', $data);
    } catch (Exception $e) {
        error_log('Error creating category: ' . $e->getMessage());
        return false;
    }
}

/**
 * Função para atualizar uma categoria existente
 * @param int $categoryId ID da categoria
 * @param array $categoryData Novos dados da categoria
 * @return bool Sucesso ou falha da operação
 */
function update_category($categoryId, $categoryData) {
    // Prepara os dados para atualização
    $data = [
        'name' => $categoryData['name'] ?? null,
        'description' => $categoryData['description'] ?? null,
        'parent_id' => $categoryData['parent_id'] ?? null,
        'attributes' => isset($categoryData['attributes']) ? serialize_category_data($categoryData['attributes']) : null,
        'status' => $categoryData['status'] ?? null,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Remove campos nulos
    $data = array_filter($data, function($value) {
        return $value !== null;
    });
    
    // Atualiza o slug apenas se o nome foi alterado
    if (isset($data['name'])) {
        $data['slug'] = generate_slug($data['name'], 'product_categories', $categoryId);
    }
    
    try {
        // Usa a função update() do api.php para atualizar a categoria
        $where = ['id' => $categoryId];
        $result = update('product_categories', $data, $where);
        return $result > 0;
    } catch (Exception $e) {
        error_log('Error updating category: ' . $e->getMessage());
        return false;
    }
}

/**
 * Função para excluir logicamente uma categoria
 * @param int $categoryId ID da categoria
 * @return bool Sucesso ou falha da operação
 */
function delete_category($categoryId) {
    try {
        // Usa a função update() do api.php para exclusão lógica
        $data = [
            'is_deleted' => true,
            'deleted_at' => date('Y-m-d H:i:s'),
            'status' => 'Inativo'
        ];
        
        $where = ['id' => $categoryId];
        $result = update('product_categories', $data, $where);
        return $result > 0;
    } catch (Exception $e) {
        error_log('Error deleting category: ' . $e->getMessage());
        return false;
    }
}

/**
 * Função para buscar uma categoria pelo ID
 * @param int $categoryId ID da categoria
 * @return array|null Dados da categoria ou null se não encontrada
 */
function get_category($categoryId) {
    try {
        // Usa a função read() do api.php para buscar a categoria
        $where = [
            'id' => $categoryId,
            'is_deleted' => false
        ];
        
        $categories = read('product_categories', $where);
        
        if (!empty($categories)) {
            $category = $categories[0];
            
            // Desserializa os atributos
            if (isset($category['attributes'])) {
                $category['attributes'] = unserialize_category_data($category['attributes']);
            }
            
            return $category;
        }
        
        return null;
    } catch (Exception $e) {
        error_log('Error getting category: ' . $e->getMessage());
        return null;
    }
}

/**
 * Função para listar todas as categorias
 * @param array $filters Filtros opcionais (parent_id, status, etc.)
 * @return array Lista de categorias
 */
function list_categories($filters = []) {
    try {
        // Prepara os filtros, sempre excluindo categorias marcadas como excluídas
        $where = ['is_deleted' => false];
        
        // Adiciona filtros opcionais
        if (isset($filters['parent_id'])) {
            $where['parent_id'] = $filters['parent_id'];
        }
        
        if (isset($filters['status'])) {
            $where['status'] = $filters['status'];
        }
        
        // Usa a função read() do api.php para listar as categorias
        $categories = read('product_categories', $where);
        
        // Desserializa os atributos de cada categoria
        foreach ($categories as &$category) {
            if (isset($category['attributes'])) {
                $category['attributes'] = unserialize_category_data($category['attributes']);
            }
        }
        
        return $categories;
    } catch (Exception $e) {
        error_log('Error listing categories: ' . $e->getMessage());
        return [];
    }
}

/**
 * Função para construir uma estrutura hierárquica de categorias
 * @param array $categories Lista plana de categorias
 * @param int $parentId ID da categoria pai
 * @return array Estrutura hierárquica
 */
function build_category_tree($categories, $parentId = 0) {
    $tree = [];
    
    foreach ($categories as $category) {
        if ($category['parent_id'] == $parentId) {
            $children = build_category_tree($categories, $category['id']);
            
            if (!empty($children)) {
                $category['children'] = $children;
            }
            
            $tree[] = $category;
        }
    }
    
    return $tree;
}