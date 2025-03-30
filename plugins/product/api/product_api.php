<?php
/**
 * API de Produtos
 * Funções para operações CRUD com produtos, integrando-se ao sistema central
 */

/**
 * Cria um novo produto
 * @param array $product_data Dados do produto
 * @return int|bool ID do produto inserido ou false em caso de erro
 */
function create_product($product_data) {
    try {
        // Converte arrays para string serializada
        if (isset($product_data['attributes']) && is_array($product_data['attributes'])) {
            $product_data['attributes'] = serialize($product_data['attributes']);
        }
        
        if (isset($product_data['images']) && is_array($product_data['images'])) {
            $product_data['images'] = serialize($product_data['images']);
        }
        
        // Gera slug a partir do nome se não fornecido
        if (!isset($product_data['slug']) && isset($product_data['name'])) {
            $product_data['slug'] = generate_slug($product_data['name'], 'products');
        }
        
        // Extrair categorias do produto se especificadas
        $categories = [];
        $primary_category = null;
        
        if (isset($product_data['categories'])) {
            $categories = $product_data['categories'];
            unset($product_data['categories']);
        }
        
        if (isset($product_data['primary_category'])) {
            $primary_category = $product_data['primary_category'];
            unset($product_data['primary_category']);
        }
        
        // Cria o produto usando a função do api.php
        $product_id = create('products', $product_data);
        
        if ($product_id) {
            // Associa às categorias, se especificadas
            if (!empty($categories)) {
                foreach ($categories as $category_id) {
                    $relation_data = [
                        'product_id' => $product_id,
                        'category_id' => $category_id,
                        'is_primary' => ($primary_category == $category_id) ? true : false,
                        'status' => 'Ativo'
                    ];
                    create('product_category_relations', $relation_data);
                }
            }
            
            return $product_id;
        }
        
        return false;
    } catch (Exception $e) {
        error_log('Error creating product: ' . $e->getMessage());
        return false;
    }
}

/**
 * Lê um produto específico por ID
 * @param int $product_id ID do produto
 * @return array|null Dados do produto ou null se não encontrado
 */
function get_product($product_id) {
    try {
        $where = ['id' => $product_id, 'is_deleted' => false];
        $products = read('products', $where);
        
        if (!empty($products) && isset($products[0])) {
            $product = $products[0];
            
            // Desserializa atributos
            if (isset($product['attributes']) && !empty($product['attributes'])) {
                $product['attributes'] = unserialize($product['attributes']);
            } else {
                $product['attributes'] = [];
            }
            
            // Desserializa imagens
            if (isset($product['images']) && !empty($product['images'])) {
                $product['images'] = unserialize($product['images']);
            } else {
                $product['images'] = [];
            }
            
            // Busca categorias do produto
            $product['categories'] = get_product_categories($product_id);
            
            // Identifica a categoria primária
            $primary_category = get_product_primary_category($product_id);
            $product['primary_category'] = $primary_category ? $primary_category['id'] : null;
            
            return $product;
        }
        
        return null;
    } catch (Exception $e) {
        error_log('Error getting product: ' . $e->getMessage());
        return null;
    }
}

/**
 * Lista produtos com filtros
 * @param array $filters Filtros para a busca (opcional)
 * @param array $pagination Dados de paginação (opcional)
 * @param array $order_by Ordenação dos resultados (opcional)
 * @return array Lista de produtos
 */
function list_products($filters = [], $pagination = [], $order_by = []) {
    try {
        // Garante que produtos excluídos não sejam listados
        $filters['is_deleted'] = false;
        
        // Configuração padrão de ordenação se não especificada
        if (empty($order_by)) {
            $order_by = ['created_at' => 'DESC'];
        }
        
        // Busca produtos
        $products = read('products', $filters, $pagination, $order_by);
        
        if (!empty($products)) {
            foreach ($products as &$product) {
                // Desserializa atributos
                if (isset($product['attributes']) && !empty($product['attributes'])) {
                    $product['attributes'] = unserialize($product['attributes']);
                } else {
                    $product['attributes'] = [];
                }
                
                // Desserializa imagens
                if (isset($product['images']) && !empty($product['images'])) {
                    $product['images'] = unserialize($product['images']);
                } else {
                    $product['images'] = [];
                }
                
                // Adiciona a imagem principal para exibição em listas
                $product['main_image'] = !empty($product['images']) ? $product['images'][0] : '/assets/images/no-image.jpg';
                
                // Busca categorias do produto
                $product['categories'] = get_product_categories($product['id']);
            }
        }
        
        return $products;
    } catch (Exception $e) {
        error_log('Error listing products: ' . $e->getMessage());
        return [];
    }
}

