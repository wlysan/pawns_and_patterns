<?php
/**
 * Controller de Logout
 * Responsável pelo processamento do logout de usuários
 */

// Inicia a sessão se ainda não foi iniciada
if (!isset($_SESSION)) {
    session_start();
}

/**
 * Realiza o logout do usuário
 */
function process_logout() {
    // Encerra a sessão atual
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Invalida a sessão no banco de dados
        if (!empty($_SESSION['session_token'])) {
            try {
                // Usa a função update() do api.php para atualizar o status da sessão
                $updateData = ['status' => 'Expirado'];
                $updateWhere = [
                    'user_id' => $user_id,
                    'token' => $_SESSION['session_token']
                ];
                update('user_sessions', $updateData, $updateWhere);
            } catch (Exception $e) {
                error_log('Error during logout: ' . $e->getMessage());
            }
        }
    }
    
    // Destrói a sessão
    session_unset();
    session_destroy();
    
    // Redireciona para a página de login
    header('Location: /index.php/login');
    exit;
}

// Processa o logout (para ser executado quando este controlador for carregado)
process_logout();