<?php
session_start();

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'application' . DIRECTORY_SEPARATOR);
define('SYSPATH', FCPATH . 'system' . DIRECTORY_SEPARATOR);

require_once APPPATH . 'config/config.php';
require_once APPPATH . 'config/database.php';
require_once APPPATH . 'helpers/url_helper.php';
require_once SYSPATH . 'core/Controller.php';
require_once SYSPATH . 'core/Router.php';

$route = [];
require_once APPPATH . 'config/routes.php';

// Ambil URI
$uri = $_SERVER['PATH_INFO'] ?? '';

if ($uri === '') {

    $requestPath = parse_url(
        $_SERVER['REQUEST_URI'] ?? '/',
        PHP_URL_PATH
    ) ?? '/';

    // Lokasi folder project
    $basePath = rtrim(
        str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')),
        '/'
    );

    // Hilangkan folder project dari URL
    if ($basePath !== '' && $basePath !== '/' &&
        str_starts_with($requestPath, $basePath)) {

        $uri = substr($requestPath, strlen($basePath));
    } else {
        $uri = $requestPath;
    }

    // Hilangkan /index.php jika ada
    $scriptName = basename($_SERVER['SCRIPT_NAME'] ?? '/index.php');

    if (str_starts_with($uri, '/' . $scriptName)) {
        $uri = substr($uri, strlen('/' . $scriptName));
    }
}

// Jalankan Router
$router = new Router($route);
$router->dispatch($uri);