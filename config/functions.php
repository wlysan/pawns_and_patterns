<?php

$plugin_hook = array();

/**
 * Parses the current URL and returns an associative array containing the route, controller, and action.
 *
 * This function analyzes the current URL, looking for segments that follow 'index.php'. It constructs 
 * and returns an array with 'route', 'controller', and 'action' based on the URL structure. If 'index.php' 
 * is not present in the URL, the function redirects to '/index.php'.
 *
 * @return array An associative array with 'route', 'controller', and 'action', or redirects if 'index.php' is missing.
 */
function get_route()
{
    $url = $_SERVER['REQUEST_URI'];

    $route = '';

    // Check if the URL contains /index.php
    if (str_contains($url, '/index.php')) {
        // Split the URL into segments after /index.php
        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        // Find the position of 'index.php' in the segments
        $indexPosition = array_search('index.php', $segments);

        // Extract the parts after 'index.php' if they exist
        if ($indexPosition !== false && isset($segments[$indexPosition + 1])) {
            $route = "/" . $segments[$indexPosition + 1] ?? null;
            $action = $segments[$indexPosition + 3] ?? null;
            $controller = $segments[$indexPosition + 2] ?? null;

            // Here you can handle your routes/actions

            if ($route != null) {
                //carrega view
            }

            if ($action != null) {
                //  echo "Action: $action";
            }
            if ($controller != null && $action == null) {
                $action = $controller;
                $controller = null;
            }
            if (count($segments) > 3) {
                unset($segments[$indexPosition]);

                unset($segments[$indexPosition + 1]);

                //unset($segments[$indexPosition + 2]);

                $action = implode("|", $segments);
            }


            $retorno['route'] = $route;
            $retorno['controller'] = $controller;
            $retorno['action'] = $action;
            return $retorno;
        } else {
            if ($route == "") {
                $route = "/home";
            }

            $retorno['route'] = $route;

            return $retorno;
        }
    } else {
        // Redirect to /index.php if it's not present in the URL
        header('Location: /index.php');
        exit;
    }
}

/**
 * Retrieves the view path associated with a given route.
 *
 * This function looks up a provided view name in a global routes array and returns the associated view path.
 * If the view is not found in the routes array, the function returns null.
 *
 * @param string $view The name of the view to look up.
 * @return string|null The path to the view file or null if not found.
 */
function get_view($view)
{
    global $routes;

    if (array_key_exists($view, $routes)) {
        $view_add = $routes[$view]['view'];
        return $view_add;
    }
}

function get_std_controller($view)
{
    global $routes;
    global $view_act;
    global $action;
    global $pdo;

    $view_act = $view;

    if (array_key_exists($view, $routes)) {
        $controller_add = $routes[$view]['controller'];
        if (isset($controller_add) && $controller_add != '') {
            if (file_exists($controller_add)) {
                include $controller_add;
            }
        }
    }
}

function get_controller_pview()
{
    global $plugin_location;
    global $action;
    global $pdo;

    $rota = get_route();
    $plugin = getPluginName(get_view($rota['route']));

    if (isset($rota['controller']) && $rota['controller'] != '') {
        $url = $plugin_location[$plugin] . 'controllers/' . $rota['controller'] . '_controller.php';

        if (file_exists($url)) {
            include $url;
        }
    }
}

function get_action()
{
    $rota = get_route();

    if (isset($rota['action']) && $rota['action'] != '') {
        $action = $rota['action'];
        return $action;
    }

    return null;
}

function getPluginName($path)
{
    // Verifica se a string começa com "plugins/"
    if (strpos($path, 'plugins/') === 0) {
        $segments = explode('/', $path);
        if (isset($segments[1])) {
            return $segments[1];
        }
    }
    return null; // Retorna null se a string não começar com "plugins/" ou se o segundo segmento não existir
}

/**
 * Retrieves the structure file associated with a given view.
 *
 * This function looks up a provided view name in a global routes array to find its associated structure file.
 * If no structure is defined for the view, it defaults to a global core structure. It returns the path to the 
 * structure file or the default structure if none is associated with the view.
 *
 * @param string $view The name of the view for which to find the structure file.
 * @return string The path to the structure file or the default structure if none is associated.
 */
function get_structure($view)
{
    global $routes;

    if (array_key_exists($view, $routes)) {
        //$structure = $routes[$view]['structure'];
        $structure = isset($routes[$view]['structure']) ? $routes[$view]['structure'] : '';

        if ($structure == '') {
            $structure = $GLOBALS['core_structure'];
        }
    }
    return $structure;
}

/**
 * Scans the 'plugins/' directory and returns an array of its subdirectories as plugin names.
 *
 * @return array An array containing the names of the subdirectories within the 'plugins/' directory.
 */
function load_plugins()
{
    $directory = 'plugins/';
    // Check if the directory exists
    // Get the list of files and directories
    $filesAndFolders = scandir($directory);

    foreach ($filesAndFolders as $item) {
        // Construct the full path
        $fullPath = $directory . '/' . $item;

        // Skip '.' and '..' to avoid infinite loop and skip files
        if ($item == '.' || $item == '..')
            continue;

        // Check if it's a directory and not a file
        if (is_dir($fullPath)) {
            // Print the folder name
            $plugins[] = $item;
        }
    }
    return $plugins;
}

/**
 * Echoes 'active' if the current route matches the specified route.
 *
 * This function is useful for highlighting the active route in UI elements like menus.
 *
 * @param string $route The route to check against the current route.
 * @return void
 */
function highlight_route($route)
{
    $rota = get_route();
    if ($rota['route'] == $route) {
        echo 'active';
    }
}
