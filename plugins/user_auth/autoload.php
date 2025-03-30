<?php
/**
 * Plugin Autoloader
 * Loads the necessary files for the plugin to function
 * 
 * Plugin Name: Product Management
 * Description: Provides product and category management functionality
 * Version: 1.0.0
 */

// Include plugin routes
include_once __DIR__ . '/plugin_routes.php';

// Include plugin helpers
include_once __DIR__ . '/includes/helpers.php';

// Include plugin API functions
include_once __DIR__ . '/includes/api.php';

// Register plugin hooks
function register_product_hooks() {
    // Register hooks for this plugin
    add_hook('menu_lateral_items', 'product_menu_items');
    add_hook('page_assets', 'product_assets');
}

// Run hook registration
register_product_hooks();