<?php
/**
 * Funções auxiliares para o gerenciamento de produtos
 * Paws&Patterns - Pet Boutique (Irlanda)
 */

/**
 * Função auxiliar para obter a URL da imagem principal de um produto
 * @param array $product Dados do produto
 * @return string URL da imagem principal
 */
function get_product_main_image($product) {
    if (!empty($product['images'])) {
        $images = is_array($product['images']) ? $product['images'] : unserialize($product['images']);
        return !empty($images) ? $images[0] : '/assets/images/no-image.jpg';
    }
    return '/assets/images/no-image.jpg';
}

/**
 * Função auxiliar para formatar preço de produtos
 * @param float $price Preço a ser formatado
 * @return string Preço formatado
 */
function format_product_price($price) {
    return '€' . number_format($price, 2);
}

/**
 * Função auxiliar para obter o desconto percentual de um produto
 * @param float $regular_price Preço regular
 * @param float $sale_price Preço de venda
 * @return int Percentual de desconto
 */
function get_product_discount_percent($regular_price, $sale_price) {
    if (empty($sale_price) || $regular_price <= 0) {
        return 0;
    }
    
    return round(($regular_price - $sale_price) / $regular_price * 100);
}

/**
 * Hook para adicionar scripts e estilos CSS nas páginas do plugin
 */
function load_product_assets() {
    // Verifica se está em uma página de produtos
    $route = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    if (strpos($route, '/product') === 0 || strpos($route, '/products') === 0 || strpos($route, '/category') === 0) {
        echo '<link rel="stylesheet" href="/plugins/product/css/products.css">';
        echo '<link rel="stylesheet" href="/plugins/product/css/menu_styles.css">';
        echo '<script src="/plugins/product/js/products.js" defer></script>';
    }
}

// Registra o hook para adicionar assets quando necessário
if (function_exists('add_hook')) {
    add_hook('page_assets', 'load_product_assets');
}