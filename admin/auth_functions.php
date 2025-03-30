<?php
/**
 * Admin Authentication Functions
 * Provides functions for admin authentication and permission management
 * Paws&Patterns - Pet Boutique Ireland
 */

/**
 * Checks if the current user has admin privileges
 * 
 * @return bool True if user has admin privileges, false otherwise
 */
function is_admin() {
    // If user is not authenticated, they can't be an admin
    if (!function_exists('is_authenticated') || !is_authenticated()) {
        return false;
    }
    
    // Get user details including role
    $user = get_authenticated_user();
    
    // Check if user has admin role
    if ($user && isset($user['role']) && $user['role'] === 'admin') {
        return true;
    }
    
    // Fallback to user ID check (legacy support)
    $user_id = get_authenticated_user_id();
    $admin_users = [1]; // User ID 1 is admin by default
    
    return in_array($user_id, $admin_users);
}

/**
 * Requires admin privileges to access the page
 * Redirects to login page if user is not an admin
 * 
 * @return void
 */
function require_admin_privileges() {
    if (!is_admin()) {
        // Redirect to admin login page
        header('Location: /index.php/admin_login');
        exit;
    }
}

/**
 * Checks if the current user has a specific permission
 * 
 * @param string $resource The resource being accessed
 * @param string $action The action being performed (view, create, edit, delete)
 * @return bool True if user has permission, false otherwise
 */
function has_admin_permission($resource, $action = 'view') {
    if (!is_admin()) {
        return false;
    }
    
    $user = get_authenticated_user();
    $role = $user['role'] ?? 'customer';
    
    // Check permissions in database if the table exists
    if (function_exists('read')) {
        try {
            $where = [
                'role' => $role,
                'resource' => $resource,
                'action' => $action,
                'is_deleted' => false
            ];
            
            $permissions = read('admin_permissions', $where);
            return !empty($permissions);
        } catch (Exception $e) {
            // Table might not exist yet, fallback to basic admin check
            return is_admin();
        }
    }
    
    // Fallback to simple admin check
    return is_admin();
}

/**
 * Requires a specific admin permission to access the page
 * 
 * @param string $resource The resource being accessed
 * @param string $action The action being performed (view, create, edit, delete)
 */
function require_admin_permission($resource, $action = 'view') {
    if (!has_admin_permission($resource, $action)) {
        // Redirect to admin dashboard with error
        header('Location: /index.php/admin?error=permission_denied');
        exit;
    }
}

/**
 * Loads this file early to make admin functions available
 */
function register_admin_functions() {
    // This function is called from config/hooks.php
}