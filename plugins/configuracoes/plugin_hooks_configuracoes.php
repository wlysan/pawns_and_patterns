<?php

$demo_plugin_hook['menu_lateral'] = [
    'profilebox' => [
        'hooklocation' => 'plugins/demo/hooks/profilebox.php',
        'hookname'=>'profilebox',
        'hookpermission' => '1'
    ],
];
$demo_plugin_hook['menu_lateral_items'] = [
    'link_demo' => [
        'hooklocation' => 'plugins/demo/hooks/link_menu.php',
        'hookname'=>'link_demo',
        'hookpermission' => '1'
    ],
];



if(is_array($plugin_hook['menu_lateral'])){
    array_merge($plugin_hook['menu_lateral'],$demo_plugin_hook['menu_lateral']);
}else{
    $plugin_hook['menu_lateral'] = $demo_plugin_hook['menu_lateral'];
}
if(is_array($plugin_hook['menu_lateral_items'])){
    array_merge($plugin_hook['menu_lateral_items'],$demo_plugin_hook['menu_lateral_items']);
}else{
    $plugin_hook['menu_lateral_items'] = $demo_plugin_hook['menu_lateral_items'];
}
