<?php

if (!function_exists('base_url')) {
  function base_url(string $uri = ''): string
  {
    global $config;
    $base = rtrim($config['base_url'], '/');
    return $uri === '' ? $base : $base . '/' . ltrim($uri, '/');
  }
}

if (!function_exists('site_url')) {
  function site_url(string $uri = ''): string
  {
    global $config;
    $base = rtrim($config['base_url'], '/');
    $indexPage = trim($config['index_page'], '/');
    $path = $indexPage === '' ? '' : '/' . $indexPage;
    $url = $base . $path;

    return $uri === '' ? $url : $url . '/' . ltrim($uri, '/');
  }
}