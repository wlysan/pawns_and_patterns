<?php
/**
 * Hooks para adicionar itens de menu relacionados a produtos
 */

/**
 * Adiciona itens de menu relacionados a produtos no menu lateral
 */
function product_sidebar_menu_items() {
    // Verifica se o usuário está autenticado (se a função existir)
    if (function_exists('is_authenticated') && !is_authenticated()) {
        return;
    }
    
    echo '
    <li>
        <a href="#" class="item" data-bs-toggle="collapse" data-bs-target="#productSubMenu" aria-expanded="false">
            <div class="icon-box bg-primary">
                <ion-icon name="pricetag-outline"></ion-icon>
            </div>
            <div class="in">
                Products
                <ion-icon name="chevron-down-outline" class="collapse-icon"></ion-icon>
            </div>
        </a>
        <div class="collapse" id="productSubMenu">
            <ul class="sub-menu">
                <li>
                    <a href="/index.php/products" class="item">
                        <ion-icon name="list-outline" class="icon"></ion-icon> All Products
                    </a>
                </li>
                <li>
                    <a href="/index.php/product_add" class="item">
                        <ion-icon name="add-outline" class="icon"></ion-icon> Add New Product
                    </a>
                </li>
                <li>
                    <a href="/index.php/category" class="item">
                        <ion-icon name="folder-outline" class="icon"></ion-icon> Categories
                    </a>
                </li>
                <li>
                    <a href="/index.php/category_add" class="item">
                        <ion-icon name="create-outline" class="icon"></ion-icon> Add New Category
                    </a>
                </li>
            </ul>
        </div>
    </li>
    ';
}

// Registra o hook para o menu lateral
if (function_exists('add_hook')) {
    add_hook('menu_lateral_items', 'product_sidebar_menu_items');
}