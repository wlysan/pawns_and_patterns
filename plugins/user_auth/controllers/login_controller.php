<?php
/**
 * Controller de Login
 * Responsável pelo processamento do login de usuários
 */

// Inicializa a sessão se ainda não foi iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Inicializa a variável global $view_data para uso na view
$view_data = [
    'errors' => [],
    'email' => ''
];

/**
 * Processa a tentativa de login
 */
function process_login() {
    global $view_data;
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];
        
        // Debug - Exibe os valores recebidos (remover em produção)
        error_log('Login attempt - Email: ' . $email);
        
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
                    
                    // Debug - Exibe informações do usuário encontrado (remover em produção)
                    error_log('User found: ' . print_r($user, true));
                    
                    // Ajuste na verificação do campo is_deleted
                    $is_deleted = isset($user['is_deleted']) && $user['is_deleted'] == 1;
                    
                    if (!$is_deleted) {
                        // Verifica se a senha é válida
                        if (password_verify($password, $user['password'])) {
                            // Verifica o status do usuário
                            if (isset($user['status'])) {
                                if ($user['status'] === 'Suspenso' || $user['status'] === 'Banido') {
                                    $errors['general'] = 'Your account has been suspended. Please contact support.';
                                } elseif ($user['status'] === 'Expirado') {
                                    $errors['general'] = 'Your account has expired. Please contact support.';
                                } else {
                                    // Login bem-sucedido
                                    $_SESSION['user_id'] = $user['id'];
                                    
                                    // Atualiza o último login usando a função update()
                                    $updateData = ['last_login' => date('Y-m-d H:i:s')];
                                    $updateWhere = ['id' => $user['id']];
                                    update('users', $updateData, $updateWhere);
                                    
                                    // Registra a sessão
                                    register_user_session($user['id']);
                                    
                                    // Redireciona para a página inicial após o login
                                    header('Location: /index.php/profile');
                                    exit;
                                }
                            } else {
                                $errors['general'] = 'User account has invalid status. Please contact support.';
                            }
                        } else {
                            $errors['general'] = 'Invalid email or password';
                        }
                    } else {
                        $errors['general'] = 'This account has been deleted. Please contact support.';
                    }
                } else {
                    $errors['general'] = 'Invalid email or password';
                }
            } catch (Exception $e) {
                error_log('Error during login: ' . $e->getMessage());
                $errors['general'] = 'An error occurred during login. Please try again later.';
            }
        }
        
        // Atualiza a variável global com os dados para a view
        $view_data['errors'] = $errors;
        $view_data['email'] = $email;
    }
}

/**
 * Registra uma nova sessão de usuário no banco de dados
 */
function register_user_session($user_id) {
    $token = bin2hex(random_bytes(32)); // Gera um token aleatório
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    // Calcula a data de expiração (30 dias a partir de agora)
    $expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));
    
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
    
    return $token;
}

// Processa o login (para ser executado quando este controlador for carregado)
process_login();