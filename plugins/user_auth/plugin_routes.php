<?php
/**
 * Rotas do plugin de autenticação de usuários
 * Define as rotas específicas para login, registro e logout
 */

$plugin_route['user_auth'] = [
    '/login' => [
        'view' => 'plugins/user_auth/views/login_view.php',
        'controller' => 'plugins/user_auth/controllers/login_controller.php',
        'structure' => 'app/struct/blank.php'
    ],
    '/register' => [
        'view' => 'plugins/user_auth/views/register_view.php',
        'controller' => 'plugins/user_auth/controllers/register_controller.php',
        'structure' => 'app/struct/blank.php'
    ],
    '/profile' => [
        'view' => 'plugins/user_auth/views/profile_view.php',
        'controller' => '',
        'structure' => 'app/struct/blank.php'
    ]
];