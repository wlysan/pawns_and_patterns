<?php
$routes = [
    '/home' => [
        'view' => 'app/views/home.php',
        'controller' => 'home',
        'structure' => 'app/struct/relogio.php'
    ],
    '/login' => [
        'view' => 'app/views/login.php',
        'controller' => 'home',
        'structure' => 'app/struct/relogio.php'
    ],
    '/register' => [
        'view' => 'app/views/register.php',
        'controller' => 'home',
        'structure' => 'app/struct/relogio.php'
    ],
    '/lost_password' => [
        'view' => 'app/views/lost_password.php',
        'controller' => 'home',
        'structure' => 'app/struct/relogio.php'
    ],
    '/products' => [
        'view' => 'app/views/products.php',
        'controller' => 'home',
        'structure' => 'app/struct/relogio.php'
    ]
];
if (isset($plugin_route)) {
    foreach ($plugin_route as $key => $value) {
        $routes = array_merge($routes, $value);
    }
}
