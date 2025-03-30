<?php
/**
 * Admin Login Controller
 * Responsável pelo processamento do login de administradores
 * Paws&Patterns - Pet Boutique Ireland
 */

// Inicializa a sessão se ainda não foi iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Inicializa a variável global $view_data para uso na view
$view_data = [
    'errors' => [],
    'email' => '',
    'blocked_attempt' => false,
    'invalid_ip' => false
];

// Lista de IPs permitidos para acesso administrativo (opcional)
// Em produção, isto poderia ser configurado em um arquivo separado ou banco de dados
$allowed_ips = [
    '127.0.0.1',               // Localhost
    '::1',                     // Localhost IPv6
    $_SERVER['SERVER_ADDR'],   // Servidor atual
    // Adicione outros IPs permitidos aqui
];

/**
 * Verifica se o acesso é de um IP permitido
 * 
 * @return bool True se o IP for permitido, false caso contrário
 */
function is_allowed_ip() {
    global $allowed_ips;
    
    // Se a lista estiver vazia, permitir todos
    if (empty($allowed_ips)) {
        return true;
    }
    
    $client_ip = $_SERVER['REMOTE_ADDR'];
    return in_array($client_ip, $allowed_ips);
}

/**
 * Verifica se o usuário está bloqueado por tentativas excessivas
 * 
 * @param string $email Email do usuário
 * @return bool True se estiver bloqueado, false caso contrário
 */
function is_blocked($email) {
    // Em uma implementação real, isto seria verificado no banco de dados
    // Para este exemplo, sempre retornamos false
    return false;
}

/**
 * Registra tentativa de login falha
 * 
 * @param string $email Email do usuário
 * @param string $reason Motivo da falha
 */
function log_failed_attempt($email, $reason = 'Invalid credentials') {
    // Em uma implementação real, isso seria registrado no banco de dados
    error_log("Failed admin login attempt: $email - Reason: $reason - IP: {$_SERVER['REMOTE_ADDR']}");
}

/**
 * Processa a tentativa de login administrativo
 */
function process_admin_login() {
    global $view_data;
    
    // Verifica se o IP está permitido
    if (!is_allowed_ip()) {
        $view_data['invalid_ip'] = true;
        log_failed_attempt('unknown', 'Invalid IP address');
        return;
    }
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];
        
        // Debug - Exibe os valores recebidos (remover em produção)
        error_log('Admin login attempt - Email: ' . $email);
        
        // Verifica se o usuário está bloqueado
        if (is_blocked($email)) {
            $view_data['blocked_attempt'] = true;
            log_failed_attempt($email, 'Account blocked');
            return;
        }
        
        // Validação básica
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }
        
        // Se não houver erros, tenta autenticar
        if (empty($errors)) {
            try {
                // Usa a função read() do api.php para buscar o usuário
                $where = ['email' => $email];
                $users = read('users', $where);
                
                if (!empty($users) && isset($users[0])) {
                    $user = $users[0];
                    
                    // Verifica se o usuário é um administrador
                    $is_admin = isset($user['role']) && $user['role'] === 'admin';
                    
                    // Fallback para o ID 1 se a coluna role não existir
                    if (!isset($user['role']) && $user['id'] == 1) {
                        $is_admin = true;
                    }
                    
                    // Debug - Exibe informações do usuário encontrado (remover em produção)
                    error_log('User found: ' . print_r($user, true));
                    
                    // Ajuste na verificação do campo is_deleted
                    $is_deleted = isset($user['is_deleted']) && $user['is_deleted'] == 1;
                    
                    if (!$is_deleted && $is_admin) {
                        // Verifica se a senha é válida
                        if (password_verify($password, $user['password'])) {
                            // Verifica o status do usuário
                            if (isset($user['status'])) {
                                if ($user['status'] === 'Suspenso' || $user['status'] === 'Banido') {
                                    $errors['general'] = 'Your account has been suspended. Please contact support.';
                                    log_failed_attempt($email, 'Account suspended/banned');
                                } elseif ($user['status'] === 'Expirado') {
                                    $errors['general'] = 'Your account has expired. Please contact support.';
                                    log_failed_attempt($email, 'Account expired');
                                } else {
                                    // Login bem-sucedido
                                    $_SESSION['user_id'] = $user['id'];
                                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                                    $_SESSION['user_email'] = $user['email'];
                                    $_SESSION['is_admin'] = true;
                                    
                                    // Atualiza o último login usando a função update()
                                    $updateData = ['last_login' => date('Y-m-d H:i:s')];
                                    $updateWhere = ['id' => $user['id']];
                                    update('users', $updateData, $updateWhere);
                                    
                                    // Registra a sessão
                                    register_admin_session($user['id']);
                                    
                                    // Redireciona para o painel administrativo após o login
                                    header('Location: /index.php/admin');
                                    exit;
                                }
                            } else {
                                $errors['general'] = 'User account has invalid status. Please contact support.';
                                log_failed_attempt($email, 'Invalid account status');
                            }
                        } else {
                            $errors['general'] = 'Invalid email or password';
                            log_failed_attempt($email, 'Invalid password');
                        }
                    } else {
                        if ($is_deleted) {
                            $errors['general'] = 'This account has been deleted. Please contact support.';
                            log_failed_attempt($email, 'Account deleted');
                        } else if (!$is_admin) {
                            $errors['general'] = 'This account does not have administrative privileges.';
                            log_failed_attempt($email, 'Not an admin account');
                        } else {
                            $errors['general'] = 'Invalid email or password';
                            log_failed_attempt($email, 'Unknown error');
                        }
                    }
                } else {
                    $errors['general'] = 'Invalid email or password';
                    log_failed_attempt($email, 'User not found');
                }
            } catch (Exception $e) {
                error_log('Error during admin login: ' . $e->getMessage());
                $errors['general'] = 'An error occurred during login. Please try again later.';
                log_failed_attempt($email, 'System error: ' . $e->getMessage());
            }
        }
        
        // Atualiza a variável global com os dados para a view
        $view_data['errors'] = $errors;
        $view_data['email'] = $email;
    }
}

/**
 * Registra uma nova sessão de administrador no banco de dados
 * 
 * @param int $user_id ID do usuário administrador
 * @return string Token da sessão
 */
function register_admin_session($user_id) {
    $token = bin2hex(random_bytes(32)); // Gera um token aleatório
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    // Calcula a data de expiração (12 horas a partir de agora - mais curto que usuários normais)
    $expires_at = date('Y-m-d H:i:s', strtotime('+12 hours'));
    
    try {
        // Usa a função create() do api.php para inserir a sessão
        $sessionData = [
            'user_id' => $user_id,
            'token' => $token,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'expires_at' => $expires_at,
            'status' => 'Ativo'
        ];
        
        create('user_sessions', $sessionData);
        
        // Armazena o token na sessão
        $_SESSION['session_token'] = $token;
        $_SESSION['admin_session'] = true; // Marca como sessão de admin
        
        return $token;
    } catch (Exception $e) {
        error_log('Error creating admin session: ' . $e->getMessage());
        return '';
    }
}

// Processa o login administrativo (para ser executado quando este controlador for carregado)
process_admin_login();