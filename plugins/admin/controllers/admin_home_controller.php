<?php
/**
 * Controller para o dashboard administrativo
 * Paws&Patterns - Pet Boutique Ireland
 */

// Inicializa a sessão se ainda não estiver iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Verifica permissões de administrador
if (function_exists('require_admin_privileges')) {
    require_admin_privileges();
}

// Inicializa a variável global $view_data para uso na view
$view_data = [
    'dashboard_stats' => [],
    'recent_orders' => [],
    'low_stock_products' => [],
    'error' => ''
];

/**
 * Carrega estatísticas para o dashboard
 */
function load_dashboard_stats() {
    global $view_data;
    
    try {
        // Verifica se a função de API definida em autoload.php existe
        if (function_exists('get_dashboard_stats')) {
            $view_data['dashboard_stats'] = get_dashboard_stats();
        } else {
            // Dados simulados para demonstração, caso a função não exista
            $view_data['dashboard_stats'] = [
                'total_products' => 157,
                'total_orders' => 43,
                'pending_orders' => 12,
                'total_customers' => 89,
                'monthly_revenue' => 4325.50,
                'out_of_stock' => 8
            ];
        }
    } catch (Exception $e) {
        error_log('Error loading dashboard stats: ' . $e->getMessage());
        $view_data['error'] = 'Failed to load dashboard statistics. Please try refreshing the page.';
    }
}

/**
 * Carrega os pedidos recentes
 * No futuro, isso buscaria dados do banco de dados
 */
function load_recent_orders() {
    global $view_data;
    
    try {
        // Aqui seria implementada a lógica para buscar os pedidos recentes do banco de dados
        // Por enquanto, usamos dados de exemplo na view
        $view_data['recent_orders'] = [];
    } catch (Exception $e) {
        error_log('Error loading recent orders: ' . $e->getMessage());
        $view_data['error'] = 'Failed to load recent orders. Please try refreshing the page.';
    }
}

/**
 * Carrega produtos com estoque baixo
 * No futuro, isso buscaria dados do banco de dados
 */
function load_low_stock_products() {
    global $view_data;
    
    try {
        // Aqui seria implementada a lógica para buscar produtos com estoque baixo
        // Por enquanto, usamos dados de exemplo na view
        $view_data['low_stock_products'] = [];
    } catch (Exception $e) {
        error_log('Error loading low stock products: ' . $e->getMessage());
        $view_data['error'] = 'Failed to load low stock products. Please try refreshing the page.';
    }
}

// Carrega todos os dados necessários para o dashboard
load_dashboard_stats();
load_recent_orders();
load_low_stock_products();

// Em uma implementação mais avançada, poderíamos processar ações específicas aqui
// Por exemplo, se o usuário filtrar dados por período, atualizar estatísticas, etc.
// Por enquanto, apenas carregamos os dados básicos