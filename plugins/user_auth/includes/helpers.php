<?php
/**
 * User Authentication Helper Functions
 * Contains utility functions specific to the authentication plugin
 */

/**
 * Check if a user is authenticated
 * @return bool True if user is authenticated, false otherwise
 */
function is_authenticated() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get the ID of the authenticated user
 * @return int|null User ID or null if not authenticated
 */
function get_authenticated_user_id() {
    if (!is_authenticated()) {
        return null;
    }
    
    return $_SESSION['user_id'];
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
    $where = ['id' => $user_id, 'is_deleted' => false];
    $users = read('users', $where);
    
    return !empty($users) ? $users[0] : null;
}

/**
 * Require authentication
 * Redirects to login page if user is not authenticated
 */
function require_authentication() {
    if (!is_authenticated()) {
        header('Location: /index.php/login');
        exit;
    }
}

/**
 * Add user authentication menu items
 */
function user_auth_menu_items() {
    if (!is_authenticated()) {
        return;
    }
    
    echo '
    <li class="nav-item">
        <a href="/index.php/profile" class="nav-link">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="/index.php/logout" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>
    ';
}

/**
 * Add authentication assets (CSS/JS)
 */
function user_auth_assets() {
    $current_route = $_SERVER['REQUEST_URI'] ?? '';
    
    if (strpos($current_route, '/login') !== false || 
        strpos($current_route, '/register') !== false || 
        strpos($current_route, '/profile') !== false) {
        
        echo '<link rel="stylesheet" href="/plugins/user_auth/css/auth_styles.css">';
        echo '<script src="/plugins/user_auth/js/auth_scripts.js" defer></script>';
    }
}

/**
 * Register a user session
 * @param int $user_id User ID
 * @return string Session token
 */
function register_user_session($user_id) {
    $token = bin2hex(random_bytes(32)); // Generate random token
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    // Calculate expiration date (30 days from now)
    $expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    // Create session record
    $sessionData = [
        'user_id' => $user_id,
        'token' => $token,
        'ip_address' => $ip_address,
        'user_agent' => $user_agent,
        'expires_at' => $expires_at,
        'status' => 'Ativo'
    ];
    
    create('user_sessions', $sessionData);
    
    // Store token in session
    $_SESSION['session_token'] = $token;
    
    return $token;
}