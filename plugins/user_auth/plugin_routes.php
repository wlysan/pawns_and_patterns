<?php
/**
 * User Authentication Routes
 * Defines routes for authentication functionality
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
        'controller' => 'plugins/user_auth/controllers/profile_controller.php',
        'structure' => 'app/struct/blank.php'
    ],
    '/logout' => [
        'view' => 'plugins/user_auth/views/logout_view.php',
        'controller' => 'plugins/user_auth/controllers/logout_controller.php',
        'structure' => 'app/struct/blank.php'
    ]
];