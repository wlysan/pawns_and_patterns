<?php
/**
 * Arquivo de autoload do plugin de autenticação
 * Carrega as dependências necessárias
 */

// Inclui o arquivo de rotas do plugin
include "plugin_routes.php";
include "api/api.php";

/**
 * Função para verificar se um usuário está autenticado
 * @return bool Retorna true se o usuário estiver autenticado, false caso contrário
 */
function is_authenticated() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Função para obter o ID do usuário autenticado
 * @return int|null Retorna o ID do usuário ou null se não estiver autenticado
 */
function get_authenticated_user_id() {
    if (!is_authenticated()) {
        return null;
    }
    
    return $_SESSION['user_id'];
}

/**
 * Função para obter os dados do usuário autenticado
 * @return array|null Retorna um array com os dados do usuário ou null se não estiver autenticado
 */
function get_authenticated_user() {
    $user_id = get_authenticated_user_id();
    
    if (!$user_id) {
        return null;
    }
    
    // Usa a função read() do api.php para buscar os dados do usuário
    $where = ['id' => $user_id, 'is_deleted' => false];
    $users = read('users', $where);
    
    return !empty($users) ? $users[0] : null;
}

/**
 * Função para exigir autenticação
 * Redireciona para a página de login caso o usuário não esteja autenticado
 */
function require_authentication() {
    if (!is_authenticated()) {
        header('Location: /index.php/login');
        exit;
    }
}