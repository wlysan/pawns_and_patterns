<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
  Sys Var
 */


$core_structure = 'app/struct/mobile.php';

include 'config/functions.php';
include_once 'admin_hooks.php';
include_once 'api.php';


$plugins_scope = load_plugins();
include 'config/hooks.php';

foreach ($plugins_scope as $key => $value) {
    include 'plugins/' . $value . '/autoload.php';
    $plugin_location[$value] = 'plugins/' . $value . '/';
}

include 'config/routes.php';

$rota = get_route();
$action = get_action();

include get_structure($rota['route']);

/**
 * Admin Hooks Configuration
 * Registers hooks for admin functionality
 * Paws&Patterns - Pet Boutique Ireland
 */

// Include the admin authentication functions
include_once 'admin/auth_functions.php';
