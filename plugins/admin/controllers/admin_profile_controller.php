<?php
/**
 * Controller para o perfil de administrador
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
    'user' => null,
    'is_edit_mode' => false,
    'success_message' => '',
    'error_message' => ''
];

/**
 * Carrega os dados do usuário atual
 */
function load_user_data() {
    global $view_data;
    
    try {
        // Em uma implementação real, isso buscaria os dados do usuário no banco de dados
        // Por enquanto, usamos valores de exemplo
        $view_data['user'] = [
            'id' => 1,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@pawsandpatterns.ie',
            'role' => 'Administrator',
            'last_login' => date('Y-m-d H:i:s'), // Horário atual como exemplo
            'created_at' => '2025-01-15 14:30:00'
        ];
    } catch (Exception $e) {
        error_log('Error loading user data: ' . $e->getMessage());
        $view_data['error_message'] = 'Failed to load user data. Please try refreshing the page.';
    }
}

/**
 * Salva as alterações no perfil do usuário
 */
function save_profile() {
    global $view_data;
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Validação dos dados do formulário
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validações específicas
            if (empty($first_name)) {
                throw new Exception('First name cannot be empty');
            }
            
            if (empty($last_name)) {
                throw new Exception('Last name cannot be empty');
            }
            
            if (empty($email)) {
                throw new Exception('Email cannot be empty');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email format');
            }
            
            // Validação de senha, apenas se o usuário estiver tentando alterá-la
            if (!empty($current_password)) {
                // Em uma implementação real, verificaria se a senha atual está correta
                
                if (empty($new_password)) {
                    throw new Exception('New password cannot be empty');
                }
                
                if (strlen($new_password) < 8) {
                    throw new Exception('New password must be at least 8 characters long');
                }
                
                if ($new_password !== $confirm_password) {
                    throw new Exception('New passwords do not match');
                }
            }
            
            // Em uma implementação real, isso salvaria os dados no banco de dados
            // Por enquanto, apenas simulamos o salvamento
            $view_data['user'] = [
                'id' => 1,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'role' => 'Administrator',
                'last_login' => date('Y-m-d H:i:s'),
                'created_at' => '2025-01-15 14:30:00'
            ];
            
            $view_data['success_message'] = 'Profile updated successfully!';
            
            // Redireciona para a visualização após salvar
            header('Location: /index.php/admin_profile');
            exit;
            
        } catch (Exception $e) {
            error_log('Error saving profile: ' . $e->getMessage());
            $view_data['error_message'] = 'Failed to save profile: ' . $e->getMessage();
            $view_data['is_edit_mode'] = true;
            
            // Carrega o usuário novamente
            load_user_data();
        }
    }
}

// Determina a ação com base na URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'edit':
        $view_data['is_edit_mode'] = true;
        load_user_data();
        break;
        
    case 'save':
        save_profile();
        break;
        
    default:
        load_user_data();
        break;
}