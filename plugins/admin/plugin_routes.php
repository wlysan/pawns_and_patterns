<?php
/**
 * Rotas do plugin de administração
 * Define as rotas específicas para o painel administrativo
 * Paws&Patterns - Pet Boutique Ireland
 */

$plugin_route['admin'] = [
    '/admin' => [
        'view' => 'plugins/admin/views/admin_home_view.php',
        'controller' => 'plugins/admin/controllers/admin_home_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/admin_dashboard' => [
        'view' => 'plugins/admin/views/admin_home_view.php',
        'controller' => 'plugins/admin/controllers/admin_home_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/admin_settings' => [
        'view' => 'plugins/admin/views/admin_settings_view.php',
        'controller' => 'plugins/admin/controllers/admin_settings_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/admin_profile' => [
        'view' => 'plugins/admin/views/admin_profile_view.php',
        'controller' => 'plugins/admin/controllers/admin_profile_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ]
];