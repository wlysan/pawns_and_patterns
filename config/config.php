<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
  Sys Var
 */

$core_structure = 'app/struct/mobile.php';

include 'config/functions.php';

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


?>