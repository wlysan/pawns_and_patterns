<?php
/**
 * View de registro
 * Exibe o formulário de registro e mensagens de erro
 */

// Obtém os erros e dados do controller, se houver
$errors = $view_data['errors'] ?? [];
$email = $view_data['email'] ?? '';
$first_name = $view_data['first_name'] ?? '';
$last_name = $view_data['last_name'] ?? '';

// Debug - Exibe os dados passados para a view (remover em produção)
error_log('View data received: ' . print_r($view_data, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Paws&Patterns</title>
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
                            
                            <h2 class="auth-title">Create Account</h2>
                            
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
                            
                            <form method="post" action="/index.php/register">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" 
                                                  class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                                  id="first_name" name="first_name" placeholder="First Name" 
                                                  value="<?php echo htmlspecialchars($first_name); ?>" required>
                                            <?php if (isset($errors['first_name'])): ?>
                                                <div class="invalid-feedback">
                                                    <?php echo htmlspecialchars($errors['first_name']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" 
                                                  class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                                  id="last_name" name="last_name" placeholder="Last Name" 
                                                  value="<?php echo htmlspecialchars($last_name); ?>" required>
                                            <?php if (isset($errors['last_name'])): ?>
                                                <div class="invalid-feedback">
                                                    <?php echo htmlspecialchars($errors['last_name']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <input type="email" 
                                          class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                          id="email" name="email" placeholder="Email address" 
                                          value="<?php echo htmlspecialchars($email); ?>" required>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['email']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-text">We'll never share your email with anyone else.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <input type="password" 
                                          class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                          id="password" name="password" placeholder="Password" required>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['password']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-text">Password must be at least 8 characters long.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <input type="password" 
                                          class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                          id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['confirm_password']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I accept the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>
                                    </label>
                                </div>
                                
                                <button type="submit" class="auth-btn">Create Account</button>
                                
                                <div class="social-login">
                                    <p class="text-center">Or register with</p>
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
                                    <span>Already have an account?&nbsp;-&nbsp;</span>
                                    <a href="/index.php/login" class="text-rlp">Sign in</a>
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