/**
 * Atualiza um produto existente
 * @param int $product_id ID do produto
 * @param array $product_data Novos dados do produto
 * @return bool True se atualizado com sucesso, false caso contrário
 */
function update_product($product_id, $product_data) {
    try {
        // Converte arrays para string serializada
        if (isset($product_data['attributes']) && is_array($product_data['attributes'])) {
            $product_data['attributes'] = serialize($product_data['attributes']);
        }
        
        if (isset($product_data['images']) && is_array($product_data['images'])) {
            $product_data['images'] = serialize($product_data['images']);
        }
        
        // Atualiza o slug se o nome foi alterado
        if (isset($product_data['name']) && !isset($product_data['slug'])) {
            $product_data['slug'] = generate_slug($product_data['name'], 'products', $product_id);
        }
        
        // Extrair categorias do produto se especificadas
        $categories = null;
        $primary_category = null;
        
        if (isset($product_data['categories'])) {
            $categories = $product_data['categories'];
            unset($product_data['categories']);
        }
        
        if (isset($product_data['primary_category'])) {
            $primary_category = $product_data['primary_category'];
            unset($product_data['primary_category']);
        }
        
        // Atualiza o produto usando a função do api.php
        $where = ['id' => $product_id];
        $updated = update('products', $product_data, $where);
        
        if ($updated) {
            // Atualiza categorias se especificadas
            if ($categories !== null) {
                // Remove associações existentes (exclusão lógica)
                $update_data = [
                    'is_deleted' => true,
                    'deleted_at' => date('Y-m-d H:i:s')
                ];
                $where = ['product_id' => $product_id];
                update('product_category_relations', $update_data, $where);
                
                // Cria novas associações
                foreach ($categories as $category_id) {
                    $relation_data = [
                        'product_id' => $product_id,
                        'category_id' => $category_id,
                        'is_primary' => ($primary_category == $category_id) ? true : false,
                        'status' => 'Ativo'
                    ];
                    create('product_category_relations', $relation_data);
                }
            } elseif ($primary_category !== null) {
                // Atualiza apenas a categoria primária
                $update_data = ['is_primary' => false];
                $where = ['product_id' => $product_id, 'is_deleted' => false];
                update('product_category_relations', $update_data, $where);
                
                $update_data = ['is_primary' => true];
                $where = ['product_id' => $product_id, 'category_id' => $primary_category, 'is_deleted' => false];
                update('product_category_relations', $update_data, $where);
            }
            
            return true;
        }
        
        return false;
    } catch (Exception $e) {
        error_log('Error updating product: ' . $e->getMessage());
        return false;
    }
}

/**
 * Exclui logicamente um produto
 * @param int $product_id ID do produto
 * @return bool True se excluído com sucesso, false caso contrário
 */
function delete_product($product_id) {
    try {
        $delete_data = [
            'is_deleted' => true,
            'deleted_at' => date('Y-m-d H:i:s'),
            'status' => 'Inativo'
        ];
        
        $where = ['id' => $product_id];
        return update('products', $delete_data, $where);
    } catch (Exception $e) {
        error_log('Error deleting product: ' . $e->getMessage());
        return false;
    }
}

/**
 * Obtém as categorias associadas a um produto
 * @param int $product_id ID do produto
 * @return array Lista de categorias
 */
function get_product_categories($product_id) {
    try {
        // Busca IDs das categorias do produto
        $joins = [
            [
                'table' => 'product_categories pc',
                'on' => 'pcr.category_id = pc.id',
                'type' => 'INNER'
            ]
        ];
        
        $where = [
            'pcr.product_id' => $product_id,
            'pcr.is_deleted' => false,
            'pc.is_deleted' => false
        ];
        
        $relations = read('product_category_relations pcr', $where, [], [], $joins);
        
        $categories = [];
        foreach ($relations as $relation) {
            $category = [
                'id' => $relation['category_id'],
                'name' => $relation['name'],
                'is_primary' => (bool)$relation['is_primary']
            ];
            $categories[] = $category;
        }
        
        return $categories;
    } catch (Exception $e) {
        error_log('Error getting product categories: ' . $e->getMessage());
        return [];
    }
}

/**
 * Obtém a categoria primária de um produto
 * @param int $product_id ID do produto
 * @return array|null Dados da categoria primária ou null se não houver
 */
function get_product_primary_category($product_id) {
    try {
        $joins = [
            [
                'table' => 'product_categories pc',
                'on' => 'pcr.category_id = pc.id',
                'type' => 'INNER'
            ]
        ];
        
        $where = [
            'pcr.product_id' => $product_id,
            'pcr.is_primary' => true,
            'pcr.is_deleted' => false,
            'pc.is_deleted' => false
        ];
        
        $relations = read('product_category_relations pcr', $where, [], [], $joins);
        
        if (!empty($relations) && isset($relations[0])) {
            return [
                'id' => $relations[0]['category_id'],
                'name' => $relations[0]['name']
            ];
        }
        
        return null;
    } catch (Exception $e) {
        error_log('Error getting primary category: ' . $e->getMessage());
        return null;
    }
}

