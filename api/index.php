<?php

// Handle static files from public/ directory
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/'
);

if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// Bootstrap Laravel from the public entry point
require __DIR__ . '/../public/index.php';
