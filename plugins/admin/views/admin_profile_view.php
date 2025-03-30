<?php
/**
 * View do perfil de administrador
 * Paws&Patterns - Pet Boutique Ireland
 */

// Obtém dados do usuário e mensagens do controller
$user = $view_data['user'] ?? null;
$success_message = $view_data['success_message'] ?? '';
$error_message = $view_data['error_message'] ?? '';
$is_edit_mode = $view_data['is_edit_mode'] ?? false;

// Se não houver dados de usuário, cria dados fictícios para demonstração
if (!$user) {
    $user = [
        'id' => 1,
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@pawsandpatterns.ie',
        'role' => 'Administrator',
        'last_login' => '2025-03-30 09:45:22',
        'created_at' => '2025-01-15 14:30:00'
    ];
}
?>

<div class="profile-container">
    <?php if (!empty($success_message)): ?>
    <div class="alert alert-success" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($success_message); ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error_message); ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-4">
            <!-- Perfil do Usuário -->
            <div class="card profile-card mb-4">
                <div class="card-body text-center">
                    <div class="profile-avatar-container">
                        <img src="/assets/images/avatar-placeholder.jpg" alt="Profile Avatar" class="profile-avatar">
                        <?php if ($is_edit_mode): ?>
                        <div class="avatar-overlay">
                            <label for="avatar-upload" class="avatar-change-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="avatar-upload" name="avatar" class="d-none">
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <h4 class="mt-3 mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($user['role']); ?></p>
                    
                    <?php if (!$is_edit_mode): ?>
                    <div class="mt-3">
                        <a href="/index.php/admin_profile/edit" class="btn btn-primary">Edit Profile</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Informações de Conta -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="profile-info-item">
                        <span class="profile-info-label"><i class="fas fa-envelope me-2"></i> Email</span>
                        <span class="profile-info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="profile-info-label"><i class="fas fa-user-shield me-2"></i> Role</span>
                        <span class="profile-info-value"><?php echo htmlspecialchars($user['role']); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="profile-info-label"><i class="fas fa-clock me-2"></i> Last Login</span>
                        <span class="profile-info-value"><?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="profile-info-label"><i class="fas fa-calendar-alt me-2"></i> Member Since</span>
                        <span class="profile-info-value"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Detalhes do perfil -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Details</h5>
                </div>
                <div class="card-body">
                    <?php if ($is_edit_mode): ?>
                    <!-- Formulário de edição de perfil -->
                    <form action="/index.php/admin_profile/save" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <hr class="my-4">
                        <h6 class="mb-3">Change Password</h6>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                            <div class="form-text">Leave empty if you don't want to change the password</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="/index.php/admin_profile" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                    <?php else: ?>
                    <!-- Exibição dos detalhes do perfil -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="profile-detail-label">First Name</h6>
                                <p class="profile-detail-value"><?php echo htmlspecialchars($user['first_name']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="profile-detail-label">Last Name</h6>
                                <p class="profile-detail-value"><?php echo htmlspecialchars($user['last_name']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="profile-detail-label">Email Address</h6>
                        <p class="profile-detail-value"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="mb-3">Security</h6>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div>
                            <h6 class="profile-detail-label mb-1">Password</h6>
                            <p class="profile-detail-value mb-0">••••••••••</p>
                        </div>
                        <a href="/index.php/admin_profile/edit#password" class="btn btn-sm btn-outline-primary ms-auto">Change Password</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Atividade recente -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-marker bg-primary">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Logged In</h6>
                                <p class="timeline-text">Today at <?php echo date('H:i', strtotime($user['last_login'])); ?></p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Updated Product Inventory</h6>
                                <p class="timeline-text">Yesterday at 14:23</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-marker bg-info">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Processed Order #PP-10248</h6>
                                <p class="timeline-text">29/03/2025 at 10:15</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos adicionais específicos para a página de perfil */
.profile-avatar-container {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.profile-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.profile-avatar-container:hover .avatar-overlay {
    opacity: 1;
}

.avatar-change-btn {
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
}

.profile-info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.profile-info-label {
    min-width: 120px;
    color: var(--text-muted);
}

.profile-info-value {
    font-weight: 500;
}

.profile-detail-label {
    color: var(--text-muted);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.profile-detail-value {
    font-weight: 500;
    font-size: 1rem;
}

/* Timeline styles */
.timeline {
    position: relative;
    padding-left: 2rem;
    list-style: none;
    margin: 0;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    width: 30px;
    height: 30px;
    left: -2rem;
    background-color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -1.85rem;
    top: 30px;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-title {
    margin-bottom: 0.25rem;
}

.timeline-text {
    color: var(--text-muted);
    margin-bottom: 0;
}
</style>