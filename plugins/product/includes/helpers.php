<?php
/**
 * Product Management Helper Functions
 * Contains utility functions specific to the product plugin
 */

/**
 * Add product menu items to sidebar
 */
function product_menu_items() {
    if (!is_authenticated()) {
        return;
    }
    
    echo '
    <li class="nav-item">
        <a href="#productSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <ul class="collapse list-unstyled" id="productSubmenu">
            <li>
                <a href="/index.php/products">All Products</a>
            </li>
            <li>
                <a href="/index.php/product_add">Add New Product</a>
            </li>
            <li>
                <a href="/index.php/category">Categories</a>
            </li>
            <li>
                <a href="/index.php/category_add">Add New Category</a>
            </li>
        </ul>
    </li>
    ';
}

/**
 * Add product assets (CSS/JS)
 */
function product_assets() {
    $current_route = $_SERVER['REQUEST_URI'] ?? '';
    
    // Check if current route is a product-related page
    if (strpos($current_route, '/product') !== false || 
        strpos($current_route, '/products') !== false || 
        strpos($current_route, '/category') !== false) {
        
        echo '<link rel="stylesheet" href="/plugins/product/css/products.css">';
        echo '<link rel="stylesheet" href="/plugins/product/css/menu_styles.css">';
        echo '<script src="/plugins/product/js/products.js" defer></script>';
    }
}

/**
 * Get main image URL for a product
 * @param array $product Product data
 * @return string Image URL
 */
function get_product_main_image($product) {
    if (!empty($product['images'])) {
        $images = is_array($product['images']) ? $product['images'] : unserialize($product['images']);
        return !empty($images) ? $images[0] : '/assets/images/no-image.jpg';
    }
    return '/assets/images/no-image.jpg';
}

/**
 * Format product price
 * @param float $price Price to format
 * @return string Formatted price
 */
function format_product_price($price) {
    return '€' . number_format($price, 2);
}

/**
 * Calculate discount percentage
 * @param float $regular_price Regular price
 * @param float $sale_price Sale price
 * @return int Discount percentage
 */
function get_product_discount_percent($regular_price, $sale_price) {
    if (empty($sale_price) || $regular_price <= 0) {
        return 0;
    }
    
    return round(($regular_price - $sale_price) / $regular_price * 100);
}