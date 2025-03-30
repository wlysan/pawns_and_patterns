<?php
/**
 * Controller para configurações do sistema
 * Paws&Patterns - Pet Boutique Ireland
 */
/*
// Inicializa a sessão se ainda não estiver iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Verifica permissões de administrador
if (function_exists('require_admin_privileges')) {
    //require_admin_privileges();
}
*/
// Inicializa a variável global $view_data para uso na view
$view_data = [
    'settings' => [],
    'is_edit_mode' => false,
    'success_message' => '',
    'error_message' => ''
];

/**
 * Carrega as configurações do sistema
 */
function load_settings() {
    global $view_data;
    
    try {
        // Em uma implementação real, isso buscaria as configurações no banco de dados
        // Por enquanto, usamos valores de exemplo
        $view_data['settings'] = [
            'store_name' => 'Paws&Patterns',
            'store_email' => 'info@pawsandpatterns.ie',
            'store_phone' => '+353 1 123 4567',
            'store_address' => '123 Pet Lane, Dublin, Ireland',
            'currency' => 'EUR',
            'tax_rate' => 23, // Taxa de IVA padrão na Irlanda
            'enable_stock_notifications' => true,
            'low_stock_threshold' => 5,
            'enable_customer_reviews' => true,
            'order_prefix' => 'PP-'
        ];
    } catch (Exception $e) {
        error_log('Error loading settings: ' . $e->getMessage());
        $view_data['error_message'] = 'Failed to load settings. Please try refreshing the page.';
    }
}

/**
 * Salva as configurações do sistema
 */
function save_settings() {
    global $view_data;
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Validação dos dados do formulário
            $store_name = trim($_POST['store_name'] ?? '');
            $store_email = trim($_POST['store_email'] ?? '');
            $store_phone = trim($_POST['store_phone'] ?? '');
            $store_address = trim($_POST['store_address'] ?? '');
            $currency = $_POST['currency'] ?? 'EUR';
            $tax_rate = floatval($_POST['tax_rate'] ?? 23);
            $enable_stock_notifications = isset($_POST['enable_stock_notifications']) ? true : false;
            $low_stock_threshold = intval($_POST['low_stock_threshold'] ?? 5);
            $enable_customer_reviews = isset($_POST['enable_customer_reviews']) ? true : false;
            $order_prefix = trim($_POST['order_prefix'] ?? 'PP-');
            
            // Validações específicas
            if (empty($store_name)) {
                throw new Exception('Store name cannot be empty');
            }
            
            if (!empty($store_email) && !filter_var($store_email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email format');
            }
            
            if ($tax_rate < 0 || $tax_rate > 100) {
                throw new Exception('Tax rate must be between 0 and 100');
            }
            
            if ($low_stock_threshold < 0) {
                throw new Exception('Low stock threshold cannot be negative');
            }
            
            // Em uma implementação real, isso salvaria as configurações no banco de dados
            // Por enquanto, apenas simulamos o salvamento
            $view_data['settings'] = [
                'store_name' => $store_name,
                'store_email' => $store_email,
                'store_phone' => $store_phone,
                'store_address' => $store_address,
                'currency' => $currency,
                'tax_rate' => $tax_rate,
                'enable_stock_notifications' => $enable_stock_notifications,
                'low_stock_threshold' => $low_stock_threshold,
                'enable_customer_reviews' => $enable_customer_reviews,
                'order_prefix' => $order_prefix
            ];
            
            $view_data['success_message'] = 'Settings saved successfully!';
            
            // Redireciona para a visualização após salvar
            header('Location: /index.php/admin_settings#' . ($_GET['section'] ?? 'general'));
            exit;
            
        } catch (Exception $e) {
            error_log('Error saving settings: ' . $e->getMessage());
            $view_data['error_message'] = 'Failed to save settings: ' . $e->getMessage();
            $view_data['is_edit_mode'] = true;
        }
    }
}

// Determina a ação com base na URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'edit':
        $view_data['is_edit_mode'] = true;
        load_settings();
        break;
        
    case 'save':
        save_settings();
        break;
        
    default:
        load_settings();
        break;
}