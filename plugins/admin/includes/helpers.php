<?php
/**
 * Admin Dashboard Helper Functions
 * Contains utility functions specific to the admin plugin
 */

/**
 * Check if user is an administrator
 * @return bool True if admin, false otherwise
 */
function is_admin() {
    if (!function_exists('is_authenticated')) {
        return false;
    }
    
    if (!is_authenticated()) {
        return false;
    }
    
    $user = get_authenticated_user();
    return isset($user['role']) && $user['role'] === 'Administrator';
}

/**
 * Require admin privileges to access a page
 */
function require_admin_privileges() {
    if (!is_admin()) {
        // Redirect to login page
        header('Location: /index.php/login');
        exit;
    }
}

/**
 * Add admin menu items to sidebar
 */
function admin_menu_items() {
    if (!is_admin()) {
        return;
    }
    
    echo '
    <li class="nav-item">
        <a href="/index.php/admin" class="nav-link">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="/index.php/admin_settings" class="nav-link">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
    </li>
    ';
}

/**
 * Add admin assets (CSS/JS)
 */
function admin_assets() {
    $current_route = $_SERVER['REQUEST_URI'] ?? '';
    
    // Check if current route is an admin page
    if (strpos($current_route, '/admin') !== false) {
        echo '<link rel="stylesheet" href="/plugins/admin/css/admin_dashboard.css">';
        echo '<script src="/plugins/admin/js/admin_dashboard.js" defer></script>';
    }
}

/**
 * Get dashboard statistics
 * @return array Dashboard statistics
 */
function get_dashboard_stats() {
    // In a real implementation, this would fetch data from database
    return [
        'total_products' => 157,
        'total_orders' => 43,
        'pending_orders' => 12,
        'total_customers' => 89,
        'monthly_revenue' => 4325.50,
        'out_of_stock' => 8
    ];
}