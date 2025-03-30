<?php
/**
 * Controller de Registro
 * Responsável pelo processamento do registro de novos usuários
 */

// Inicializa a sessão se ainda não foi iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Inicializa a variável global $view_data para uso na view
$view_data = [
    'errors' => [],
    'email' => '',
    'first_name' => '',
    'last_name' => ''
];

/**
 * Processa o registro de um novo usuário
 */
function process_registration() {
    global $view_data;
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        
        $errors = [];
        
        // Debug - Exibe os valores recebidos (remover em produção)
        error_log('Registration attempt - Email: ' . $email);
        error_log('First name: ' . $first_name);
        error_log('Last name: ' . $last_name);
        
        // Validação
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        } else {
            try {
                // Verifica se o email já está em uso usando a função read()
                $where = ['email' => $email];
                $existingUsers = read('users', $where);
                
                // Verifica se encontrou algum usuário ativo com esse email
                $emailExists = false;
                if (!empty($existingUsers)) {
                    foreach ($existingUsers as $existingUser) {
                        // Considera somente usuários que não foram excluídos
                        if (!isset($existingUser['is_deleted']) || $existingUser['is_deleted'] == 0) {
                            $emailExists = true;
                            break;
                        }
                    }
                }
                
                if ($emailExists) {
                    $errors['email'] = 'This email is already registered';
                }
            } catch (Exception $e) {
                error_log('Error checking existing email: ' . $e->getMessage());
                $errors['general'] = 'An error occurred during registration. Please try again.';
            }
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long';
        }
        
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        if (empty($first_name)) {
            $errors['first_name'] = 'First name is required';
        }
        
        if (empty($last_name)) {
            $errors['last_name'] = 'Last name is required';
        }
        
        // Debug - Exibe os erros (remover em produção)
        if (!empty($errors)) {
            error_log('Validation errors: ' . print_r($errors, true));
        }
        
        // Se não houver erros, registra o usuário
        if (empty($errors)) {
            try {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Usa a função create() do api.php para inserir o usuário
                $userData = [
                    'email' => $email,
                    'password' => $hashed_password,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'status' => 'Pendente'
                ];
                
                $userId = create('users', $userData);
                
                if ($userId) {
                    // Atualiza o status para 'Ativo' usando a função update()
                    $updateData = ['status' => 'Ativo'];
                    $updateWhere = ['id' => $userId];
                    update('users', $updateData, $updateWhere);
                    
                    // Redireciona para a página de login com mensagem de sucesso
                    // Usando o formato correto: index.php/login/registered=1 ao invés de index.php/login?registered=1
                    header('Location: /index.php/login/registered=1');
                    exit;
                } else {
                    $errors['general'] = 'Registration failed. Please try again.';
                }
            } catch (Exception $e) {
                error_log('Error during user creation: ' . $e->getMessage());
                $errors['general'] = 'Registration failed. Please try again later.';
            }
        }
        
        // Atualiza a variável global com os dados para a view
        $view_data['errors'] = $errors;
        $view_data['email'] = $email;
        $view_data['first_name'] = $first_name;
        $view_data['last_name'] = $last_name;
    }
}

// Processa o registro (para ser executado quando este controlador for carregado)
process_registration();