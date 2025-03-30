<?php
/**
 * Login Controller
 * Handles user login process
 */

// Initialize session if not started
if (!isset($_SESSION)) {
    session_start();
}

// Initialize view data
$view_data = [
    'errors' => [],
    'email' => ''
];

/**
 * Process login form submission
 */
function process_login() {
    global $view_data;
    
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];
        
        // Basic validation
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }
        
        // If no validation errors, attempt authentication
        if (empty($errors)) {
            try {
                // Get user data
                $user = authenticate_user($email, $password);
                
                if ($user) {
                    // Authentication successful - create session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    
                    // Update last login timestamp
                    $updateData = ['last_login' => date('Y-m-d H:i:s')];
                    $updateWhere = ['id' => $user['id']];
                    update('users', $updateData, $updateWhere);
                    
                    // Register session
                    register_user_session($user['id']);
                    
                    // Redirect to profile page
                    header('Location: /index.php/profile');
                    exit;
                } else {
                    $errors['general'] = 'Invalid email or password';
                }
            } catch (Exception $e) {
                error_log('Login error: ' . $e->getMessage());
                $errors['general'] = 'An error occurred. Please try again later.';
            }
        }
        
        // Update view data with errors and submitted email
        $view_data['errors'] = $errors;
        $view_data['email'] = $email;
    }
}

// Process login
process_login();