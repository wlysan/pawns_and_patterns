<?php
/**
 * Menu Hooks do Plugin de Produtos
 * Adiciona itens de menu relacionados ao plugin no menu lateral do dashboard
 */

/**
 * Função para adicionar os itens do menu de produtos no painel lateral
 */
function product_sidebar_menu() {
    // Verifica se o usuário está autenticado (se a função existir)
    if (function_exists('is_authenticated') && !is_authenticated()) {
        return;
    }
    
    // Obtém a rota atual para destacar o item de menu ativo
    $current_route = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    
    // Função auxiliar para verificar se a rota atual corresponde a um padrão
    $is_active = function($pattern) use ($current_route) {
        return strpos($current_route, $pattern) === 0 ? 'active' : '';
    };
    
    echo '
    <li class="nav-item">
        <a href="#productSubmenu" data-toggle="collapse" aria-expanded="' . ($is_active('/product') || $is_active('/category') ? 'true' : 'false') . '" class="nav-link dropdown-toggle ' . ($is_active('/product') || $is_active('/category') ? 'active' : '') . '">
            <div class="icon-box bg-primary">
                <ion-icon name="pricetag-outline"></ion-icon>
            </div>
            <div class="in">
                Products
                <ion-icon name="chevron-down-outline" class="collapse-icon"></ion-icon>
            </div>
        </a>
        
        <ul class="collapse list-unstyled ' . ($is_active('/product') || $is_active('/category') ? 'show' : '') . '" id="productSubmenu">
            <!-- Seção de Categorias -->
            <li class="submenu-title">Categories</li>
            <li class="nav-item">
                <a href="/index.php/category" class="nav-link ' . $is_active('/category') . '">
                    <div class="icon-box">
                        <ion-icon name="list-outline"></ion-icon>
                    </div>
                    <div class="in">List Categories</div>
                </a>
            </li>
            <li class="nav-item">
                <a href="/index.php/category_add" class="nav-link ' . $is_active('/category_add') . '">
                    <div class="icon-box">
                        <ion-icon name="add-circle-outline"></ion-icon>
                    </div>
                    <div class="in">Add Category</div>
                </a>
            </li>
            
            <!-- Separador -->
            <li class="divider"></li>
            
            <!-- Seção de Produtos -->
            <li class="submenu-title">Products</li>
            <li class="nav-item">
                <a href="/index.php/products" class="nav-link ' . $is_active('/products') . '">
                    <div class="icon-box">
                        <ion-icon name="grid-outline"></ion-icon>
                    </div>
                    <div class="in">Product List</div>
                </a>
            </li>
            <li class="nav-item">
                <a href="/index.php/product_add" class="nav-link ' . $is_active('/product_add') . '">
                    <div class="icon-box">
                        <ion-icon name="add-circle-outline"></ion-icon>
                    </div>
                    <div class="in">Add Product</div>
                </a>
            </li>
        </ul>
    </li>
    ';
}

// Registra o hook para o menu lateral
if (function_exists('add_hook')) {
    add_hook('menu_lateral_items', 'product_sidebar_menu');
}

/**
 * Hook para adicionar título nas páginas do plugin
 */
function product_page_title() {
    // Obtém a rota atual
    $current_route = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    
    // Define os títulos das páginas
    $titles = [
        '/category' => 'Categories',
        '/category_add' => 'Add Category',
        '/category_edit' => 'Edit Category',
        '/products' => 'Products',
        '/product_add' => 'Add Product',
        '/product_edit' => 'Edit Product',
        '/product_detail' => 'Product Details'
    ];
    
    // Verifica se há um título para a rota atual
    foreach ($titles as $route => $title) {
        if (strpos($current_route, $route) === 0) {
            echo '<h1 class="page-title">' . $title . '</h1>';
            echo '<p class="page-subtitle">Paws&Patterns Product Management</p>';
            break;
        }
    }
}

// Registra o hook para o título da página
if (function_exists('add_hook')) {
    add_hook('page_title', 'product_page_title');
}

/**
 * Hook para adicionar breadcrumbs nas páginas do plugin
 */
function product_breadcrumbs() {
    // Obtém a rota atual
    $current_route = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    
    // ID do registro sendo editado ou visualizado (se aplicável)
    $id = null;
    $parts = explode('/', $current_route);
    if (count($parts) > 2 && is_numeric($parts[2])) {
        $id = $parts[2];
    }
    
    // Estrutura de breadcrumbs para cada rota
    $breadcrumbs = [];
    
    // Breadcrumb de Home sempre presente
    $breadcrumbs[] = ['title' => 'Dashboard', 'url' => '/index.php/home'];
    
    // Adicionando breadcrumbs específicos por rota
    if (strpos($current_route, '/category') === 0) {
        // Rotas de categorias
        $breadcrumbs[] = ['title' => 'Categories', 'url' => '/index.php/category'];
        
        if (strpos($current_route, '/category_add') === 0) {
            $breadcrumbs[] = ['title' => 'Add New', 'url' => ''];
        } 
        else if (strpos($current_route, '/category_edit') === 0 && $id) {
            $breadcrumbs[] = ['title' => 'Edit Category', 'url' => ''];
        }
    }
    else if (strpos($current_route, '/product') === 0) {
        // Rotas de produtos
        $breadcrumbs[] = ['title' => 'Products', 'url' => '/index.php/products'];
        
        if (strpos($current_route, '/product_add') === 0) {
            $breadcrumbs[] = ['title' => 'Add New', 'url' => ''];
        } 
        else if (strpos($current_route, '/product_edit') === 0 && $id) {
            $breadcrumbs[] = ['title' => 'Edit Product', 'url' => ''];
        }
        else if (strpos($current_route, '/product_detail') === 0 && $id) {
            $breadcrumbs[] = ['title' => 'Product Details', 'url' => ''];
        }
    }
    
    // Renderiza os breadcrumbs
    if (!empty($breadcrumbs)) {
        echo '<div class="breadcrumb-container">';
        echo '<ol class="breadcrumb">';
        
        foreach ($breadcrumbs as $index => $crumb) {
            $isLast = ($index === count($breadcrumbs) - 1);
            
            if ($isLast || empty($crumb['url'])) {
                echo '<li class="breadcrumb-item active">' . htmlspecialchars($crumb['title']) . '</li>';
            } else {
                echo '<li class="breadcrumb-item"><a href="' . htmlspecialchars($crumb['url']) . '">' . htmlspecialchars($crumb['title']) . '</a></li>';
            }
        }
        
        echo '</ol>';
        echo '</div>';
    }
}

// Registra o hook para breadcrumbs
if (function_exists('add_hook')) {
    add_hook('breadcrumbs', 'product_breadcrumbs');
}