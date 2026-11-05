<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

define('APPPATH', __DIR__ . '/application/');

require_once APPPATH . 'config/database.php';
require_once APPPATH . 'config/config.php';
require_once APPPATH . 'config/routes.php';
require_once APPPATH . 'helpers/url_helper.php';
require_once APPPATH . '../system/core/controller.php';

// Ambil URI dari query parameter 'route' atau PATH_INFO
$uri = $_GET['route'] ?? ($_SERVER['PATH_INFO'] ?? '');
$uri = trim($uri, '/');

if ($uri === '') {
  $uri = $route['default_controller'] ?? 'Katalog_controller';
}

// Cek apakah URI cocok dengan definisi di $route (seperti $route['katalog'] atau $route['katalog/detail/(:any)'])
if (isset($route[$uri])) {
  $target = $route[$uri];
} else {
  $target = $uri;
  // Cek pencocokan rute berparameter seperti katalog/detail/(:any)
  foreach ($route as $key => $val) {
    $pattern = '#^' . str_replace('(:any)', '([^/]+)', $key) . '$#';
    if (preg_match($pattern, $uri)) {
      $target = preg_replace($pattern, $val, $uri);
      break;
    }
  }
}

$segments = explode('/', $target);
$controllerName = ucfirst($segments[0]);
$methodName = $segments[1] ?? 'index';
$param = $segments[2] ?? null;

$controllerFile = APPPATH . 'controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
  require_once $controllerFile;
  if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $methodName)) {
      if ($param !== null) {
        $controller->$methodName($param);
      } else {
        $controller->$methodName();
      }
      exit;
    }
  }
}

http_response_code(404);
echo "Halaman tidak ditemukan (404).";