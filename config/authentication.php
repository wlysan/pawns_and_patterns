<?php
/**
 * Authentication System
 * Provides unified session and API authentication
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is authenticated via session
 * @return bool True if authenticated, false otherwise
 */
function is_authenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get the ID of the authenticated user
 * @return int|null User ID or null if not authenticated
 */
function get_authenticated_user_id() {
    return is_authenticated() ? $_SESSION['user_id'] : null;
}

/**
 * Get the authenticated user's data
 * @return array|null User data or null if not authenticated
 */
function get_authenticated_user() {
    $user_id = get_authenticated_user_id();
    
    if (!$user_id) {
        return null;
    }
    
    // Get user data from database
    $where = ['id' => $user_id, 'is_deleted' => 0];
    $users = read('users', $where);
    
    return !empty($users) ? $users[0] : null;
}

/**
 * Check if user has a specific role
 * @param string $role Role to check
 * @return bool True if user has role, false otherwise
 */
function user_has_role($role) {
    $user = get_authenticated_user();
    
    if (!$user || !isset($user['role'])) {
        return false;
    }
    
    return $user['role'] === $role;
}

/**
 * Check if user is an admin
 * @return bool True if admin, false otherwise
 */
function is_admin() {
    return user_has_role('Administrator');
}

/**
 * Require user to be authenticated, redirect if not
 * @param string $redirect_url URL to redirect to if not authenticated
 */
function require_authentication($redirect_url = '/index.php/login') {
    if (!is_authenticated()) {
        header("Location: {$redirect_url}");
        exit;
    }
}

/**
 * Require user to have admin privileges, redirect if not
 * @param string $redirect_url URL to redirect to if not admin
 */
function require_admin_privileges($redirect_url = '/index.php/login') {
    require_authentication($redirect_url);
    
    if (!is_admin()) {
        header("Location: {$redirect_url}");
        exit;
    }
}

/**
 * Authenticate user with credentials
 * @param string $email User email
 * @param string $password User password
 * @return bool|array User data if authenticated, false otherwise
 */
function authenticate_user($email, $password) {
    // Get user by email
    $where = ['email' => $email, 'is_deleted' => 0];
    $users = read('users', $where);
    
    if (empty($users)) {
        return false;
    }
    
    $user = $users[0];
    
    // Check password
    if (!password_verify($password, $user['password'])) {
        return false;
    }
    
    // Check user status
    if (isset($user['status'])) {
        if (in_array($user['status'], ['Suspenso', 'Banido', 'Expirado'])) {
            return false;
        }
    }
    
    // Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    
    // Update last login
    $update_data = ['last_login' => date('Y-m-d H:i:s')];
    $where = ['id' => $user['id']];
    update('users', $update_data, $where);
    
    return $user;
}

/**
 * Log out the current user
 */
function logout_user() {
    // Clear session data
    session_unset();
    session_destroy();
}

/**
 * Register a new user
 * @param array $user_data User data
 * @return int|bool User ID if registered, false otherwise
 */
function register_user($user_data) {
    // Validate required fields
    $required_fields = ['email', 'password', 'first_name', 'last_name'];
    
    foreach ($required_fields as $field) {
        if (!isset($user_data[$field]) || empty($user_data[$field])) {
            return false;
        }
    }
    
    // Check if email already exists
    $where = ['email' => $user_data['email'], 'is_deleted' => 0];
    $existing_users = read('users', $where);
    
    if (!empty($existing_users)) {
        return false;
    }
    
    // Hash password
    $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);
    
    // Set default values
    $user_data['status'] = $user_data['status'] ?? 'Pendente';
    $user_data['created_at'] = date('Y-m-d H:i:s');
    
    // Create user
    return create('users', $user_data);
}

/**
 * Generate a password reset token
 * @param string $email User email
 * @return string|bool Token if generated, false otherwise
 */
function generate_password_reset_token($email) {
    // Get user by email
    $where = ['email' => $email, 'is_deleted' => 0];
    $users = read('users', $where);
    
    if (empty($users)) {
        return false;
    }
    
    $user = $users[0];
    
    // Generate token
    $token = bin2hex(random_bytes(32));
    
    // Store token in database
    $token_data = [
        'user_id' => $user['id'],
        'token' => $token,
        'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $result = create('password_reset_tokens', $token_data);
    
    return $result ? $token : false;
}

/**
 * Reset password using token
 * @param string $token Reset token
 * @param string $new_password New password
 * @return bool Success status
 */
function reset_password_with_token($token, $new_password) {
    // Get token data
    $where = ['token' => $token, 'used' => 0];
    $tokens = read('password_reset_tokens', $where);
    
    if (empty($tokens)) {
        return false;
    }
    
    $token_data = $tokens[0];
    
    // Check if token is expired
    if (strtotime($token_data['expires_at']) < time()) {
        return false;
    }
    
    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update user password
    $user_data = ['password' => $hashed_password];
    $where = ['id' => $token_data['user_id']];
    $result = update('users', $user_data, $where);
    
    if ($result) {
        // Mark token as used
        $token_update = ['used' => 1];
        $token_where = ['id' => $token_data['id']];
        update('password_reset_tokens', $token_update, $token_where);
        
        return true;
    }
    
    return false;
}

/**
 * Create a new API key for a user
 * @param int $user_id User ID
 * @return string|bool API key or false on failure
 */
function create_api_key($user_id) {
    // Generate random API key
    $api_key = bin2hex(random_bytes(32));
    
    // Store in database
    $data = [
        'key' => $api_key,
        'user_id' => $user_id,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $result = create('api_keys', $data);
    
    return $result ? $api_key : false;
}

/**
 * Get API key for a user
 * @param int $user_id User ID
 * @return string|bool API key or false if not found
 */
function get_user_api_key($user_id) {
    $where = ['user_id' => $user_id, 'is_deleted' => 0];
    $keys = read('api_keys', $where);
    
    return !empty($keys) ? $keys[0]['key'] : false;
}

/**
 * Grant permission to a user for a table operation
 * @param int $user_id User ID
 * @param string $table Table name
 * @param string $operation Permission type (read, write, delete)
 * @return bool Success status
 */
function grant_permission($user_id, $table, $operation) {
    // Check if permission already exists
    $where = [
        'user_id' => $user_id,
        'table_name' => $table,
        'permission' => $operation,
        'is_deleted' => 0
    ];
    
    $existing = read('permissions', $where);
    
    if (!empty($existing)) {
        return true; // Permission already exists
    }
    
    // Create new permission
    $data = [
        'user_id' => $user_id,
        'table_name' => $table,
        'permission' => $operation,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    return create('permissions', $data) ? true : false;
}