<?php
// Router for Vercel
$uri = $_SERVER['REQUEST_URI'];
$uri = explode('?', $uri)[0];

// Change working directory to root so relative requires work
chdir(__DIR__ . '/..');

// Handle assets
if (strpos($uri, '/assets/') === 0) {
    $file = __DIR__ . '/..' . $uri;
    if (file_exists($file)) {
        $mime = mime_content_type($file);
        header("Content-Type: $mime");
        readfile($file);
        exit;
    }
}

// Handle request
if ($uri == '/' || $uri == '') {
    require 'index.php';
} else {
    $file = ltrim($uri, '/');
    if (file_exists($file) && is_file($file)) {
        require $file;
    } else if (file_exists($file . '.php')) {
        require $file . '.php';
    } else {
        http_response_code(404);
        echo "404 Not Found: " . $uri;
    }
}
