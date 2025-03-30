<?php
/**
 * User Authentication API Functions
 * Contains API functions specific to the authentication plugin
 */

/**
 * Authenticate a user with email and password
 * @param string $email User email
 * @param string $password User password
 * @return array|bool User data if authenticated, false otherwise
 */
function authenticate_user($email, $password) {
    if (empty($email) || empty($password)) {
        return false;
    }
    
    // Get user by email
    $where = ['email' => $email];
    $users = read('users', $where);
    
    if (empty($users)) {
        return false;
    }
    
    $user = $users[0];
    
    // Check if user is deleted
    if (isset($user['is_deleted']) && $user['is_deleted'] == 1) {
        return false;
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        return false;
    }
    
    // Check user status
    if (isset($user['status'])) {
        if ($user['status'] === 'Suspenso' || $user['status'] === 'Banido' || $user['status'] === 'Expirado') {
            return false;
        }
    }
    
    // Authentication successful
    return $user;
}

/**
 * Register a new user
 * @param array $user_data User data
 * @return int|bool User ID if successful, false otherwise
 */
function register_user($user_data) {
    // Validate required fields
    $required_fields = ['email', 'password', 'first_name', 'last_name'];
    foreach ($required_fields as $field) {
        if (empty($user_data[$field])) {
            return false;
        }
    }
    
    // Validate email format
    if (!filter_var($user_data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    // Check if email already exists
    $where = ['email' => $user_data['email'], 'is_deleted' => false];
    $existing_users = read('users', $where);
    
    if (!empty($existing_users)) {
        return false;
    }
    
    // Hash password
    $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);
    
    // Set default values
    $user_data['status'] = 'Pendente';
    $user_data['created_at'] = date('Y-m-d H:i:s');
    
    // Create user
    return create('users', $user_data);
}