<?php
/**
 * Admin Login View
 * Exibe o formulário de login para administradores
 * Paws&Patterns - Pet Boutique Ireland
 */

// Obtém os erros do controller, se houver
$errors = $view_data['errors'] ?? [];
$email = $view_data['email'] ?? '';

// Mensagens de erro especiais
$blocked_attempt = $view_data['blocked_attempt'] ?? false;
$invalid_ip = $view_data['invalid_ip'] ?? false;
?>

<h2 class="admin-login-title">Administrator Access</h2>

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

<?php if ($blocked_attempt): ?>
    <div class="alert alert-danger" role="alert">
        <p><i class="fas fa-exclamation-triangle"></i> Access blocked due to multiple failed attempts. Please try again later or contact system administrator.</p>
    </div>
<?php endif; ?>

<?php if ($invalid_ip): ?>
    <div class="alert alert-danger" role="alert">
        <p><i class="fas fa-shield-alt"></i> Access denied from your current location. Administrative access is restricted to authorized networks.</p>
    </div>
<?php endif; ?>

<form method="post" action="/index.php/admin_login" id="adminLoginForm">
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
               id="email" name="email" placeholder="Admin email" 
               value="<?php echo htmlspecialchars($email); ?>" required>
        <?php if (isset($errors['email'])): ?>
            <div class="invalid-feedback">
                <?php echo htmlspecialchars($errors['email']); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                   id="password" name="password" placeholder="Password" required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <?php if (isset($errors['password'])): ?>
            <div class="invalid-feedback d-block">
                <?php echo htmlspecialchars($errors['password']); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="/index.php/forgot-password/admin" class="text-decoration-none small">Forgot password?</a>
    </div>
    
    <button type="submit" class="admin-login-btn" id="loginBtn">Sign In</button>
    
    <div class="security-info mt-3">
        <p><i class="fas fa-lock"></i> This is a secure administrative area. Unauthorized access attempts are logged and may be reported to authorities.</p>
    </div>
</form>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Show loading overlay on form submit
    const form = document.getElementById('adminLoginForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    if (form && loadingOverlay) {
        form.addEventListener('submit', function() {
            loadingOverlay.style.display = 'flex';
        });
    }
    
    // Disable autocomplete for security (optional)
    //document.getElementById('email').setAttribute('autocomplete', 'off');
    //document.getElementById('password').setAttribute('autocomplete', 'off');
});
</script>