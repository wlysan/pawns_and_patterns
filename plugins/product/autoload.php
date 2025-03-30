<?php
/**
 * Arquivo de autoload do plugin de produtos
 * Carrega as dependências necessárias para o funcionamento do plugin
 */

// Inclui o arquivo de rotas do plugin
include "plugin_routes.php";

// Inclui o arquivo de utilitários compartilhados
include "api/utils_api.php";

// Inclui os arquivos de API para categorias e produtos
include "api/category_api.php";
include "api/product_api.php";

// Inclui as funções auxiliares
include "api/product_helpers.php";

// Inclui os hooks do plugin
include "hooks/menu_hooks.php";