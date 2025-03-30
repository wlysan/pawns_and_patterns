<?php
/**
 * Admin Hooks Configuration
 * Registers hooks for admin functionality
 * Paws&Patterns - Pet Boutique Ireland
 */

// Include the admin authentication functions
include_once 'admin/auth_functions.php';

// Register admin-related hooks
function register_admin_hooks() {
    global $plugin_hook;
    
    // Add hook for admin menu items
    if (!isset($plugin_hook['admin_menu_items'])) {
        $plugin_hook['admin_menu_items'] = array();
    } else if (!is_array($plugin_hook['admin_menu_items'])) {
        $plugin_hook['admin_menu_items'] = array($plugin_hook['admin_menu_items']);
    }
    
    // Add hook for admin init (runs early in the admin section)
    if (!isset($plugin_hook['admin_init'])) {
        $plugin_hook['admin_init'] = array();
    } else if (!is_array($plugin_hook['admin_init'])) {
        $plugin_hook['admin_init'] = array($plugin_hook['admin_init']);
    }
    
    // Add the admin init function that runs on every admin page
    $plugin_hook['admin_init'][] = 'admin_init_function';
}

/**
 * Function that runs on every admin page load
 */
function admin_init_function() {
    // Check admin privileges
    require_admin_privileges();
    
    // Additional admin initialization can be added here
}

// Register the hooks
register_admin_hooks();