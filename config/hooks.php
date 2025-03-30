<?php
/**
 * Hooks System
 * Centralized hook management for plugin integration
 */

// Initialize global hook arrays if not set
if (!isset($plugin_hook)) {
    $plugin_hook = [];
}

/**
 * Register a hook for a specific action
 * @param string $hook_name Name of the hook
 * @param callable|string $callback Function or file path to execute
 * @param int $priority Priority (lower numbers execute first)
 * @return bool Success status
 */
function add_hook($hook_name, $callback, $priority = 10) {
    global $plugin_hook;
    
    // Initialize the hook array if not yet set
    if (!isset($plugin_hook[$hook_name])) {
        $plugin_hook[$hook_name] = [];
    }
    
    // Add the callback to the hook with its priority
    $plugin_hook[$hook_name][] = [
        'callback' => $callback,
        'priority' => $priority
    ];
    
    // Sort hooks by priority
    if (count($plugin_hook[$hook_name]) > 1) {
        usort($plugin_hook[$hook_name], function($a, $b) {
            return $a['priority'] - $b['priority'];
        });
    }
    
    return true;
}

/**
 * Remove a hook
 * @param string $hook_name Name of the hook
 * @param callable|string $callback Function or file path to remove
 * @return bool Success status
 */
function remove_hook($hook_name, $callback) {
    global $plugin_hook;
    
    // If hook doesn't exist, return false
    if (!isset($plugin_hook[$hook_name])) {
        return false;
    }
    
    // Find and remove the callback
    foreach ($plugin_hook[$hook_name] as $key => $hook) {
        if ($hook['callback'] === $callback) {
            unset($plugin_hook[$hook_name][$key]);
            return true;
        }
    }
    
    return false;
}

/**
 * Execute all functions hooked to a specific action
 * @param string $hook_name Name of the hook to execute
 * @param mixed ...$args Arguments to pass to the hook functions
 * @return mixed|null Result of the last hook function or null
 */
function do_action($hook_name, ...$args) {
    global $plugin_hook;
    
    $result = null;
    
    // If hook doesn't exist, return null
    if (!isset($plugin_hook[$hook_name])) {
        return $result;
    }
    
    // Execute each callback in priority order
    foreach ($plugin_hook[$hook_name] as $hook) {
        $callback = $hook['callback'];
        
        // If callback is a string (file path), include the file
        if (is_string($callback) && file_exists($callback)) {
            ob_start();
            include $callback;
            $result = ob_get_clean();
        } 
        // Otherwise execute as function
        elseif (is_callable($callback)) {
            $result = call_user_func_array($callback, $args);
        }
    }
    
    return $result;
}

/**
 * Check if a hook exists
 * @param string $hook_name Name of the hook
 * @return bool True if hook exists, false otherwise
 */
function has_hook($hook_name) {
    global $plugin_hook;
    return isset($plugin_hook[$hook_name]) && !empty($plugin_hook[$hook_name]);
}

/**
 * Get all registered hooks
 * @return array Registered hooks
 */
function get_all_hooks() {
    global $plugin_hook;
    return $plugin_hook;
}

// Initialize common hooks
$plugin_hook['menu_lateral'] = [];
$plugin_hook['menu_lateral_items'] = [];
$plugin_hook['page_assets'] = [];
$plugin_hook['breadcrumbs'] = [];
$plugin_hook['page_title'] = [];
$plugin_hook['footer'] = [];