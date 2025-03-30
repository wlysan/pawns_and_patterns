<?php
// Inicia a sessão no começo do arquivo, antes de qualquer saída
if (!isset($_SESSION) && !headers_sent()) {
    session_start();
}

// Verifica autenticação, se a função existir
if (function_exists('is_authenticated') && !is_authenticated()) {
    // Redireciona para o login
    if (!headers_sent()) {
        header('Location: /index.php/login');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws&Patterns Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Admin Dashboard CSS -->
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-image-container">
                        <span class="logo-text">Paws</span>
                        <img class="logo-image" src="/assets/images/logo.svg" alt="Paws&Patterns">
                        <span class="logo-text">Patterns</span>
                    </div>
                    <p class="logo-tagline">Administration</p>
                </div>
                <button id="sidebarToggle" class="sidebar-toggle d-lg-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="sidebar-user">
                <div class="user-avatar">
                    <img src="/assets/images/avatar-placeholder.jpg" alt="Admin User">
                </div>
                <div class="user-info">
                    <h5>Admin User</h5>
                    <span class="user-role">Administrator</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item active">
                        <a href="/index.php/admin/dashboard" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <?php 
                    // Carrega os itens de menu dinâmicos usando o hook menu_lateral_items
                    if (function_exists('load_hook')) {
                        // Carrega os hooks dos plugins
                        ob_start(); // Inicia o buffer de saída para capturar os hooks
                        load_hook('menu_lateral_items');
                        $menu_items = ob_get_clean(); // Captura a saída dos hooks
                        
                        // Exibe os itens de menu gerados pelos plugins
                        echo $menu_items;
                    }
                    
                    // Verifica se o plugin de pedidos existe
                    $orders_plugin_path = 'plugins/orders/';
                    if (is_dir($orders_plugin_path)) {
                    ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#ordersSubmenu">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                            <i class="fas fa-chevron-right submenu-arrow"></i>
                        </a>
                        <ul class="collapse submenu" id="ordersSubmenu">
                            <li>
                                <a href="/index.php/admin/orders" class="submenu-link">
                                    <i class="fas fa-list"></i>
                                    <span>All Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="/index.php/admin/orders/pending" class="submenu-link">
                                    <i class="fas fa-clock"></i>
                                    <span>Pending</span>
                                </a>
                            </li>
                            <li>
                                <a href="/index.php/admin/orders/completed" class="submenu-link">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Completed</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    
                    <?php 
                    // Verifica se o plugin de clientes existe
                    $customers_plugin_path = 'plugins/customers/';
                    if (is_dir($customers_plugin_path)) {
                    ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#customersSubmenu">
                            <i class="fas fa-users"></i>
                            <span>Customers</span>
                            <i class="fas fa-chevron-right submenu-arrow"></i>
                        </a>
                        <ul class="collapse submenu" id="customersSubmenu">
                            <li>
                                <a href="/index.php/admin/customers" class="submenu-link">
                                    <i class="fas fa-list"></i>
                                    <span>All Customers</span>
                                </a>
                            </li>
                            <li>
                                <a href="/index.php/admin/customers/add" class="submenu-link">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Add New</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    
                    <li class="nav-header">CONTENT</li>
                    
                    <?php 
                    // Verifica se o plugin de promoções existe
                    $promotions_plugin_path = 'plugins/promotions/';
                    if (is_dir($promotions_plugin_path)) {
                    ?>
                    <li class="nav-item">
                        <a href="/index.php/admin/promotions" class="nav-link">
                            <i class="fas fa-percentage"></i>
                            <span>Promotions</span>
                        </a>
                    </li>
                    <?php } ?>
                    
                    <?php 
                    // Verifica se o plugin de avaliações existe
                    $reviews_plugin_path = 'plugins/reviews/';
                    if (is_dir($reviews_plugin_path)) {
                    ?>
                    <li class="nav-item">
                        <a href="/index.php/admin/reviews" class="nav-link">
                            <i class="fas fa-star"></i>
                            <span>Reviews</span>
                        </a>
                    </li>
                    <?php } ?>
                    
                    <li class="nav-header">SYSTEM</li>
                    
                    <li class="nav-item">
                        <a href="/index.php/admin/settings" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    
                    <?php 
                    // Verifica se o plugin de gerenciamento de usuários existe
                    $user_management_plugin_path = 'plugins/user_auth/';
                    if (is_dir($user_management_plugin_path)) {
                    ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#usersSubmenu">
                            <i class="fas fa-user-shield"></i>
                            <span>Users</span>
                            <i class="fas fa-chevron-right submenu-arrow"></i>
                        </a>
                        <ul class="collapse submenu" id="usersSubmenu">
                            <li>
                                <a href="/index.php/admin/users" class="submenu-link">
                                    <i class="fas fa-list"></i>
                                    <span>All Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="/index.php/admin/users/roles" class="submenu-link">
                                    <i class="fas fa-user-tag"></i>
                                    <span>Roles</span>
                                </a>
                            </li>
                            <li>
                                <a href="/index.php/admin/users/permissions" class="submenu-link">
                                    <i class="fas fa-lock"></i>
                                    <span>Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    
                    <li class="nav-item">
                        <a href="/index.php/logout" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navbar -->
            <header class="navbar navbar-admin">
                <div class="navbar-start">
                    <button id="mobileSidebarToggle" class="sidebar-toggle d-lg-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <form class="search-form">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                            <button class="btn btn-search" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="navbar-end">
                    <div class="navbar-item dropdown">
                        <button class="btn btn-icon" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
                            <div class="dropdown-header">
                                <span>Notifications</span>
                                <a href="#" class="text-muted">Mark all as read</a>
                            </div>
                            <div class="notifications-body">
                                <a href="#" class="dropdown-item notification-item unread">
                                    <div class="notification-icon bg-primary">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text">New order received</p>
                                        <span class="notification-time">30 min ago</span>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item notification-item unread">
                                    <div class="notification-icon bg-success">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text">New customer registered</p>
                                        <span class="notification-time">1 hour ago</span>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item notification-item">
                                    <div class="notification-icon bg-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text">Low stock alert: Leather Dog Collar</p>
                                        <span class="notification-time">3 hours ago</span>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer">
                                <a href="/index.php/admin/notifications">View All Notifications</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="navbar-item dropdown">
                        <button class="btn btn-icon" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="/index.php/admin/profile" class="dropdown-item">
                                <i class="fas fa-user me-2"></i> Profile
                            </a>
                            <a href="/index.php/admin/settings" class="dropdown-item">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="/index.php/logout" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                <!-- Breadcrumbs -->
                <div class="breadcrumbs-wrapper">
                    <?php 
                    // Carrega os breadcrumbs dinâmicos
                    if (function_exists('load_hook')) {
                        ob_start();
                        load_hook('breadcrumbs');
                        echo ob_get_clean();
                    }
                    ?>
                </div>
                
                <!-- Page Title -->
                <div class="page-header">
                    <?php 
                    // Carrega o título dinâmico da página
                    if (function_exists('load_hook')) {
                        ob_start();
                        load_hook('page_title');
                        echo ob_get_clean();
                    }
                    ?>
                </div>
                
                <!-- Main Content -->
                <div class="page-content-main">
                    <?php
                    // Carrega qualquer asset adicional necessário para esta página
                    if (function_exists('load_hook')) {
                        ob_start();
                        load_hook('page_assets');
                        echo ob_get_clean();
                    }
                    
                    // Carrega o controller e a view
                    try {
                        // Usa buffer para evitar saída prematura
                        ob_start();
                        get_std_controller($rota['route']);
                        include get_view($rota['route']);
                        echo ob_get_clean();
                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger">Error loading content: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Footer -->
            <footer class="admin-footer">
                <div class="footer-content">
                    <p>&copy; <?php echo date('Y'); ?> Paws&Patterns. All rights reserved.</p>
                    <p>Version 1.0.0</p>
                </div>
            </footer>
        </main>
    </div>

    <!-- Bootstrap JS (com Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js para dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Admin Dashboard JS -->
    <script src="/assets/js/admin.js"></script>
</body>
</html>