/**
 * Calcula o total de produtos com base nos filtros
 * @param array $filters Filtros para a contagem
 * @return int Total de produtos
 */
function count_products($filters = []) {
    try {
        // Garante que produtos excluídos não sejam contados
        $filters['is_deleted'] = false;
        
        $products = read('products', $filters);
        return count($products);
    } catch (Exception $e) {
        error_log('Error counting products: ' . $e->getMessage());
        return 0;
    }
}

function get_products_by_category($category_id, $limit = 8) {
    // Implementação simplificada - em um sistema real, precisaríamos 
    // fazer uma consulta JOIN com product_category_relations
    $products = list_products(['status' => 'Ativo'], ['page' => 1, 'per_page' => $limit * 2]);
    
    $filtered_products = [];
    $count = 0;
    
    foreach ($products as $product) {
        if (!empty($product['categories'])) {
            foreach ($product['categories'] as $category) {
                if ($category['id'] == $category_id) {
                    $filtered_products[] = $product;
                    $count++;
                    break;
                }
            }
            
            if ($count >= $limit) {
                break;
            }
        }
    }
    
    return $filtered_products;
}

/**
 * Função para obter produtos recém-adicionados
 * @param int $limit Número máximo de produtos a retornar
 * @return array Lista de produtos recentes
 */
function get_new_products($limit = 8) {
    $filters = [
        'status' => 'Ativo'
    ];
    
    $pagination = [
        'page' => 1,
        'per_page' => $limit
    ];
    
    $order_by = [
        'created_at' => 'DESC'
    ];
    
    return list_products($filters, $pagination, $order_by);
}

/**
 * Função para obter produtos em promoção
 * @param int $limit Número máximo de produtos a retornar
 * @return array Lista de produtos em promoção
 */
function get_sale_products($limit = 8) {
    $filters = [
        'status' => 'Ativo'
    ];
    
    $pagination = [
        'page' => 1,
        'per_page' => $limit * 2 // Buscamos mais para filtrar depois
    ];
    
    $products = list_products($filters, $pagination);
    
    $sale_products = [];
    foreach ($products as $product) {
        if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']) {
            $sale_products[] = $product;
            
            if (count($sale_products) >= $limit) {
                break;
            }
        }
    }
    
    return $sale_products;
}

/**
 * Função para pesquisar produtos
 * @param string $search_term Termo de busca
 * @param array $filter_options Opções adicionais de filtro
 * @param int $page Página atual
 * @param int $per_page Itens por página
 * @return array Resultados da pesquisa e informações de paginação
 */
function search_products($search_term, $filter_options = [], $page = 1, $per_page = 12) {
    $filters = [];
    
    // Sempre filtra por produtos ativos
    $filters['status'] = 'Ativo';
    
    // Adiciona termo de busca
    if (!empty($search_term)) {
        $filters['name'] = ['operador' => 'LIKE', 'valor' => "%$search_term%"];
    }
    
    // Adiciona filtros adicionais
    if (!empty($filter_options['category_id'])) {
        // Este filtro seria implementado de maneira diferente com JOIN em um sistema real
    }
    
    if (!empty($filter_options['price_min'])) {
        $filters['price'] = ['operador' => '>=', 'valor' => floatval($filter_options['price_min'])];
    }
    
    if (!empty($filter_options['price_max'])) {
        if (isset($filters['price'])) {
            // Já existe um filtro de preço, precisamos usar um AND
            $filters['AND'] = [
                ['price' => $filters['price']],
                ['price' => ['operador' => '<=', 'valor' => floatval($filter_options['price_max'])]]
            ];
            unset($filters['price']);
        } else {
            $filters['price'] = ['operador' => '<=', 'valor' => floatval($filter_options['price_max'])];
        }
    }
    
    // Configuração de paginação
    $pagination = [
        'page' => $page,
        'per_page' => $per_page
    ];
    
    // Ordenação
    $order_by = [];
    if (!empty($filter_options['sort_by'])) {
        $sort_field = $filter_options['sort_by'];
        $sort_order = $filter_options['sort_order'] ?? 'asc';
        $order_by[$sort_field] = strtoupper($sort_order);
    } else {
        $order_by['created_at'] = 'DESC';
    }
    
    // Executa a pesquisa
    $products = list_products($filters, $pagination, $order_by);
    $total_count = count_products($filters);
    
    return [
        'products' => $products,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total_items' => $total_count,
            'total_pages' => ceil($total_count / $per_page)
        ]
    ];
}