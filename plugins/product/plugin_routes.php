<?php
/**
 * Rotas do plugin de categorias de produtos
 * Define as rotas específicas para gerenciamento de categorias
 */

$plugin_route['product'] = [
    '/category' => [
        'view' => 'plugins/product/views/category_view.php',
        'controller' => 'plugins/product/controllers/category_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/category_add' => [
        'view' => 'plugins/product/views/category_view.php',
        'controller' => 'plugins/product/controllers/category_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/category_edit' => [
        'view' => 'plugins/product/views/category_view.php',
        'controller' => 'plugins/product/controllers/category_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/category_delete' => [
        'view' => 'plugins/product/views/category_view.php',
        'controller' => 'plugins/product/controllers/category_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],

    // Novas rotas para produtos
    '/products' => [
        'view' => 'plugins/product/views/product_view.php',
        'controller' => 'plugins/product/controllers/product_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/product_add' => [
        'view' => 'plugins/product/views/product_view.php',
        'controller' => 'plugins/product/controllers/product_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/product_edit' => [
        'view' => 'plugins/product/views/product_view.php',
        'controller' => 'plugins/product/controllers/product_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    '/product_delete' => [
        'view' => 'plugins/product/views/product_view.php',
        'controller' => 'plugins/product/controllers/product_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ],
    
    // Rota para visualização detalhada de um produto
    '/product_detail' => [
        'view' => 'plugins/product/views/product_detail_view.php',
        'controller' => 'plugins/product/controllers/product_controller.php',
        'structure' => 'plugins/admin/struct/admin_dashboard_struct.php'
    ]
];