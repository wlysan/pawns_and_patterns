<?php
/**
 * View de login
 * Exibe o formulário de login e mensagens de erro/sucesso
 */

// Obtém os erros do controller, se houver
$errors = $view_data['errors'] ?? [];
$email = $view_data['email'] ?? '';

// Debug - Exibe os dados passados para a view (remover em produção)
error_log('Login view data: ' . print_r($view_data, true));

// Verifica se há mensagem de registro bem-sucedido
// Verifica se a URL contém "registered=1" como segmento, não como parâmetro GET
$registered = false;
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($request_uri, '/login/registered=1') !== false) {
    $registered = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Paws&Patterns</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/plugins/user_auth/css/auth_styles.css">
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="auth-card">
                        <div class="auth-form">
                            <div class="logo-container">
                                <div class="logo-image-container">
                                    <span class="logo-text">Paws</span>
                                    <img class="logo-image" src="/assets/images/logo.svg" alt="Paws&Patterns">
                                    <span class="logo-text">Patterns</span>
                                </div>
                                <p class="logo-tagline">Pet Boutique Ireland</p>
                            </div>
                            
                            <h2 class="auth-title">Sign In</h2>
                            
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php if (isset($errors['general'])): ?>
                                        <p><?php echo htmlspecialchars($errors['general']); ?></p>
                                    <?php else: ?>
                                        <p>Please correct the errors below:</p>
                                        <ul>
                                            <?php foreach ($errors as $field => $error): ?>
                                                <li><?php echo htmlspecialchars($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($registered): ?>
                                <div class="alert alert-success" role="alert">
                                    <p>Registration successful! You can now login with your credentials.</p>
                                </div>
                            <?php endif; ?>
                            
                            <form method="post" action="/index.php/login">
                                <div class="mb-3">
                                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                           id="email" name="email" placeholder="Email address" 
                                           value="<?php echo htmlspecialchars($email); ?>" required>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['email']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                           id="password" name="password" placeholder="Password" required>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['password']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="/index.php/forgot-password" class="text-rlp">Forgot your password?</a>
                                </div>
                                
                                <button type="submit" class="auth-btn">Sign In</button>
                                
                                <div class="social-login">
                                    <p class="text-center">Or sign in with</p>
                                    <div class="social-btns">
                                        <button type="button" class="social-btn google">
                                            <i class="fab fa-google"></i> Google
                                        </button>
                                        <button type="button" class="social-btn facebook">
                                            <i class="fab fa-facebook-f"></i> Facebook
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="container-link">
                                    <span>Don't have an account?&nbsp;-&nbsp;</span>
                                    <a href="/index.php/register" class="text-rlp">Create one now</a>
                                </div>
                            </form>
                            
                            <!-- Debug info - Remover em produção -->
                            <div class="mb-3" style="display: none;">
                                <pre><?php echo htmlspecialchars(print_r($_POST, true)); ?></pre>
                                <pre><?php echo htmlspecialchars(print_r($errors, true)); ?></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>