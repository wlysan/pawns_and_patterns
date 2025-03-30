<?php
/**
 * Arquivo de autoload do plugin de administração
 * Carrega as dependências necessárias
 * Paws&Patterns - Pet Boutique Ireland
 */

// Inclui o arquivo de rotas do plugin
include "plugin_routes.php";

// Função para verificar se o usuário tem permissão de administrador
function is_admin() {
    if (!function_exists('is_authenticated')) {
        return false;
    }
    
    if (!is_authenticated()) {
        return false;
    }
    
    // Aqui você pode adicionar lógica adicional para verificar 
    // se o usuário autenticado tem permissões de administrador
    // Por exemplo, verificar um campo is_admin no perfil do usuário
    
    // Temporariamente retorna true se o usuário estiver autenticado
    // Em um ambiente de produção, isso deve verificar permissões específicas
    return true;
}

// Função para proteger páginas de administração
function require_admin_privileges() {
    if (!is_admin()) {
        // Redireciona para a página de login se não tiver privilégios
        header('Location: /index.php/login');
        exit;
    }
}

// Função para obter estatísticas do painel
function get_dashboard_stats() {
    // Este é um exemplo - em um ambiente real, você buscaria essas informações do banco de dados
    return [
        'total_products' => 157,
        'total_orders' => 43,
        'pending_orders' => 12,
        'total_customers' => 89,
        'monthly_revenue' => 4325.50,
        'out_of_stock' => 8
    ];
}

// Função para carregar os hooks de menu lateral do admin
function register_admin_hooks() {
    global $plugin_hook;
    
    // Registra o hook para adicionar itens ao menu lateral
    if (!isset($plugin_hook['menu_lateral_items'])) {
        $plugin_hook['menu_lateral_items'] = array();
    } else if (!is_array($plugin_hook['menu_lateral_items'])) {
        // Se por algum motivo não for um array, converte para array
        $plugin_hook['menu_lateral_items'] = array($plugin_hook['menu_lateral_items']);
    }
    
    // Adiciona a função que renderiza os itens de menu do admin
    $plugin_hook['menu_lateral_items'][] = 'render_admin_menu_items';
}

// Função para renderizar os itens do menu de administração
function render_admin_menu_items() {
    echo '<li>
        <a href="/index.php/admin" class="item">
            <div class="icon-box bg-primary">
                <ion-icon name="speedometer-outline"></ion-icon>
            </div>
            <div class="in">
                Admin Dashboard
            </div>
        </a>
    </li>';
}

// Registra os hooks do plugin
register_admin_hooks();